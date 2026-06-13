// ── Theme ────────────────────────────────────────────────
let currentTheme = localStorage.getItem('parkaid-theme') || 'dark';
let isAnimating = false;

// ── Select Mode ───────────────────────────────────────────
let isSelectMode = false;
let selectedSlotIds = new Set();

// ── Management log ────────────────────────────────────────
async function logManagementAction(action, slotId = null, floor = null, type = null, quantity = 1) {
    try {
        const payload = { action, quantity: quantity || 1 };
        if (slotId !== null && slotId !== undefined && slotId !== '') payload.slot_id = slotId;
        if (floor  !== null && floor  !== undefined && floor  !== '') payload.floor   = floor;
        if (type   !== null && type   !== undefined && type   !== '') payload.type    = type;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('📝 Logging management action:', payload);

        const response = await fetch('/management-log', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            console.error('❌ Failed to log management action. Status:', response.status);
        } else {
            console.log('✅ Management action logged successfully');
        }
    } catch (e) {
        console.error('❌ Failed to log management action:', e);
    }
}

// ── Theme ─────────────────────────────────────────────────
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('parkaid-theme', theme);
    currentTheme = theme;
}

window.toggleTheme = function() {
    applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
};

// ── Slots state ───────────────────────────────────────────
// Demo slots only used when server is unreachable
let slots = [
    { id: 'A-01', number: '01', floor: 'Ground Floor', type: 'Regular', occupied: false, time: 'just now' },
    { id: 'A-02', number: '02', floor: 'Ground Floor', type: 'Regular', occupied: false, time: 'just now' },
    { id: 'B-01', number: '01', floor: 'Ground Floor', type: 'Regular', occupied: false, time: 'just now' },
    { id: 'B-02', number: '02', floor: 'Ground Floor', type: 'Regular', occupied: false, time: 'just now' },
];

window.isDemoMode = true;

// ── previousSlots: keyed map for O(1) lookup ─────────────
// Using a Map<id, occupied> avoids the array-scan bug and
// ensures every server slot is compared correctly regardless
// of sort order or ID format.
let previousSlotMap = new Map();   // id → occupied (boolean)
let isFirstLoad = true;

// ── Load slots from server ────────────────────────────────
async function loadSlotsFromServer() {
    try {
        const response = await fetch('/api/slots');
        if (!response.ok) {
            console.warn('⚠️ Server returned error:', response.status);
            window.isDemoMode = true;
            if (isFirstLoad) { renderFloors(); isFirstLoad = false; }
            return false;
        }

        const data = await response.json();

        if (!data || data.length === 0) {
            window.isDemoMode = false;
            if (isFirstLoad) { renderFloors(); isFirstLoad = false; }
            return false;
        }

        window.isDemoMode = false;
        processServerSlots(data);
        return true;

    } catch (error) {
        console.warn('⚠️ Server not available, using demo mode');
        window.isDemoMode = true;
        if (isFirstLoad) { renderFloors(); isFirstLoad = false; }
        return false;
    }
}

// ── Debug: Check what data is coming from server ──
async function debugServerData() {
    try {
        const response = await fetch('/api/slots');
        const data = await response.json();
        console.log('🔍 DEBUG - Raw server data:', JSON.stringify(data, null, 2));
        console.log('🔍 DEBUG - Number of slots:', data.length);
        data.forEach(slot => {
            console.log(`  Slot ${slot.id}: occupied=${slot.occupied}, time=${slot.time}`);
        });
        return data;
    } catch (error) {
        console.error('🔍 DEBUG - Error fetching:', error);
    }
}

// Call this manually from browser console or add a button
window.debugServerData = debugServerData;

