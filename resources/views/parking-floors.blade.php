<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Park Aid — Parking Floors</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css', 'resources/js/app.js', 'resources/js/parking.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* ── Responsive Header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 2rem;
            gap: 12px;
            flex-wrap: wrap;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: clamp(1.75rem, 5vw, 2.25rem);
        }

        /* ── Action bar (Add + Select buttons) ── */
        .action-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        /* ── Mobile burger ── */
        .mobile-menu-btn {
            display: none;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 7px;
            cursor: pointer;
            color: var(--text-muted);
            align-items: center;
            justify-content: center;
        }

        /* ── Floor grid responsive ── */
        .floor-slots-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        /* ── Tablet: 1024px ── */
        @media (max-width: 1024px) {
            .floor-slots-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ── Tablet: 768px ── */
        @media (max-width: 768px) {
            .mobile-menu-btn { display: flex; }

            .floor-slots-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .header-actions .user-info { display: none; }
            .header-actions .dropdown-arrow { display: none; }
            .user-dropdown-btn { padding: 6px !important; }
            .live-badge-text { display: none; }

            .btn-add-space-inline,
            .btn-select-mode {
                padding: 8px 14px !important;
                font-size: 11px !important;
            }

            /* Select All label compact */
            #select-all-wrapper {
                padding: 8px 12px !important;
                font-size: 10px !important;
            }
        }

        /* ── Small mobile: 480px ── */
        @media (max-width: 480px) {
            .page-title { font-size: 1.5rem !important; }

            .floor-slots-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.6rem;
            }

            .action-bar {
                gap: 8px;
            }

            .btn-add-space-inline {
                flex: 1;
                justify-content: center;
                min-width: 0;
                padding: 8px 12px !important;
                font-size: 10px !important;
            }

            .btn-select-mode {
                padding: 8px 12px !important;
                font-size: 10px !important;
            }

            /* Parking bay compact */
            .parking-bay .p-3 {
                padding: 0.5rem !important;
            }

            .car-wrap {
                height: 80px !important;
            }

            .car-wrap svg {
                width: 52px !important;
                height: 52px !important;
            }

            .p-marker {
                font-size: 28px !important;
            }

            .slot-num {
                font-size: 9px !important;
            }

            .floor-badge {
                font-size: 8px !important;
                padding: 1px 4px !important;
            }

            .status-pill {
                font-size: 9px !important;
                padding: 2px 7px !important;
            }

            /* Modal responsive */
            .modal-box {
                padding: 1.25rem !important;
                margin: 0.75rem !important;
                border-radius: 16px !important;
            }

            /* Delete bar responsive */
            .delete-bar {
                width: calc(100% - 2rem) !important;
                left: 1rem !important;
                transform: none !important;
                border-radius: 14px !important;
                padding: 8px 14px !important;
                gap: 10px !important;
                font-size: 11px !important;
            }

            .delete-bar-btn {
                padding: 6px 12px !important;
                font-size: 10px !important;
            }
        }

        /* ── Very small: 360px ── */
        @media (max-width: 360px) {
            .page-title { font-size: 1.3rem !important; }
            .floor-slots-grid { gap: 0.5rem; }
            .header-actions { gap: 6px; }

            .btn-add-space-inline,
            .btn-select-mode {
                padding: 7px 10px !important;
                font-size: 9px !important;
            }
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
            <a href="{{ route('parking.floors') }}" class="nav-item active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 21H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 17V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M6 10H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M16 10H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Parking Floors</span>
            </a>
            <a href="{{ route('history') }}" class="nav-item">
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
                    <span class="sidebar-stat-value" id="sidebar-total">3</span>
                </div>
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Available</span>
                    <span class="sidebar-stat-value text-green-500" id="sidebar-available">2</span>
                </div>
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Occupied</span>
                    <span class="sidebar-stat-value text-red-500" id="sidebar-occupied">1</span>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="max-w-6xl mx-auto px-5 py-8">

            {{-- Header --}}
            <div class="page-header fade-1">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-yellow-400">
                            <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17M5 17H3V15H5M19 17H21V15H19M5 17H19M7 14.5C7 15.3 6.33 16 5.5 16S4 15.3 4 14.5 4.67 13 5.5 13 7 13.7 7 14.5ZM20 14.5C20 15.3 19.33 16 18.5 16S17 15.3 17 14.5 17.67 13 18.5 13 20 13.7 20 14.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="mono text-xs tracking-widest uppercase text-muted">Parking Management</span>
                    </div>
                    <h1 class="mono font-bold tracking-wide page-title">
                        <span class="text-yellow-400">PARKING</span><span class="text-primary">FLOORS</span>
                    </h1>
                    <p class="mono text-xs mt-1 text-faint">Manage all parking floors and slots</p>
                </div>

                <div class="header-actions">
                    {{-- Mobile sidebar toggle --}}
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>

                    {{-- Live Badge --}}
                    <div class="flex items-center gap-2 bg-green-500/10 border border-green-500/20 rounded-full px-3 py-1.5">
                        <span class="live-pulse w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                        <span class="mono text-xs tracking-widest text-green-400 uppercase live-badge-text">Live</span>
                    </div>

                    {{-- Theme Toggle --}}
                    <div class="theme-switch-wrapper" style="margin-top:0;">
                        <label class="theme-switch" onclick="toggleTheme()">
                            <div class="theme-switch-slider">
                                <div class="theme-switch-icons">
                                    <span class="theme-switch-sun">☀️</span>
                                    <span class="theme-switch-moon">🌙</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- User Dropdown --}}
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

            {{-- Action Bar --}}
            <div class="action-bar">
                <button onclick="window.openBulkModal && window.openBulkModal()" class="btn-add-space-inline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    Add New Parking Space
                </button>
                <button onclick="window.toggleSelectMode()" class="btn-select-mode" id="btn-select-mode">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M9 11L12 14L22 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12V19A2 2 0 0119 21H5A2 2 0 013 19V5A2 2 0 015 3H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Select
                </button>

                {{-- Select All — only visible in select mode --}}
                <label id="select-all-wrapper" style="
                    display: none;
                    align-items: center;
                    gap: 6px;
                    cursor: pointer;
                    font-family: 'Oxanium', monospace;
                    font-size: 11px;
                    font-weight: 600;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    color: var(--text-muted);
                    padding: 10px 16px;
                    background: var(--bg-card);
                    border: 1px solid var(--border);
                    border-radius: 999px;
                    transition: all 0.2s;
                    user-select: none;
                ">
                    <input
                        type="checkbox"
                        id="select-all-checkbox"
                        onclick="window.toggleSelectAll(this)"
                        style="accent-color: #ef4444; width: 14px; height: 14px; cursor: pointer;"
                    />
                    Select All
                </label>
            </div>

            <div class="road-divider mb-6 fade-2"></div>

            {{-- Floors Container --}}
            <div id="floors-container"></div>

        </div>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

