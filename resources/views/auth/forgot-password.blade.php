<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Park Aid — Forgot Password</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css', 'resources/js/app.js', 'resources/js/parking.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>

<div class="top-bar"></div>

{{-- Theme Toggle Button --}}
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

{{-- Background with parking theme --}}
<div class="forgot-bg">
    <div class="parking-pattern"></div>
    <div class="floating-cars">
        <span class="car-1">🚗</span>
        <span class="car-2">🚙</span>
        <span class="car-3">🚕</span>
        <span class="car-4">🚓</span>
        <span class="car-5">🚑</span>
        <span class="car-6">🚒</span>
    </div>
    <div class="overlay-gradient"></div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <a href="/" class="login-logo">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                    <path d="M5 11L6.5 6.5H17.5L19 11M5 11H19M5 11V17M19 11V17M5 17H3V15H5M19 17H21V15H19M5 17H19M7 14.5C7 15.3 6.33 16 5.5 16S4 15.3 4 14.5 4.67 13 5.5 13 7 13.7 7 14.5ZM20 14.5C20 15.3 19.33 16 18.5 16S17 15.3 17 14.5 17.67 13 18.5 13 20 13.7 20 14.5Z" stroke="#facc15" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <h1 style="color: var(--text-primary); font-size: 28px; margin-top: 1rem;">Forgot Password?</h1>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 0.5rem;">Enter your email to receive a reset link</p>
        </div>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group">
                <label class="input-label" for="email">Email Address</label>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-reset">
                Send Reset Link
            </button>

            <div class="back-link">
                <a href="{{ route('login') }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--bg-base);
        overflow-x: hidden;
        min-height: 100vh;
    }
    
    .top-bar {
        height: 3px;
        background: linear-gradient(90deg, transparent, #facc15 20%, #facc15 80%, transparent);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }
    
    /* Page Theme Toggle - Top Right Corner */
    .page-theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
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
    
    /* Background with parking theme */
    .forgot-bg {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
        overflow: hidden;
    }
    
    .parking-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            repeating-linear-gradient(90deg, 
                rgba(250,204,21,0.08) 0px, 
                rgba(250,204,21,0.08) 2px,
                transparent 2px,
                transparent 60px),
            repeating-linear-gradient(0deg, 
                rgba(250,204,21,0.08) 0px, 
                rgba(250,204,21,0.08) 2px,
                transparent 2px,
                transparent 60px);
        background-size: 60px 60px;
    }
    
    .parking-pattern::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
        height: 500px;
        background-image: radial-gradient(circle at center, rgba(34,197,94,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .floating-cars {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    
    .car-1, .car-2, .car-3, .car-4, .car-5, .car-6 {
        position: absolute;
        font-size: 55px;
        opacity: 0.25;
        animation: float linear infinite;
        filter: drop-shadow(0 0 8px rgba(250,204,21,0.4));
    }
    
    .car-1 {
        top: 10%;
        left: -100px;
        animation-duration: 18s;
        animation-delay: 0s;
    }
    
    .car-2 {
        top: 25%;
        left: -100px;
        animation-duration: 22s;
        animation-delay: 2s;
    }
    
    .car-3 {
        top: 40%;
        left: -100px;
        animation-duration: 15s;
        animation-delay: 4s;
    }
    
    .car-4 {
        top: 55%;
        left: -100px;
        animation-duration: 20s;
        animation-delay: 1s;
    }
    
    .car-5 {
        top: 70%;
        left: -100px;
        animation-duration: 25s;
        animation-delay: 3s;
    }
    
    .car-6 {
        top: 85%;
        left: -100px;
        animation-duration: 17s;
        animation-delay: 5s;
    }
    
    @keyframes float {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(100vw + 200px));
        }
    }
    
    .overlay-gradient {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, 
            transparent 0%,
            var(--bg-base) 100%);
        opacity: 0.6;
    }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .login-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 32px;
        padding: 2.5rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 45px -12px rgba(0,0,0,0.3);
        border-top: 4px solid #facc15;
        backdrop-filter: blur(5px);
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .login-logo {
        display: inline-block;
        text-decoration: none;
    }
    
    .login-logo svg {
        width: 60px;
        height: 60px;
    }
    
    .input-group {
        margin-bottom: 1.25rem;
    }
    
    .input-label {
        display: block;
        font-family: 'Oxanium', monospace;
        font-size: 11px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 8px;
    }
    
    .input-field {
        width: 100%;
        background: var(--bg-input);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 16px;
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .input-field:focus {
        outline: none;
        border-color: #facc15;
        box-shadow: 0 0 0 3px rgba(250,204,21,0.1);
    }
    
    .btn-reset {
        width: 100%;
        background: #facc15;
        color: #0e1117;
        font-family: 'Oxanium', monospace;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 12px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }
    
    .btn-reset:hover {
        background: #fde047;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(250,204,21,0.3);
    }
    
    .back-link {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .back-link a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s;
    }
    
    .back-link a:hover {
        color: #facc15;
    }
    
    .success-message {
        background: rgba(34,197,94,0.1);
        border: 1px solid rgba(34,197,94,0.3);
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 1.5rem;
        color: #22c55e;
        font-size: 13px;
        text-align: center;
    }
    
    .error-text {
        color: #ef4444;
        font-size: 11px;
        margin-top: 6px;
    }
    
    /* Theme variables */
    :root[data-theme="dark"] {
        --bg-base: #0e1117;
        --bg-card: rgba(19, 24, 31, 0.95);
        --bg-input: #0d1117;
        --border: #1e2a38;
        --text-primary: #ffffff;
        --text-muted: #94a3b8;
    }
    
    :root[data-theme="light"] {
        --bg-base: #f0f4f9;
        --bg-card: rgba(255, 255, 255, 0.95);
        --bg-input: #f8fafc;
        --border: #e2e8f0;
        --text-primary: #1e293b;
        --text-muted: #64748b;
    }
</style>

<script>
    // Theme handling
    let currentTheme = localStorage.getItem('parkaid-theme') || 'dark';
    
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('parkaid-theme', theme);
        currentTheme = theme;
    }
    
    function toggleTheme() {
        applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    }
    
    // Apply saved theme on load
    applyTheme(currentTheme);
</script>

</body>
</html>