// ── Core: process incoming server data ───────────────────
function processServerSlots(serverSlots) {
    if (isFirstLoad) {
        // ── First load: full render, build map from server data ──
        slots = serverSlots;
        previousSlotMap.clear();
        serverSlots.forEach(s => previousSlotMap.set(s.id, s.occupied));
        renderFloors();
        isFirstLoad = false;
        console.log('✅ Initial render with', serverSlots.length, 'slots');
        return;
    }

    // ── Subsequent polls: find what changed ──────────────
    const changedSlots = [];

    serverSlots.forEach(newSlot => {
        const prevOccupied = previousSlotMap.get(newSlot.id);

        // New slot appeared (not in previous map) — need re-render
        if (prevOccupied === undefined) {
            changedSlots.push({ id: newSlot.id, occupied: newSlot.occupied, time: newSlot.time, isNew: true });
            return;
        }

        // Status changed
        if (prevOccupied !== newSlot.occupied) {
            changedSlots.push({ id: newSlot.id, occupied: newSlot.occupied, time: newSlot.time, isNew: false });
        }
    });

    // Check for removed slots (in map but not in server response)
    const serverIds = new Set(serverSlots.map(s => s.id));
    let hasRemovals = false;
    previousSlotMap.forEach((_, id) => {
        if (!serverIds.has(id)) hasRemovals = true;
    });

    // ── Always update previousSlotMap BEFORE any DOM work ──
    previousSlotMap.clear();
    serverSlots.forEach(s => previousSlotMap.set(s.id, s.occupied));

    // ── Update local slots array to match server ──────────
    slots = serverSlots;

    // If new slots appeared or slots were removed, do a full re-render
    const hasNewSlots = changedSlots.some(c => c.isNew);
    if (hasNewSlots || hasRemovals) {
        console.log('🔄 Full re-render: new or removed slots detected');
        renderFloors();
        return;
    }

    // ── Animate only the changed slots ───────────────────
    if (changedSlots.length > 0) {
        console.log('🔄 Animating', changedSlots.length, 'changed slot(s):', changedSlots.map(c => `${c.id}→${c.occupied ? 'occupied' : 'vacant'}`).join(', '));
        changedSlots.forEach(changed => {
            animateSlotChange(changed.id, changed.occupied);
            updateSlotTimeUI(changed.id, changed.time);
        });
        refreshStats();
        updateSidebarStats();
    }
}

// ── Sync slots to server (after manual add/delete) ───────
async function syncSlotsToServer() {
    if (window.isDemoMode) return;
    try {
        const response = await fetch('/api/sync-slots', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ slots })
        });
        if (response.ok) console.log('✅ Synced to server');
    } catch (error) {
        console.error('❌ Failed to sync to server:', error);
    }
}

// ── Animate a single slot change ─────────────────────────
function animateSlotChange(slotId, isOccupied) {
    const card  = document.getElementById('card-'  + slotId);
    const car   = document.getElementById('car-'   + slotId);
    const empty = document.getElementById('empty-' + slotId);
    const badge = document.getElementById('badge-' + slotId);

    if (!card) {
        // Card not in DOM — do a full re-render to be safe
        console.warn('⚠️ Card not found for slot', slotId, '— triggering full re-render');
        renderFloors();
        return;
    }

    isAnimating = true;

    card.classList.add('flash');
    setTimeout(() => card.classList.remove('flash'), 500);

    if (isOccupied) {
        card.classList.remove('vacant');
        card.classList.add('occupied');
        if (empty) empty.classList.add('hidden');
        if (car) {
            car.classList.remove('hidden', 'leaving');
            car.classList.add('entering');
            setTimeout(() => { car.classList.remove('entering'); isAnimating = false; }, 600);
        } else {
            isAnimating = false;
        }
        if (badge) {
            badge.className = 'status-pill occupied';
            badge.innerHTML = '<span class="status-dot"></span> Occupied';
        }
    } else {
        card.classList.remove('occupied');
        card.classList.add('vacant');
        if (car) {
            car.classList.add('leaving');
            setTimeout(() => {
                car.classList.add('hidden');
                car.classList.remove('leaving');
                if (empty) empty.classList.remove('hidden');
                isAnimating = false;
            }, 400);
        } else {
            if (empty) empty.classList.remove('hidden');
            isAnimating = false;
        }
        if (badge) {
            badge.className = 'status-pill vacant';
            badge.innerHTML = '<span class="status-dot"></span> Vacant';
        }
    }
}

// ── Update only the time label on a slot card ─────────────
function updateSlotTimeUI(slotId, time) {
    const el = document.getElementById('time-' + slotId);
    if (el) el.textContent = 'Last update: ' + time;
}

// ── Format number with leading zeros ─────────────────────
function formatNumber(num, originalFormat) {
    const originalLength = originalFormat.length;
    const numStr = num.toString();
    return numStr.length < originalLength
        ? '0'.repeat(originalLength - numStr.length) + numStr
        : numStr;
}

// ── Clock ─────────────────────────────────────────────────
function tick() {
    const clock = document.getElementById('clock');
    if (clock) clock.textContent = new Date().toLocaleTimeString();
}
tick();
setInterval(tick, 1000);

