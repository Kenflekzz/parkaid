<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Park Aid — History</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css', 'resources/js/app.js', 'resources/js/parking.js'])
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        .tab-btn {
            font-family: 'Oxanium', monospace;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn.active {
            background: rgba(250,204,21,0.12);
            border-color: rgba(250,204,21,0.4);
            color: #facc15;
        }
        .tab-btn:hover:not(.active) {
            border-color: var(--text-faint);
            color: var(--text-secondary);
        }

        .history-filter-btn {
            font-family: 'Oxanium', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }
        .history-filter-btn.active {
            background: rgba(59,130,246,0.12);
            border-color: rgba(59,130,246,0.4);
            color: #60a5fa;
        }

        .history-clear-btn {
            font-family: 'Oxanium', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(239,68,68,0.3);
            background: rgba(239,68,68,0.06);
            color: #ef4444;
            cursor: pointer;
            transition: all 0.2s;
        }
        .history-clear-btn:hover {
            background: rgba(239,68,68,0.12);
            border-color: rgba(239,68,68,0.5);
        }

        .history-entry {
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .history-entry:hover {
            transform: translateX(3px);
        }

        .mgmt-filter-btn {
            font-family: 'Oxanium', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }
        .mgmt-filter-btn.active {
            background: rgba(34,197,94,0.1);
            border-color: rgba(34,197,94,0.4);
            color: #4ade80;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-faint);
        }
        .empty-state svg {
            margin: 0 auto 16px;
            opacity: 0.3;
            display: block;
        }
        .empty-state p.title {
            font-family: 'Oxanium', monospace;
            font-size: 14px;
            color: var(--text-faint);
        }
        .empty-state p.sub {
            font-size: 12px;
            margin-top: 4px;
            color: var(--text-faintest, #1e293b);
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-family: 'Oxanium', monospace;
            color: var(--text-muted);
        }
        .user-badge .avatar {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(250,204,21,0.15);
            color: #facc15;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 700;
        }

        /* ── Pagination ── */
        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 10px;
        }
        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .pg-btn {
            font-family: 'Oxanium', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }
        .pg-btn:hover:not(:disabled) {
            border-color: rgba(250,204,21,0.4);
            color: #facc15;
            background: rgba(250,204,21,0.06);
        }
        .pg-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        .pg-btn.active {
            background: rgba(250,204,21,0.12);
            border-color: rgba(250,204,21,0.4);
            color: #facc15;
        }
        .pg-info {
            font-family: 'Oxanium', monospace;
            font-size: 11px;
            color: var(--text-faint);
            letter-spacing: 0.05em;
        }
        .pg-size-select {
            font-family: 'Oxanium', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-muted);
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="top-bar"></div>

<div class="dashboard-layout">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo" onclick="toggleSidebar()" style="cursor: pointer;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17M5 17H3V15H5M19 17H21V15H19M5 17H19M7 14.5C7 15.3 6.33 16 5.5 16S4 15.3 4 14.5 4.67 13 5.5 13 7 13.7 7 14.5ZM20 14.5C20 15.3 19.33 16 18.5 16S17 15.3 17 14.5 17.67 13 18.5 13 20 13.7 20 14.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="mono font-bold">PARKAID</span>
            </div>
            <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M3 9L12 3L21 9L12 15L3 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 21V12L12 10L15 12V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 13V21H19V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('parking.floors') }}" class="nav-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 10H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M16 10H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Parking Floors</span>
            </a>
            <a href="{{ route('history') }}" class="nav-item active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 6V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                </svg>
                <span>History</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-stats">
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Total Slots</span>
                    <span class="sidebar-stat-value" id="sidebar-total">4</span>
                </div>
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Available</span>
                    <span class="sidebar-stat-value text-green-500" id="sidebar-available">4</span>
                </div>
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Occupied</span>
                    <span class="sidebar-stat-value text-red-500" id="sidebar-occupied">0</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="max-w-6xl mx-auto px-5 py-8">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-8 fade-1">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-yellow-400">
                            <path d="M12 6V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span class="mono text-xs tracking-widest uppercase text-muted">Parking Activity</span>
                    </div>
                    <h1 class="mono text-4xl font-bold tracking-wide">
                        <span class="text-yellow-400">PARKING</span><span class="text-primary">HISTORY</span>
                    </h1>
                    <p class="mono text-xs mt-1.5 text-faint">All parking activity and management logs</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-green-500/10 border border-green-500/20 rounded-full px-3 py-1.5">
                        <span class="live-pulse w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                        <span class="mono text-xs tracking-widest text-green-400 uppercase">Live</span>
                    </div>
                    <div class="theme-switch-wrapper">
                        <label class="theme-switch" onclick="toggleTheme()">
                            <div class="theme-switch-slider">
                                <div class="theme-switch-icons">
                                    <span class="theme-switch-sun">☀️</span>
                                    <span class="theme-switch-moon">🌙</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="user-dropdown">
                        <button onclick="toggleUserMenu()" class="user-dropdown-btn">
                            <div class="user-avatar">
                                <span class="user-initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                            </div>
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">Administrator</span>
                            </div>
                            <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                        <div id="userMenu" class="user-dropdown-menu hidden">
                            <div class="dropdown-header">
                                <div class="dropdown-avatar">
                                    <span class="dropdown-initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                </div>
                                <div class="dropdown-info">
                                    <p class="dropdown-name">{{ Auth::user()->name }}</p>
                                    <p class="dropdown-email">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21V19C20 16.8 18.2 15 16 15H8C5.8 15 4 16.8 4 19V21" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Profile Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-logout">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2"/>
                                        <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2"/>
                                        <path d="M21 12H9" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="road-divider mb-6 fade-2"></div>

            {{-- ── Tab Switcher ── --}}
            <div class="flex items-center gap-3 mb-6 fade-2">
                <button class="tab-btn active" id="tab-sensor" onclick="switchTab('sensor')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:5px;">
                        <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Sensor Activity
                </button>
                <button class="tab-btn" id="tab-mgmt" onclick="switchTab('mgmt')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:5px;">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Management Activity
                </button>
            </div>

            {{-- ── SENSOR HISTORY PANEL ── --}}
            <div id="panel-sensor">
                <div class="flex items-center justify-between mb-4 fade-3">
                    <div class="flex items-center gap-2">
                        <button onclick="filterHistory('all')" class="history-filter-btn active" id="filter-all">All</button>
                        <button onclick="filterHistory('occupied')" class="history-filter-btn" id="filter-occupied">Occupied</button>
                        <button onclick="filterHistory('vacant')" class="history-filter-btn" id="filter-vacant">Vacant</button>
                    </div>
                    <button onclick="clearHistory()" class="history-clear-btn">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:4px;">
                            <path d="M3 6H5H21M8 6V4A2 2 0 0110 2H14A2 2 0 0116 4V6M19 6V20A2 2 0 0117 22H7A2 2 0 015 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Clear Sensor History
                    </button>
                </div>
                <div id="history-container" class="fade-3">
                    <div class="empty-state" id="history-empty" style="display:none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                            <path d="M12 6V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <p class="title">No sensor history yet</p>
                        <p class="sub">Parking activity will appear here when sensors detect changes</p>
                    </div>
                    <div id="history-list"></div>
                </div>
                {{-- Sensor Pagination --}}
                <div class="pagination-bar" id="sensor-pagination" style="display:none;">
                    <span class="pg-info" id="sensor-pg-info"></span>
                    <div class="pagination-controls" id="sensor-pg-controls"></div>
                    <select class="pg-size-select" id="sensor-pg-size" onchange="setSensorPageSize(this.value)">
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                    </select>
                </div>
            </div>

            {{-- ── MANAGEMENT HISTORY PANEL ── --}}
            <div id="panel-mgmt" style="display:none;">
                <div class="flex items-center justify-between mb-4 fade-3">
                    <div class="flex items-center gap-2">
                        <button onclick="filterMgmt('all')" class="mgmt-filter-btn active" id="mgmt-filter-all">All</button>
                        <button onclick="filterMgmt('slot_added')" class="mgmt-filter-btn" id="mgmt-filter-slot_added">Added</button>
                        <button onclick="filterMgmt('slot_deleted')" class="mgmt-filter-btn" id="mgmt-filter-slot_deleted">Deleted</button>
                        <button onclick="filterMgmt('space_added')" class="mgmt-filter-btn" id="mgmt-filter-space_added">Space Added</button>
                    </div>
                    <button onclick="clearMgmtHistory()" class="history-clear-btn">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:4px;">
                            <path d="M3 6H5H21M8 6V4A2 2 0 0110 2H14A2 2 0 0116 4V6M19 6V20A2 2 0 0117 22H7A2 2 0 015 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Clear Management History
                    </button>
                </div>
                <div id="mgmt-container" class="fade-3">
                    <div class="empty-state" id="mgmt-empty" style="display:none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <p class="title">No management activity yet</p>
                        <p class="sub">Adding or deleting slots and spaces will be logged here</p>
                    </div>
                    <div id="mgmt-list"></div>
                </div>
                {{-- Management Pagination --}}
                <div class="pagination-bar" id="mgmt-pagination" style="display:none;">
                    <span class="pg-info" id="mgmt-pg-info"></span>
                    <div class="pagination-controls" id="mgmt-pg-controls"></div>
                    <select class="pg-size-select" id="mgmt-pg-size" onchange="setMgmtPageSize(this.value)">
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                    </select>
                </div>
            </div>

        </div>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
