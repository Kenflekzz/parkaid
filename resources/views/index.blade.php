<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Park Aid — Dashboard</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css', 'resources/js/app.js', 'resources/js/parking.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        #floors-container { display: none; }

        .legend-dot {
            width: 10px; height: 10px;
            border-radius: 50%; display: inline-block;
        }
        .legend-dot-green { background-color: #4ade80; box-shadow: 0 0 4px #4ade80; }
        .legend-dot-red   { background-color: #ef4444; box-shadow: 0 0 4px #ef4444; }

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

        /* ── Responsive Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        /* ── User dropdown responsive ── */
        .user-info {
            display: flex;
            flex-direction: column;
        }

        /* ── Mobile burger button ── */
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

        /* ── Tablet: 768px ── */
        @media (max-width: 768px) {
            .mobile-menu-btn { display: flex; }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.75rem !important;
            }

            .stat-card .text-4xl {
                font-size: 1.75rem !important;
            }

            .header-actions .user-info {
                display: none;
            }

            .header-actions .dropdown-arrow {
                display: none;
            }

            .user-dropdown-btn {
                padding: 6px !important;
            }

            .live-badge-text {
                display: none;
            }
        }

        /* ── Small mobile: 480px ── */
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: repeat(1, 1fr);
                gap: 0.6rem;
            }

            .stat-card {
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-align: left;
                padding: 0.85rem 1rem !important;
            }

            .stat-card .text-xs.tracking-widest {
                margin-bottom: 0 !important;
            }

            .stat-card .text-xs.mt-1 {
                margin-top: 0 !important;
                display: none;
            }

            .stat-card .text-4xl {
                font-size: 1.75rem !important;
                margin: 0 !important;
            }

            .page-title {
                font-size: 1.6rem !important;
            }

            .page-header {
                margin-bottom: 1.25rem;
            }

            /* Stack capacity label + bar label */
            .capacity-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 4px;
            }

            /* Legend row compact */
            .legend-row {
                gap: 1.25rem !important;
            }
        }

        /* ── Very small: 360px ── */
        @media (max-width: 360px) {
            .page-title { font-size: 1.35rem !important; }
            .header-actions { gap: 6px; }
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
            <a href="{{ route('dashboard') }}" class="nav-item active">
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
                    <span class="sidebar-stat-value" id="sidebar-total">0</span>
                </div>
                <div class="sidebar-stat">
                    <span class="sidebar-stat-label">Available</span>
                    <span class="sidebar-stat-value text-green-500" id="sidebar-available">0</span>
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
            <div class="page-header fade-1">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-yellow-400">
                            <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17M5 17H3V15H5M19 17H21V15H19M5 17H19M7 14.5C7 15.3 6.33 16 5.5 16S4 15.3 4 14.5 4.67 13 5.5 13 7 13.7 7 14.5ZM20 14.5C20 15.3 19.33 16 18.5 16S17 15.3 17 14.5 17.67 13 18.5 13 20 13.7 20 14.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="mono text-xs tracking-widest uppercase text-muted">Smart Parking System</span>
                    </div>
                    <h1 class="mono font-bold tracking-wide page-title">
                        <span class="text-yellow-400">OVERVIEW</span>
                    </h1>
                    <p id="clock" class="mono text-xs mt-1 text-faint">--:--:--</p>
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

            <div class="road-divider mb-6 fade-2"></div>

            {{-- Stats --}}
            <div class="fade-3">
                <div class="stats-grid">
                    <div class="stat-card green-accent">
                        <p class="text-xs tracking-widest uppercase mb-2 text-muted">Available</p>
                        <p class="mono text-4xl font-bold text-green-500" id="stat-vacant">0</p>
                        <p class="text-xs mt-1 text-faint">slots free</p>
                    </div>
                    <div class="stat-card red-accent">
                        <p class="text-xs tracking-widest uppercase mb-2 text-muted">Occupied</p>
                        <p class="mono text-4xl font-bold text-red-500" id="stat-occupied">0</p>
                        <p class="text-xs mt-1 text-faint">slots taken</p>
                    </div>
                    <div class="stat-card blue-accent">
                        <p class="text-xs tracking-widest uppercase mb-2 text-muted">Occupancy</p>
                        <p class="mono text-4xl font-bold text-blue-500" id="stat-pct">0%</p>
                        <p class="text-xs mt-1 text-faint">of capacity</p>
                    </div>
                </div>

                <div class="stat-card mb-6">
                    <div class="flex items-center justify-between mb-3 capacity-header">
                        <p class="mono text-xs tracking-widest uppercase text-muted">Capacity Usage</p>
                        <p class="mono text-xs text-faint" id="bar-label">0 / 0 slots used</p>
                    </div>
                    <div class="w-full h-3 rounded-full overflow-hidden bar-track">
                        <div class="bar-fill h-full rounded-full bg-green-400" id="occ-bar" style="width:0%;"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-faint">0%</span>
                        <span class="text-xs text-faint">50%</span>
                        <span class="text-xs text-faint">100%</span>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-8 legend-row">
                    <div class="flex items-center gap-2 text-xs text-muted">
                        <div class="legend-dot legend-dot-green"></div>
                        <span>Vacant</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-muted">
                        <div class="legend-dot legend-dot-red"></div>
                        <span>Occupied</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="floors-container" style="display:none;"></div>
<div id="bulkModal" class="modal-backdrop" style="display:none;"></div>
<div id="singleModal" class="modal-backdrop" style="display:none;"></div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

</body>
</html>