// ── Car SVG ───────────────────────────────────────────────
function carSVG() {
    return `<svg width="68" height="68" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="12" y="20" width="40" height="28" rx="6" fill="#1e293b" stroke="#ef4444" stroke-width="1.5"/>
        <rect x="16" y="14" width="32" height="16" rx="4" fill="#334155"/>
        <rect x="18" y="16" width="28" height="10" rx="3" fill="#1e40af" opacity="0.6"/>
        <circle cx="18" cy="48" r="5" fill="#0f172a" stroke="#64748b" stroke-width="2"/>
        <circle cx="18" cy="48" r="2.5" fill="#475569"/>
        <circle cx="46" cy="48" r="5" fill="#0f172a" stroke="#64748b" stroke-width="2"/>
        <circle cx="46" cy="48" r="2.5" fill="#475569"/>
        <rect x="10" y="32" width="6" height="4" rx="1" fill="#fef08a" opacity="0.8"/>
        <rect x="48" y="32" width="6" height="4" rx="1" fill="#f87171" opacity="0.8"/>
        <rect x="14" y="24" width="8" height="4" rx="1" fill="#1e40af" opacity="0.5"/>
        <rect x="42" y="24" width="8" height="4" rx="1" fill="#1e40af" opacity="0.5"/>
    </svg>`;
}

// ── Build single bay HTML ─────────────────────────────────
function buildBayHTML(slot) {
    const status    = slot.occupied ? 'occupied' : 'vacant';
    const carHide   = slot.occupied ? '' : 'hidden';
    const emptyHide = slot.occupied ? 'hidden' : '';

    return `
    <div class="parking-bay ${status} bay-pop" id="card-${slot.id}" onclick="window.toggleSlot && window.toggleSlot('${slot.id}')">
        <input
            type="checkbox"
            class="slot-checkbox"
            id="cb-${slot.id}"
            onclick="window.toggleSlotSelect(event, '${slot.id}')"
        />
        <div class="bay-lines"></div>
        <div class="p-3 pb-4">
            <span class="slot-num">${slot.id}</span>
            <span class="floor-badge">${slot.floor}</span>
            <div class="car-wrap" style="height:100px;">
                <div class="bay-empty-marker ${emptyHide}" id="empty-${slot.id}">
                    <div class="p-marker">P</div>
                    <p style="font-size:10px; color:var(--text-faint); letter-spacing:0.1em; text-transform:uppercase;">${slot.type}</p>
                </div>
                <div class="car-icon ${carHide}" id="car-${slot.id}">${carSVG()}</div>
            </div>
            <div class="text-center mt-1">
                <span class="status-pill ${status}" id="badge-${slot.id}">
                    <span class="status-dot"></span>
                    ${slot.occupied ? 'Occupied' : 'Vacant'}
                </span>
            </div>
            <p class="text-center mono mt-2" style="font-size:10px; color:var(--text-faint);" id="time-${slot.id}">Last update: ${slot.time}</p>
        </div>
    </div>`;
}

// ── Render all floors ─────────────────────────────────────
function renderFloors() {
    const container = document.getElementById('floors-container');
    if (!container) return;
    container.innerHTML = '';

    const floors = {};
    slots.forEach(s => {
        if (!floors[s.floor]) floors[s.floor] = [];
        floors[s.floor].push(s);
    });

    // Sort floors in a logical order
    const floorOrder = ['Basement 2', 'Basement 1', 'Ground Floor', 'Floor 1', 'Floor 2', 'Floor 3'];
    const sortedFloorNames = Object.keys(floors).sort((a, b) => {
        const ai = floorOrder.indexOf(a);
        const bi = floorOrder.indexOf(b);
        if (ai === -1 && bi === -1) return a.localeCompare(b);
        if (ai === -1) return 1;
        if (bi === -1) return -1;
        return ai - bi;
    });

    sortedFloorNames.forEach(floor => {
        // Custom sorting to swap B-01 and B-02 on Ground Floor
        let floorSlots = floors[floor].slice();
        
        if (floor === 'Ground Floor') {
            // Define custom display order: A-01, A-02, B-02, B-01
            const displayOrder = ['A-01', 'A-02', 'B-02', 'B-01'];
            floorSlots.sort((a, b) => {
                const indexA = displayOrder.indexOf(a.id);
                const indexB = displayOrder.indexOf(b.id);
                
                // If both are in our custom order, use that
                if (indexA !== -1 && indexB !== -1) {
                    return indexA - indexB;
                }
                // If only one is in custom order, custom order items come first
                if (indexA !== -1) return -1;
                if (indexB !== -1) return 1;
                // For any other slots, sort alphabetically
                return a.id.localeCompare(b.id);
            });
        } else {
            // For other floors, sort alphabetically
            floorSlots.sort((a, b) => a.id.localeCompare(b.id));
        }

        const section = document.createElement('div');
        section.className = 'mb-8';
        section.id = 'floor-' + floor.replace(/\s+/g, '-');

        section.innerHTML = `
            <div class="flex items-center gap-3 mb-3">
                <span class="floor-label">${floor}</span>
                <span style="font-size:11px; color:var(--text-faint)">${floorSlots.length} space${floorSlots.length !== 1 ? 's' : ''}</span>
                <button class="floor-add-button" onclick="window.openSingleModalForFloor('${floor.replace(/'/g, "\\'")}')">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:4px;">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Add Slot
                </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="grid-${floor.replace(/\s+/g, '-')}">
                ${floorSlots.map(s => buildBayHTML(s)).join('')}
            </div>`;
        container.appendChild(section);
    });

    refreshStats();
    updateSidebarStats();
}