// ── State ─────────────────────────────────────────────────
let allHistory        = [];
let allMgmtLogs       = [];
let currentFilter     = 'all';
let currentMgmtFilter = 'all';
let currentTab        = 'sensor';

// ── Pagination state ──────────────────────────────────────
let sensorPage    = 1;
let sensorPerPage = 10;
let mgmtPage      = 1;
let mgmtPerPage   = 10;

// ── Helper: safe value ────────────────────────────────────
function safeValue(value, defaultValue = '—') {
    return (value !== null && value !== undefined && value !== '') ? value : defaultValue;
}

// ── Helper: escape HTML ───────────────────────────────────
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

// ── Tab switcher ──────────────────────────────────────────
function switchTab(tab) {
    currentTab = tab;
    document.getElementById('panel-sensor').style.display = tab === 'sensor' ? 'block' : 'none';
    document.getElementById('panel-mgmt').style.display   = tab === 'mgmt'   ? 'block' : 'none';
    document.getElementById('tab-sensor').classList.toggle('active', tab === 'sensor');
    document.getElementById('tab-mgmt').classList.toggle('active', tab === 'mgmt');
}

// ── Load sensor history ───────────────────────────────────
async function loadHistory() {
    try {
        const response = await fetch('/api/history', { credentials: 'same-origin' });
        if (response.ok) {
            allHistory = await response.json();
            renderHistory(filterByStatus(allHistory, currentFilter));
        }
    } catch (e) {
        console.log('Failed to load sensor history');
    }
}