{{-- Bulk Add Modal --}}
<div class="modal-backdrop" id="bulkModal">
    <div class="modal-box">
        <div class="flex items-start justify-between mb-1">
            <div>
                <p class="modal-title">Add Multiple Parking Spaces</p>
                <p class="modal-subtitle">Create multiple parking slots at once</p>
            </div>
            <button onclick="window.closeBulkModal && window.closeBulkModal()" class="modal-close-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <div>
            <label class="field-label">Number of Slots to Add</label>
            <input class="field-input" id="bulk-quantity" type="number" min="1" max="50" value="1">
            <p class="field-error" id="err-bulk-quantity">Enter a number between 1 and 50</p>

            <label class="field-label">Slot Name Prefix</label>
            <input class="field-input" id="bulk-name" type="text" placeholder="e.g., D">
            <p class="field-error" id="err-bulk-name">Prefix required</p>

            <label class="field-label">Starting Slot Number</label>
            <input class="field-input" id="bulk-start-number" type="text" placeholder="e.g., 01">
            <p class="field-error" id="err-bulk-start-number">Starting number required</p>

            <label class="field-label">Floor</label>
            <select class="field-input" id="bulk-floor">
                <option value="Ground Floor">Ground Floor</option>
                <option value="Floor 1">Floor 1</option>
                <option value="Floor 2">Floor 2</option>
                <option value="Floor 3">Floor 3</option>
                <option value="Basement 1">Basement 1</option>
                <option value="Basement 2">Basement 2</option>
            </select>

            <label class="field-label">Slot Type</label>
            <select class="field-input" id="bulk-type">
                <option value="Regular">Regular</option>
                <option value="Compact">Compact</option>
                <option value="PWD">PWD</option>
                <option value="VIP">VIP</option>
                <option value="EV Charging">EV Charging</option>
            </select>

            <button class="btn-primary" onclick="window.addBulkSlotsHandler && window.addBulkSlotsHandler()">+ Add Parking Spaces</button>
            <button class="btn-cancel" onclick="window.closeBulkModal && window.closeBulkModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- Single Add Modal --}}