// ── Select mode ───────────────────────────────────────────
window.toggleSelectMode = function() {
    isSelectMode = !isSelectMode;
    selectedSlotIds.clear();

    const container      = document.getElementById('floors-container');
    const btn            = document.getElementById('btn-select-mode');
    const deleteBar      = document.getElementById('deleteBar');
    const selectAllWrapper = document.getElementById('select-all-wrapper');
    const selectAllCb    = document.getElementById('select-all-checkbox');

    if (container) container.classList.toggle('select-mode', isSelectMode);
    if (btn) btn.classList.toggle('active', isSelectMode);
    if (selectAllWrapper) selectAllWrapper.style.display = isSelectMode ? 'flex' : 'none';
    if (selectAllCb) { selectAllCb.checked = false; selectAllCb.indeterminate = false; }
    document.querySelectorAll('.slot-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('.parking-bay.selected').forEach(card => card.classList.remove('selected'));
    if (deleteBar) deleteBar.classList.remove('visible');

    updateDeleteBar();
};

window.toggleSelectAll = function(checkbox) {
    if (checkbox.checked) {
        slots.forEach(slot => {
            selectedSlotIds.add(slot.id);
            const card = document.getElementById('card-' + slot.id);
            const cb   = document.getElementById('cb-'   + slot.id);
            if (card) card.classList.add('selected');
            if (cb)   cb.checked = true;
        });
    } else {
        selectedSlotIds.clear();
        document.querySelectorAll('.parking-bay.selected').forEach(card => card.classList.remove('selected'));
        document.querySelectorAll('.slot-checkbox').forEach(cb => cb.checked = false);
    }
    updateDeleteBar();
};

window.toggleSlotSelect = function(e, slotId) {
    e.stopPropagation();
    if (!isSelectMode) return;

    const card = document.getElementById('card-' + slotId);
    const cb   = document.getElementById('cb-'   + slotId);

    if (selectedSlotIds.has(slotId)) {
        selectedSlotIds.delete(slotId);
        if (card) card.classList.remove('selected');
        if (cb)   cb.checked = false;
    } else {
        selectedSlotIds.add(slotId);
        if (card) card.classList.add('selected');
        if (cb)   cb.checked = true;
    }

    const selectAllCb = document.getElementById('select-all-checkbox');
    if (selectAllCb) {
        selectAllCb.checked       = selectedSlotIds.size === slots.length;
        selectAllCb.indeterminate = selectedSlotIds.size > 0 && selectedSlotIds.size < slots.length;
    }

    updateDeleteBar();
};

function updateDeleteBar() {
    const deleteBar = document.getElementById('deleteBar');
    const countEl   = document.getElementById('delete-bar-count');
    const count      = selectedSlotIds.size;
    if (countEl)   countEl.textContent = count + ' slot' + (count !== 1 ? 's' : '') + ' selected';
    if (deleteBar) deleteBar.classList.toggle('visible', isSelectMode && count > 0);
}

window.openDeleteModal = function() {
    if (selectedSlotIds.size === 0) return;

    const modal    = document.getElementById('deleteModal');
    const subtitle = document.getElementById('delete-modal-subtitle');
    const list     = document.getElementById('delete-slots-list');

    const selectedSlots  = slots.filter(s => selectedSlotIds.has(s.id));
    const affectedFloors = [...new Set(selectedSlots.map(s => s.floor))];
    const floorsToDelete = affectedFloors.filter(floor => {
        const floorSlots      = slots.filter(s => s.floor === floor);
        const selectedInFloor = floorSlots.filter(s => selectedSlotIds.has(s.id));
        return floorSlots.length === selectedInFloor.length;
    });

    subtitle.textContent = floorsToDelete.length > 0
        ? `This will also remove ${floorsToDelete.length} floor(s). Cannot be undone.`
        : 'This action cannot be undone.';

    list.innerHTML = selectedSlots.map(s => `
        <div style="display:flex; align-items:center; justify-content:space-between; padding:4px 0; border-bottom:1px solid rgba(239,68,68,0.1);">
            <span style="font-family:'Oxanium',monospace; font-size:12px; color:var(--text-primary);">${s.id}</span>
            <span style="font-size:11px; color:var(--text-muted);">${s.floor} · ${s.type}</span>
            <span class="status-pill ${s.occupied ? 'occupied' : 'vacant'}" style="font-size:9px; padding:2px 8px;">
                <span class="status-dot"></span>${s.occupied ? 'Occupied' : 'Vacant'}
            </span>
        </div>
    `).join('');

    if (modal) modal.classList.add('open');
};

window.closeDeleteModal = function() {
    const modal = document.getElementById('deleteModal');
    if (modal) modal.classList.remove('open');
};

document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) window.closeDeleteModal();
});