// ── Load management logs ──────────────────────────────────
async function loadMgmtLogs() {
    try {
        const response = await fetch('/api/management-logs', { credentials: 'same-origin' });
        if (response.ok) {
            allMgmtLogs = await response.json();
            renderMgmtLogs(filterByAction(allMgmtLogs, currentMgmtFilter));
        }
    } catch (e) {
        console.log('Failed to load management logs');
    }
}

// ── Filter helpers ────────────────────────────────────────
function filterByStatus(data, filter) {
    return filter === 'all' ? data : data.filter(e => e.status === filter);
}

function filterByAction(data, filter) {
    return filter === 'all' ? data : data.filter(e => e.action === filter);
}

// ── Sensor filter buttons ─────────────────────────────────
function filterHistory(filter) {
    currentFilter = filter;
    sensorPage = 1;
    document.querySelectorAll('.history-filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('filter-' + filter).classList.add('active');
    renderHistory(filterByStatus(allHistory, filter));
}

// ── Management filter buttons ─────────────────────────────
function filterMgmt(filter) {
    currentMgmtFilter = filter;
    mgmtPage = 1;
    document.querySelectorAll('.mgmt-filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('mgmt-filter-' + filter).classList.add('active');
    renderMgmtLogs(filterByAction(allMgmtLogs, filter));
}

// ── Page size setters ─────────────────────────────────────
function setSensorPageSize(val) {
    sensorPerPage = parseInt(val);
    sensorPage = 1;
    renderHistory(filterByStatus(allHistory, currentFilter));
}

function setMgmtPageSize(val) {
    mgmtPerPage = parseInt(val);
    mgmtPage = 1;
    renderMgmtLogs(filterByAction(allMgmtLogs, currentMgmtFilter));
}

// ── Core pagination helper ────────────────────────────────
function paginate(data, page, perPage) {
    const total      = data.length;
    const totalPages = Math.max(1, Math.ceil(total / perPage));
    const safePage   = Math.min(Math.max(1, page), totalPages);
    const start      = (safePage - 1) * perPage;
    const end        = Math.min(start + perPage, total);
    return { items: data.slice(start, end), page: safePage, totalPages, total, start, end };
}

// ── Build pagination controls ─────────────────────────────
function buildPaginationControls(panelId, controlsId, infoId, page, totalPages, total, start, end, gotoFn) {
    const bar      = document.getElementById(panelId);
    const controls = document.getElementById(controlsId);
    const info     = document.getElementById(infoId);

    if (total === 0) {
        if (bar) bar.style.display = 'none';
        return;
    }
    if (bar) bar.style.display = 'flex';
    if (info) info.textContent = `Showing ${start + 1}–${end} of ${total} entries`;

    if (!controls) return;

    const maxButtons = 5;
    let startPage = Math.max(1, page - Math.floor(maxButtons / 2));
    let endPage   = Math.min(totalPages, startPage + maxButtons - 1);
    if (endPage - startPage < maxButtons - 1) startPage = Math.max(1, endPage - maxButtons + 1);

    let html = `<button class="pg-btn" onclick="${gotoFn}(${page - 1})" ${page === 1 ? 'disabled' : ''}>← Prev</button>`;

    if (startPage > 1) html += `<button class="pg-btn" onclick="${gotoFn}(1)">1</button>`;
    if (startPage > 2) html += `<span class="pg-info" style="padding:0 4px;">…</span>`;

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="pg-btn ${i === page ? 'active' : ''}" onclick="${gotoFn}(${i})">${i}</button>`;
    }

    if (endPage < totalPages - 1) html += `<span class="pg-info" style="padding:0 4px;">…</span>`;
    if (endPage < totalPages)     html += `<button class="pg-btn" onclick="${gotoFn}(${totalPages})">${totalPages}</button>`;

    html += `<button class="pg-btn" onclick="${gotoFn}(${page + 1})" ${page === totalPages ? 'disabled' : ''}>Next →</button>`;

    controls.innerHTML = html;
}

// ── Page navigation (global so inline onclick can reach them) ──
window.goToSensorPage = function(p) {
    sensorPage = p;
    renderHistory(filterByStatus(allHistory, currentFilter));
};

window.goToMgmtPage = function(p) {
    mgmtPage = p;
    renderMgmtLogs(filterByAction(allMgmtLogs, currentMgmtFilter));
};

// ── User badge helper ─────────────────────────────────────
function userBadge(user) {
    if (!user || !user.name) {
        return '<span class="user-badge"><span class="avatar">🤖</span><span>System</span></span>';
    }
    const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    return `<span class="user-badge"><span class="avatar">${escapeHtml(initials)}</span><span>${escapeHtml(user.name)}</span></span>`;
}

// ── Render sensor history ─────────────────────────────────
function renderHistory(data) {
    const list  = document.getElementById('history-list');
    const empty = document.getElementById('history-empty');

    if (!data || data.length === 0) {
        list.innerHTML = '';
        empty.style.display = 'block';
        document.getElementById('sensor-pagination').style.display = 'none';
        return;
    }
    empty.style.display = 'none';

    const { items, page, totalPages, total, start, end } = paginate(data, sensorPage, sensorPerPage);
    sensorPage = page;

    const grouped = {};
    items.forEach(entry => {
        const date = entry.date || 'Unknown Date';
        if (!grouped[date]) grouped[date] = [];
        grouped[date].push(entry);
    });

    list.innerHTML = Object.entries(grouped).map(([date, entries]) => `
        <div style="margin-bottom: 1.5rem;">
            <div style="font-family:'Oxanium',monospace; font-size:10px; letter-spacing:0.15em; text-transform:uppercase; color:var(--text-faint); margin-bottom:8px; padding-left:4px;">${escapeHtml(date)}</div>
            ${entries.map(entry => `
                <div class="history-entry" style="
                    display:flex; align-items:center; gap:12px;
                    background:var(--bg-card); border:1px solid var(--border);
                    border-left:3px solid ${entry.status === 'occupied' ? '#ef4444' : '#22c55e'};
                    border-radius:10px; padding:12px 16px; margin-bottom:6px; transition:all 0.2s;
                ">
                    <div style="
                        width:36px; height:36px; border-radius:10px; flex-shrink:0;
                        background:${entry.status === 'occupied' ? 'rgba(239,68,68,0.1)' : 'rgba(34,197,94,0.1)'};
                        display:flex; align-items:center; justify-content:center;
                    ">
                        ${entry.status === 'occupied'
                            ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/></svg>`
                            : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M5 13L9 17L19 7" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`
                        }
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                            <span style="font-family:'Oxanium',monospace; font-size:13px; font-weight:600; color:var(--text-primary);">Slot ${escapeHtml(entry.slot_id)}</span>
                            <span class="status-pill ${entry.status}" style="font-size:9px; padding:2px 8px;">
                                <span class="status-dot"></span>
                                ${entry.status === 'occupied' ? 'Occupied' : 'Vacant'}
                            </span>
                        </div>
                        <div style="font-size:11px; color:var(--text-muted);">
                            ${safeValue(entry.floor, 'Unknown Floor')} · ${safeValue(entry.type, 'Unknown Type')}
                            ${entry.distance ? `· ${parseFloat(entry.distance).toFixed(1)}cm` : ''}
                            · ${userBadge(entry.user)}
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="font-family:'Oxanium',monospace; font-size:12px; font-weight:600; color:var(--text-primary);">${escapeHtml(entry.time)}</div>
                        <div style="font-size:10px; color:var(--text-faint); margin-top:2px;">${escapeHtml(entry.date)}</div>
                    </div>
                </div>
            `).join('')}
        </div>
    `).join('');

    buildPaginationControls('sensor-pagination', 'sensor-pg-controls', 'sensor-pg-info', page, totalPages, total, start, end, 'goToSensorPage');
}

// ── Render management logs ────────────────────────────────
function renderMgmtLogs(data) {
    const list  = document.getElementById('mgmt-list');
    const empty = document.getElementById('mgmt-empty');

    if (!data || data.length === 0) {
        list.innerHTML = '';
        empty.style.display = 'block';
        document.getElementById('mgmt-pagination').style.display = 'none';
        return;
    }
    empty.style.display = 'none';

    const { items, page, totalPages, total, start, end } = paginate(data, mgmtPage, mgmtPerPage);
    mgmtPage = page;

    const grouped = {};
    items.forEach(entry => {
        const date = entry.date || 'Unknown Date';
        if (!grouped[date]) grouped[date] = [];
        grouped[date].push(entry);
    });

    const actionColor = {
        'slot_added':    '#22c55e',
        'slot_deleted':  '#ef4444',
        'space_added':   '#3b82f6',
        'space_deleted': '#f59e0b',
    };

    const actionIcon = {
        'slot_added':    `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 5V19M5 12H19" stroke="#22c55e" stroke-width="2" stroke-linecap="round"/></svg>`,
        'slot_deleted':  `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 6H5H21M8 6V4A2 2 0 0110 2H14A2 2 0 0116 4V6M19 6V20A2 2 0 0117 22H7A2 2 0 015 20V6H19Z" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/></svg>`,
        'space_added':   `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" stroke="#3b82f6" stroke-width="2"/><path d="M12 5V19M5 12H19" stroke="#3b82f6" stroke-width="1.5" stroke-linecap="round"/></svg>`,
        'space_deleted': `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" stroke="#f59e0b" stroke-width="2"/><path d="M5 12H19" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round"/></svg>`,
    };

    list.innerHTML = Object.entries(grouped).map(([date, entries]) => `
        <div style="margin-bottom: 1.5rem;">
            <div style="font-family:'Oxanium',monospace; font-size:10px; letter-spacing:0.15em; text-transform:uppercase; color:var(--text-faint); margin-bottom:8px; padding-left:4px;">${escapeHtml(date)}</div>
            ${entries.map(entry => `
                <div class="history-entry" style="
                    display:flex; align-items:center; gap:12px;
                    background:var(--bg-card); border:1px solid var(--border);
                    border-left:3px solid ${actionColor[entry.action] || '#64748b'};
                    border-radius:10px; padding:12px 16px; margin-bottom:6px; transition:all 0.2s;
                ">
                    <div style="
                        width:36px; height:36px; border-radius:10px; flex-shrink:0;
                        background:${actionColor[entry.action] || '#64748b'}18;
                        display:flex; align-items:center; justify-content:center;
                    ">
                        ${actionIcon[entry.action] || ''}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-family:'Oxanium',monospace; font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:2px;">
                            ${escapeHtml(entry.action_label) || 'Action'}
                        </div>
                        <div style="font-size:11px; color:var(--text-muted);">
                            ${entry.slot_id ? `Slot <strong style="color:var(--text-primary)">${escapeHtml(entry.slot_id)}</strong>` : ''}
                            ${entry.floor    ? `· ${escapeHtml(entry.floor)}`    : ''}
                            ${entry.type     ? `· ${escapeHtml(entry.type)}`     : ''}
                            ${entry.quantity && entry.quantity > 1 ? `· ${entry.quantity} slots` : ''}
                            · ${userBadge(entry.user)}
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="font-family:'Oxanium',monospace; font-size:12px; font-weight:600; color:var(--text-primary);">${escapeHtml(entry.time)}</div>
                        <div style="font-size:10px; color:var(--text-faint); margin-top:2px;">${escapeHtml(entry.date)}</div>
                    </div>
                </div>
            `).join('')}
        </div>
    `).join('');

    buildPaginationControls('mgmt-pagination', 'mgmt-pg-controls', 'mgmt-pg-info', page, totalPages, total, start, end, 'goToMgmtPage');
}

// ── Clear history ─────────────────────────────────────────
async function clearHistory() {
    if (!confirm('Clear all sensor history? This cannot be undone.')) return;
    try {
        await fetch('/api/history', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') }
        });
        allHistory = [];
        sensorPage = 1;
        renderHistory([]);
    } catch (e) {
        console.log('Failed to clear sensor history');
    }
}

async function clearMgmtHistory() {
    if (!confirm('Clear all management history? This cannot be undone.')) return;
    try {
        await fetch('/api/management-logs', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') }
        });
        allMgmtLogs = [];
        mgmtPage = 1;
        renderMgmtLogs([]);
    } catch (e) {
        console.log('Failed to clear management history');
    }
}

// ── Init ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
    loadMgmtLogs();

    // Poll both every 5 seconds — preserves current page
    setInterval(() => {
        loadHistory();
        loadMgmtLogs();
    }, 5000);
});
</script>

</body>
</html>