<div class="modal-backdrop" id="singleModal">
    <div class="modal-box">
        <div class="flex items-start justify-between mb-1">
            <div>
                <p class="modal-title">Add Parking Slot</p>
                <p class="modal-subtitle" id="singleModalFloorHint">Add a single parking slot</p>
            </div>
            <button onclick="window.closeSingleModal && window.closeSingleModal()" class="modal-close-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <div>
            <label class="field-label">Slot Name</label>
            <input class="field-input" id="single-name" type="text" placeholder="e.g., D">
            <p class="field-error" id="err-single-name">Slot name required</p>

            <label class="field-label">Slot Number</label>
            <input class="field-input" id="single-number" type="text" placeholder="e.g., 01">
            <p class="field-error" id="err-single-number">Slot number required</p>

            <label class="field-label">Slot Type</label>
            <select class="field-input" id="single-type">
                <option value="Regular">Regular</option>
                <option value="Compact">Compact</option>
                <option value="PWD">PWD</option>
                <option value="VIP">VIP</option>
                <option value="EV Charging">EV Charging</option>
            </select>

            <button class="btn-primary" onclick="window.addSingleSlotHandler && window.addSingleSlotHandler()">+ Add Parking Slot</button>
            <button class="btn-cancel" onclick="window.closeSingleModal && window.closeSingleModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- Slot Info Modal --}}
<div class="modal-backdrop" id="slotInfoModal">
    <div class="modal-box">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="modal-title" id="modal-slot-title">Slot A-01</p>
                <p class="modal-subtitle" id="modal-slot-floor">Ground Floor</p>
            </div>
            <button onclick="window.closeSlotInfoModal()" class="modal-close-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div class="flex items-center justify-center mb-5">
            <span class="status-pill text-sm px-4 py-2" id="modal-slot-badge">
                <span class="status-dot"></span>
                <span id="modal-slot-status-text">Vacant</span>
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="stat-card" style="padding: 12px;">
                <p class="text-xs tracking-widest uppercase text-muted mb-1">Slot ID</p>
                <p class="mono font-bold text-primary" id="modal-info-id">A-01</p>
            </div>
            <div class="stat-card" style="padding: 12px;">
                <p class="text-xs tracking-widest uppercase text-muted mb-1">Type</p>
                <p class="mono font-bold text-primary" id="modal-info-type">Regular</p>
            </div>
            <div class="stat-card" style="padding: 12px;">
                <p class="text-xs tracking-widest uppercase text-muted mb-1">Floor</p>
                <p class="mono font-bold text-primary" id="modal-info-floor">Ground Floor</p>
            </div>
            <div class="stat-card" style="padding: 12px;">
                <p class="text-xs tracking-widest uppercase text-muted mb-1">Slot Number</p>
                <p class="mono font-bold text-primary" id="modal-info-number">01</p>
            </div>
        </div>

        <div class="stat-card mb-3" style="padding: 12px; text-align: center;">
            <p class="text-xs tracking-widest uppercase text-muted mb-2">Sensor Distance</p>
            <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                <p class="mono text-3xl font-bold text-yellow-400" id="modal-info-distance">--</p>
                <p class="mono text-sm text-faint">cm</p>
            </div>
            <p class="text-xs text-faint mt-1">Distance detected by ultrasonic sensor</p>
        </div>

        <div class="stat-card mb-4" style="padding: 12px;">
            <p class="text-xs tracking-widest uppercase text-muted mb-2">Last Parked</p>
            <p class="mono font-bold text-primary" id="modal-info-time">just now</p>
            <p class="text-xs text-faint mt-1">Last status change detected</p>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-backdrop" id="deleteModal">
    <div class="modal-box" style="max-width: 400px;">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="modal-title" style="color: #ef4444;">Delete Slots</p>
                <p class="modal-subtitle" id="delete-modal-subtitle">This action cannot be undone.</p>
            </div>
            <button onclick="window.closeDeleteModal()" class="modal-close-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div id="delete-slots-list" style="
            background: rgba(239,68,68,0.05);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 1rem;
            max-height: 150px;
            overflow-y: auto;
        "></div>

        <button onclick="window.confirmDelete()" class="btn-primary" style="background: #ef4444; margin-bottom: 8px;">
            Delete Selected
        </button>
        <button onclick="window.closeDeleteModal()" class="btn-cancel">Cancel</button>
    </div>
</div>

{{-- Floating Delete Bar --}}
<div class="delete-bar" id="deleteBar">
    <span class="delete-bar-count" id="delete-bar-count">0 selected</span>
    <button class="delete-bar-btn cancel" onclick="window.toggleSelectMode()">Cancel</button>
    <button class="delete-bar-btn confirm" onclick="window.openDeleteModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="display:inline; margin-right:4px;">
            <path d="M3 6H5H21M8 6V4A2 2 0 0110 2H14A2 2 0 0116 4V6M19 6V20A2 2 0 0117 22H7A2 2 0 015 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Delete
    </button>
</div>

</body>
</html>