window.confirmDelete = function() {
    const toDelete    = new Set(selectedSlotIds);
    const deletedSlots = slots.filter(s => toDelete.has(s.id));

    slots = slots.filter(s => !toDelete.has(s.id));

    // Keep previousSlotMap in sync
    toDelete.forEach(id => previousSlotMap.delete(id));

    selectedSlotIds.clear();
    isSelectMode = false;

    const container        = document.getElementById('floors-container');
    const btn              = document.getElementById('btn-select-mode');
    const deleteBar        = document.getElementById('deleteBar');
    const selectAllWrapper = document.getElementById('select-all-wrapper');
    const selectAllCb      = document.getElementById('select-all-checkbox');

    if (container)        container.classList.remove('select-mode');
    if (btn)              btn.classList.remove('active');
    if (deleteBar)        deleteBar.classList.remove('visible');
    if (selectAllWrapper) selectAllWrapper.style.display = 'none';
    if (selectAllCb)      { selectAllCb.checked = false; selectAllCb.indeterminate = false; }

    window.closeDeleteModal();
    renderFloors();

    if (!window.isDemoMode) {
        syncSlotsToServer();
        deletedSlots.forEach(slot => logManagementAction('slot_deleted', slot.id, slot.floor, slot.type, 1));
    }
};

// ── Slot click: open info modal (or select in select mode) ─
window.toggleSlot = function(id) {
    if (isSelectMode) {
        window.toggleSlotSelect({ stopPropagation: () => {} }, id);
        return;
    }
    window.openSlotInfoModal(id);
};

// ── Slot info modal ───────────────────────────────────────
let slotInfoRefreshInterval = null;

window.openSlotInfoModal = function(id) {
    const modal = document.getElementById('slotInfoModal');
    modal.dataset.slotId = id;
    renderSlotInfoModal(id);
    modal.classList.add('open');

    slotInfoRefreshInterval = setInterval(() => {
        const currentId = document.getElementById('slotInfoModal')?.dataset.slotId;
        if (currentId) renderSlotInfoModal(currentId);
    }, 800);
};

function renderSlotInfoModal(id) {
    const slot = slots.find(s => s.id === id);
    if (!slot) return;

    document.getElementById('modal-slot-title').textContent  = 'Slot ' + slot.id;
    document.getElementById('modal-slot-floor').textContent  = slot.floor;

    const badge      = document.getElementById('modal-slot-badge');
    const statusText = document.getElementById('modal-slot-status-text');
    badge.className  = 'status-pill text-sm px-4 py-2 ' + (slot.occupied ? 'occupied' : 'vacant');
    statusText.textContent = slot.occupied ? 'Occupied' : 'Vacant';

    document.getElementById('modal-info-id').textContent     = slot.id;
    document.getElementById('modal-info-type').textContent   = slot.type;
    document.getElementById('modal-info-floor').textContent  = slot.floor;
    document.getElementById('modal-info-number').textContent = slot.number;
    document.getElementById('modal-info-time').textContent   = slot.time || 'No data yet';

    const distanceEl = document.getElementById('modal-info-distance');
    if (slot.distance !== undefined && slot.distance !== null) {
        distanceEl.textContent = parseFloat(slot.distance).toFixed(1);
    } else {
        distanceEl.textContent = slot.occupied ? '< 15' : '> 15';
    }
}

window.closeSlotInfoModal = function() {
    const modal = document.getElementById('slotInfoModal');
    if (modal) modal.classList.remove('open');
    if (slotInfoRefreshInterval) {
        clearInterval(slotInfoRefreshInterval);
        slotInfoRefreshInterval = null;
    }
};

document.getElementById('slotInfoModal')?.addEventListener('click', function(e) {
    if (e.target === this) window.closeSlotInfoModal();
});

