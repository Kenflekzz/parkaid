<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Park Aid — Profile</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css', 'resources/js/app.js', 'resources/js/parking.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
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
            <a href="#" class="nav-item" onclick="return false;">
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

            {{-- Header with Enhanced User Dropdown --}}
            <div class="flex items-start justify-between mb-8 fade-1">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-yellow-400">
                            <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17M5 17H3V15H5M19 17H21V15H19M5 17H19M7 14.5C7 15.3 6.33 16 5.5 16S4 15.3 4 14.5 4.67 13 5.5 13 7 13.7 7 14.5ZM20 14.5C20 15.3 19.33 16 18.5 16S17 15.3 17 14.5 17.67 13 18.5 13 20 13.7 20 14.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="mono text-xs tracking-widest uppercase text-muted">Profile Settings</span>
                    </div>
                    <h1 class="mono text-4xl font-bold tracking-wide">
                        <span class="text-yellow-400">MY</span><span class="text-primary">PROFILE</span>
                    </h1>
                    <p class="mono text-xs mt-1.5 text-faint">Manage your account settings</p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Live Badge --}}
                    <div class="flex items-center gap-2 bg-green-500/10 border border-green-500/20 rounded-full px-3 py-1.5">
                        <span class="live-pulse w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                        <span class="mono text-xs tracking-widest text-green-400 uppercase">Live</span>
                    </div>
                    
                    {{-- Theme Toggle - Replaces Dashboard Button --}}
                    <div class="page-theme-toggle">
                        <button class="theme-toggle-btn" onclick="toggleTheme()" title="Toggle Dark/Light mode">
                            <div class="toggle-track">
                                <div class="toggle-thumb"></div>
                                <div class="toggle-icons">
                                    <span class="toggle-sun">☀️</span>
                                    <span class="toggle-moon">🌙</span>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    {{-- ENHANCED USER DROPDOWN --}}
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

            {{-- Profile Content --}}
            <div class="fade-3">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Profile Sidebar Card --}}
                    <div class="lg:col-span-1">
                        <div class="stat-card text-center">
                            <div class="profile-avatar mb-4">
                                <div class="avatar-circle">
                                    <span class="avatar-initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                </div>
                            </div>
                            <h3 class="mono text-xl font-bold text-primary">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-muted mt-1">{{ Auth::user()->email }}</p>
                            <div class="mt-4 pt-4 border-t border-border">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-muted">Member since</span>
                                    <span class="text-primary">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted">Account status</span>
                                    <span class="text-green-500">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Forms --}}
                    <div class="lg:col-span-2">
                        {{-- Update Profile Information --}}
                        <div class="stat-card mb-6">
                            <h3 class="mono text-lg font-bold text-primary mb-4 flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21V19C20 16.8 18.2 15 16 15H8C5.8 15 4 16.8 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                    <path d="M17 3L21 7M14 6L18 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Profile Information
                            </h3>
                            @include('profile.partials.update-profile-information-form')
                        </div>

                        {{-- Update Password --}}
                        <div class="stat-card mb-6">
                            <h3 class="mono text-lg font-bold text-primary mb-4 flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M7 11V7C7 4.2 9.2 2 12 2C14.8 2 17 4.2 17 7V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Update Password
                            </h3>
                            @include('profile.partials.update-password-form')
                        </div>

                        {{-- Delete Account --}}
                        <div class="stat-card border-red-500/30">
                            <h3 class="mono text-lg font-bold text-red-500 mb-4 flex items-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M10 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M14 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Delete Account
                            </h3>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<style>
    /* Profile Avatar Styles */
    .profile-avatar {
        display: flex;
        justify-content: center;
    }
    
    .avatar-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #facc15, #eab308);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(250,204,21,0.3);
    }
    
    .avatar-initials {
        font-family: 'Oxanium', monospace;
        font-size: 32px;
        font-weight: 700;
        color: #0e1117;
    }
    
    [data-theme="light"] .avatar-circle {
        box-shadow: 0 4px 15px rgba(234,179,8,0.2);
    }
    
    /* Form Styles for Profile */
    .profile-form-group {
        margin-bottom: 1.25rem;
    }
    
    .profile-form-label {
        display: block;
        font-family: 'Oxanium', monospace;
        font-size: 11px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    
    .profile-form-input {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--text-primary);
        font-size: 14px;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .profile-form-input:focus {
        border-color: rgba(250,204,21,0.4);
        box-shadow: 0 0 0 3px rgba(250,204,21,0.08);
    }
    
    .profile-btn-primary {
        background: #facc15;
        color: #0e1117;
        font-family: 'Oxanium', monospace;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .profile-btn-primary:hover {
        background: #fde047;
        transform: translateY(-1px);
    }
    
    .profile-btn-danger {
        background: rgba(239,68,68,0.1);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,0.3);
        font-family: 'Oxanium', monospace;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .profile-btn-danger:hover {
        background: rgba(239,68,68,0.2);
        border-color: rgba(239,68,68,0.5);
    }
    
    .profile-btn-secondary {
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border);
        font-family: 'Oxanium', monospace;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .profile-btn-secondary:hover {
        border-color: var(--text-faint);
        color: var(--text-secondary);
    }
    
    /* Alert Messages */
    .profile-success {
        background: rgba(34,197,94,0.1);
        border: 1px solid rgba(34,197,94,0.3);
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 1rem;
        color: #22c55e;
        font-size: 13px;
    }
    
    .profile-error {
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 1rem;
        color: #ef4444;
        font-size: 13px;
    }
    
    /* Theme Toggle Button Styles */
    .page-theme-toggle {
        display: inline-block;
    }
    
    .theme-toggle-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.3s ease;
    }
    
    .toggle-track {
        width: 60px;
        height: 32px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 999px;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .theme-toggle-btn:hover .toggle-track {
        border-color: #facc15;
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(250,204,21,0.2);
    }
    
    .toggle-thumb {
        position: absolute;
        left: 3px;
        top: 3px;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 2;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    [data-theme="light"] .toggle-thumb {
        transform: translateX(28px);
    }
    
    .toggle-icons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0 8px;
        position: relative;
        z-index: 1;
    }
    
    .toggle-sun,
    .toggle-moon {
        font-size: 13px;
        opacity: 0.6;
        transition: opacity 0.3s;
        line-height: 1;
    }
    
    [data-theme="light"] .toggle-sun {
        opacity: 1;
    }
    
    [data-theme="dark"] .toggle-moon {
        opacity: 1;
    }
</style>

<script>
    // User menu dropdown - attached to window for global access
    window.toggleUserMenu = function() {
        const menu = document.getElementById('userMenu');
        if (menu) {
            menu.classList.toggle('show');
            menu.classList.toggle('hidden');
        }
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('userMenu');
        const button = e.target.closest('.user-dropdown-btn');
        if (menu && !button && !menu.contains(e.target)) {
            menu.classList.add('hidden');
            menu.classList.remove('show');
        }
    });
    
    // Theme toggle function
    let currentTheme = localStorage.getItem('parkaid-theme') || 'dark';
    
    function toggleTheme() {
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('parkaid-theme', newTheme);
        currentTheme = newTheme;
    }
</script>

</body>
</html>