// ── Stats ─────────────────────────────────────────────────
function refreshStats() {
    const total    = slots.length;
    const occupied = slots.filter(s => s.occupied).length;
    const vacant   = total - occupied;
    const pct      = total > 0 ? Math.round((occupied / total) * 100) : 0;

    const statVacant   = document.getElementById('stat-vacant');
    const statOccupied = document.getElementById('stat-occupied');
    const statPct      = document.getElementById('stat-pct');
    const barLabel     = document.getElementById('bar-label');
    const occBar       = document.getElementById('occ-bar');

    if (statVacant)   statVacant.textContent   = vacant;
    if (statOccupied) statOccupied.textContent = occupied;
    if (statPct)      statPct.textContent      = pct + '%';
    if (barLabel)     barLabel.textContent     = occupied + ' / ' + total + ' slots used';

    if (occBar) {
        occBar.style.width           = pct + '%';
        occBar.style.backgroundColor = pct >= 100 ? '#ef4444' : pct >= 67 ? '#f59e0b' : '#4ade80';
    }
}

// ── Sidebar stats ─────────────────────────────────────────
function updateSidebarStats() {
    const total     = slots.length;
    const occupied  = slots.filter(s => s.occupied).length;
    const available = total - occupied;

    const sidebarTotal     = document.getElementById('sidebar-total');
    const sidebarAvailable = document.getElementById('sidebar-available');
    const sidebarOccupied  = document.getElementById('sidebar-occupied');

    if (sidebarTotal)     sidebarTotal.textContent     = total;
    if (sidebarAvailable) sidebarAvailable.textContent = available;
    if (sidebarOccupied)  sidebarOccupied.textContent  = occupied;
}

// ── Bulk Add Modal ────────────────────────────────────────
window.openBulkModal = function() {
    const modal = document.getElementById('bulkModal');
    if (modal) modal.classList.add('open');
    document.getElementById('bulk-name')?.focus();
};

window.closeBulkModal = function() {
    const modal = document.getElementById('bulkModal');
    if (modal) modal.classList.remove('open');
    clearBulkForm();
};

document.getElementById('bulkModal')?.addEventListener('click', function(e) {
    if (e.target === this) window.closeBulkModal();
});

function clearBulkForm() {
    ['bulk-quantity', 'bulk-name', 'bulk-start-number'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.value = id === 'bulk-quantity' ? '1' : ''; el.classList.remove('error'); }
    });
    ['err-bulk-quantity', 'err-bulk-name', 'err-bulk-start-number'].forEach(id => {
        document.getElementById(id)?.classList.remove('show');
    });
    const floorSelect = document.getElementById('bulk-floor');
    if (floorSelect) floorSelect.selectedIndex = 0;
}

function validateBulk() {
    let valid = true;

    const quantityEl = document.getElementById('bulk-quantity');
    const quantity   = parseInt(quantityEl?.value);
    const errQuantity = document.getElementById('err-bulk-quantity');
    if (!quantityEl || isNaN(quantity) || quantity < 1 || quantity > 50) {
        quantityEl?.classList.add('error'); errQuantity?.classList.add('show'); valid = false;
    } else {
        quantityEl?.classList.remove('error'); errQuantity?.classList.remove('show');
    }

    const nameEl  = document.getElementById('bulk-name');
    const errName = document.getElementById('err-bulk-name');
    if (!nameEl?.value.trim()) {
        nameEl?.classList.add('error'); errName?.classList.add('show'); valid = false;
    } else {
        nameEl?.classList.remove('error'); errName?.classList.remove('show');
    }

    const startEl  = document.getElementById('bulk-start-number');
    const errStart = document.getElementById('err-bulk-start-number');
    if (!startEl?.value.trim()) {
        startEl?.classList.add('error'); errStart?.classList.add('show'); valid = false;
    } else {
        startEl?.classList.remove('error'); errStart?.classList.remove('show');
    }

    return valid;
}

window.addBulkSlotsHandler = function() {
    if (!validateBulk()) return;

    const quantity       = parseInt(document.getElementById('bulk-quantity').value);
    const namePrefix     = document.getElementById('bulk-name').value.trim().toUpperCase();
    const startNumberRaw = document.getElementById('bulk-start-number').value.trim();
    const floor          = document.getElementById('bulk-floor').value;
    const type           = document.getElementById('bulk-type').value;

    let startNum = parseInt(startNumberRaw);
    if (isNaN(startNum)) startNum = 1;

    const newSlots = [];
    for (let i = 0; i < quantity; i++) {
        const numFormatted = formatNumber(startNum + i, startNumberRaw);
        const slotId       = `${namePrefix}-${numFormatted}`;
        newSlots.push({ id: slotId, number: numFormatted, floor, type, occupied: false, time: 'just now' });
    }

    slots.push(...newSlots);
    // Add to map so next poll doesn't treat them as new
    newSlots.forEach(s => previousSlotMap.set(s.id, false));

    window.closeBulkModal();
    renderFloors();

    if (!window.isDemoMode) {
        syncSlotsToServer();
        newSlots.forEach(slot => logManagementAction('space_added', slot.id, slot.floor, slot.type, 1));
    }

    setTimeout(() => {
        const firstEl = document.getElementById('card-' + newSlots[0]?.id);
        if (firstEl) {
            firstEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            newSlots.forEach(slot => {
                const el = document.getElementById('card-' + slot.id);
                if (el) { el.classList.add('flash'); setTimeout(() => el.classList.remove('flash'), 500); }
            });
        }
    }, 100);
};

// ── Single Add Modal ──────────────────────────────────────
let pendingSingleFloor = null;

window.openSingleModalForFloor = function(floorName) {
    pendingSingleFloor = floorName;
    const modal     = document.getElementById('singleModal');
    const modalHint = document.getElementById('singleModalFloorHint');
    if (modal) modal.classList.add('open');
    if (floorName && modalHint) {
        modalHint.innerHTML = `Add a single parking slot to <strong style="color:#facc15">${floorName}</strong>`;
    } else if (modalHint) {
        modalHint.innerHTML = 'Add a single parking slot';
    }
    document.getElementById('single-name')?.focus();
};

window.closeSingleModal = function() {
    const modal = document.getElementById('singleModal');
    if (modal) modal.classList.remove('open');
    clearSingleForm();
    pendingSingleFloor = null;
};

document.getElementById('singleModal')?.addEventListener('click', function(e) {
    if (e.target === this) window.closeSingleModal();
});

function clearSingleForm() {
    ['single-name', 'single-number'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.value = ''; el.classList.remove('error'); }
    });
    ['err-single-name', 'err-single-number'].forEach(id => {
        document.getElementById(id)?.classList.remove('show');
    });
}

function validateSingle() {
    let valid = true;

    const nameEl  = document.getElementById('single-name');
    const errName = document.getElementById('err-single-name');
    if (!nameEl?.value.trim()) {
        nameEl?.classList.add('error'); errName?.classList.add('show'); valid = false;
    } else {
        nameEl?.classList.remove('error'); errName?.classList.remove('show');
    }

    const numEl  = document.getElementById('single-number');
    const errNum = document.getElementById('err-single-number');
    if (!numEl?.value.trim()) {
        numEl?.classList.add('error'); errNum?.classList.add('show'); valid = false;
    } else {
        numEl?.classList.remove('error'); errNum?.classList.remove('show');
    }

    return valid;
}

window.addSingleSlotHandler = function() {
    if (!validateSingle()) return;
    if (!pendingSingleFloor) { alert('Please select a floor first'); return; }

    const name   = document.getElementById('single-name').value.trim().toUpperCase();
    const number = document.getElementById('single-number').value.trim();
    const type   = document.getElementById('single-type').value;
    const floor  = pendingSingleFloor;
    const id     = `${name}-${number}`;

    slots.push({ id, number, floor, type, occupied: false, time: 'just now' });
    previousSlotMap.set(id, false); // keep map in sync

    window.closeSingleModal();
    renderFloors();

    if (!window.isDemoMode) {
        syncSlotsToServer();
        logManagementAction('slot_added', id, floor, type, 1);
    }

    setTimeout(() => {
        const el = document.getElementById('card-' + id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('flash');
            setTimeout(() => el.classList.remove('flash'), 500);
        }
    }, 100);
};

// ── Sidebar ───────────────────────────────────────────────
window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const isMobile = window.innerWidth <= 768;

    if (!sidebar) return;
    if (isMobile) {
        sidebar.classList.toggle('mobile-open');
        overlay?.classList.toggle('active');
    } else {
        sidebar.classList.toggle('collapsed');
    }
};

document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'b') { e.preventDefault(); window.toggleSidebar(); }
});

document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
    document.getElementById('sidebar')?.classList.remove('mobile-open');
    this.classList.remove('active');
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        document.getElementById('sidebar')?.classList.remove('mobile-open');
        document.getElementById('sidebarOverlay')?.classList.remove('active');
    }
});

document.addEventListener('click', function(e) {
    const sidebar  = document.getElementById('sidebar');
    const isMobile = window.innerWidth <= 768;
    if (isMobile && sidebar?.classList.contains('mobile-open')) {
        if (!sidebar.contains(e.target) && !e.target.closest('.mobile-menu-btn')) {
            sidebar.classList.remove('mobile-open');
            document.getElementById('sidebarOverlay')?.classList.remove('active');
        }
    }
});

// ── User Menu ─────────────────────────────────────────────
window.toggleUserMenu = function() {
    const menu = document.getElementById('userMenu');
    if (menu) { menu.classList.toggle('show'); menu.classList.toggle('hidden'); }
};

document.addEventListener('click', function(e) {
    const menu   = document.getElementById('userMenu');
    const button = e.target.closest('.user-dropdown-btn');
    if (menu && !button && !menu.contains(e.target)) {
        menu.classList.remove('show');
        menu.classList.add('hidden');
    }
});

// ── ULTRA-FAST Polling with Optimized Updates ──
let lastKnownHash = '';
let pollInterval = null;
let isPolling = false;
let consecutiveErrors = 0;
let lastUpdateTime = Date.now();

// Function to calculate hash quickly
function getQuickHash(data) {
    if (!data || data.length === 0) return '';
    // Only hash the occupied status and IDs for speed
    return data.map(s => `${s.id}:${s.occupied}`).join('|');
}

async function quickPoll() {
    if (isPolling) return; // Prevent overlapping polls
    isPolling = true;
    
    try {
        const startTime = Date.now();
        const response = await fetch('/api/slots?_=' + startTime, {
            headers: {
                'Cache-Control': 'no-cache'
            }
        });
        
        const endTime = Date.now();
        const responseTime = endTime - startTime;
        
        if (response.ok) {
            consecutiveErrors = 0;
            const data = await response.json();
            
            // Use faster hash calculation
            const currentHash = getQuickHash(data);
            
            // Only update if data has changed
            if (currentHash !== lastKnownHash) {
                const timeSinceLastUpdate = endTime - lastUpdateTime;
                console.log(`🔄 Changes detected in ${responseTime}ms (${timeSinceLastUpdate}ms since last update)`);
                lastKnownHash = currentHash;
                lastUpdateTime = endTime;
                
                if (data && data.length > 0) {
                    window.isDemoMode = false;
                    processServerSlots(data);
                }
            }
        } else {
            consecutiveErrors++;
            if (consecutiveErrors > 3) {
                console.warn('⚠️ Multiple polling errors');
            }
        }
    } catch (error) {
        consecutiveErrors++;
        if (consecutiveErrors === 1) {
            console.error('Poll error:', error);
        }
    } finally {
        isPolling = false;
    }
}

// Variable polling interval - faster when changes are happening
let currentPollInterval = 200; // Start at 200ms
let lastChangeTime = Date.now();
let noChangeCount = 0;

// Adaptive polling - speeds up when changes detected, slows down when idle
function adaptivePoll() {
    quickPoll();
    
    // Adjust polling rate based on activity
    const timeSinceLastChange = Date.now() - lastUpdateTime;
    
    if (timeSinceLastChange < 1000) {
        // Recent change - poll faster
        currentPollInterval = 150;
    } else if (timeSinceLastChange < 3000) {
        // Recent activity - medium speed
        currentPollInterval = 250;
    } else {
        // No changes - slower polling to save resources
        currentPollInterval = 500;
    }
    
    // Update the interval
    if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = setInterval(adaptivePoll, currentPollInterval);
    }
}

// ── Override processServerSlots to track last update time ──
const originalProcessServerSlots = processServerSlots;
window.processServerSlots = function(serverSlots) {
    lastUpdateTime = Date.now();
    return originalProcessServerSlots(serverSlots);
};

// ── Initial load with adaptive fast polling ──
document.addEventListener('DOMContentLoaded', async () => {
    console.log('🚀 Initializing Parking System with Adaptive Polling...');
    applyTheme(currentTheme);
    
    // Initial load
    const startTime = Date.now();
    const serverOk = await loadSlotsFromServer();
    const loadTime = Date.now() - startTime;
    
    updateSidebarStats();
    
    // Store initial hash using quick hash
    if (slots && slots.length > 0) {
        lastKnownHash = getQuickHash(slots);
        lastUpdateTime = Date.now();
    }
    
    if (serverOk) {
        console.log(`✅ Server connected (initial load: ${loadTime}ms)`);
        console.log('📡 Starting adaptive polling (150-500ms based on activity)');
        // Start adaptive polling
        pollInterval = setInterval(adaptivePoll, 200);
    } else {
        // Retry connection if server is not available
        console.log('Server not available, retrying...');
        const retryInterval = setInterval(async () => {
            const ok = await loadSlotsFromServer();
            if (ok) {
                clearInterval(retryInterval);
                if (slots && slots.length > 0) {
                    lastKnownHash = getQuickHash(slots);
                }
                pollInterval = setInterval(adaptivePoll, 200);
                console.log('✅ Server connected, adaptive polling started');
            }
        }, 3000);
    }
});

// Clean up on page unload
window.addEventListener('beforeunload', () => {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});

