<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Park Aid — Register</title>
    @include('profile.partials.favicon')
    @vite(['resources/css/app.css', 'resources/css/parking.css'])
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
    
    <div class="login-bg">
        <div class="parking-pattern"></div>
        <div class="floating-cars">
            <span class="car-1">🚗</span>
            <span class="car-2">🚙</span>
            <span class="car-3">🚕</span>
            <span class="car-4">🚓</span>
            <span class="car-5">🚑</span>
            <span class="car-6">🚒</span>
            <span class="car-7">🚌</span>
            <span class="car-8">🚎</span>
            <span class="car-9">🚐</span>
            <span class="car-10">🛻</span>
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
                <h1 style="color: var(--text-primary); font-size: 28px; margin-top: 1rem;">Create Account</h1>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: 0.5rem;">Sign up to manage your parking</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <label class="input-label" for="name">Full Name</label>
                    <input id="name" class="input-field" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="email">Email Address</label>
                    <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="password">Password</label>
                    <div class="password-wrapper">
                        <input id="password" class="input-field" type="password" name="password" required oninput="checkPasswordInput('password')">
                        <button type="button" class="password-toggle hidden" id="passwordToggle" onclick="togglePasswordVisibility('password', this)">
                            🙈
                        </button>
                    </div>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="password_confirmation">Confirm Password</label>
                    <div class="password-wrapper">
                        <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required oninput="checkPasswordInput('password_confirmation')">
                        <button type="button" class="password-toggle hidden" id="passwordConfirmToggle" onclick="togglePasswordVisibility('password_confirmation', this)">
                            🙈
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    Create Account
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
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
        
        /* Password Wrapper Styles */
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 18px;
            transition: all 0.2s;
            border-radius: 6px;
            z-index: 2;
        }
        
        .password-toggle.hidden {
            display: none !important;
        }
        
        .password-toggle.visible {
            display: flex !important;
        }
        
        .password-toggle:hover {
            color: #facc15;
            background: rgba(250,204,21,0.1);
            transform: translateY(-50%) scale(1.1);
        }
        
        [data-theme="light"] .password-toggle {
            color: #6b7c90;
        }
        
        [data-theme="light"] .password-toggle:hover {
            color: #ca8a04;
        }
        
        /* Adjust input padding to accommodate the toggle button */
        .input-field {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 40px 12px 16px !important;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s;
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
        
        .login-bg {
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
        
        .car-1, .car-2, .car-3, .car-4, .car-5, .car-6, .car-7, .car-8, .car-9, .car-10 {
            position: absolute;
            font-size: 55px;
            opacity: 0.3;
            animation: float linear infinite;
            filter: drop-shadow(0 0 8px rgba(250,204,21,0.4));
        }
        
        .car-1 {
            top: 5%;
            left: -100px;
            animation-duration: 16s;
            animation-delay: 0s;
        }
        
        .car-2 {
            top: 18%;
            left: -100px;
            animation-duration: 20s;
            animation-delay: 1.5s;
        }
        
        .car-3 {
            top: 32%;
            left: -100px;
            animation-duration: 14s;
            animation-delay: 3s;
        }
        
        .car-4 {
            top: 48%;
            left: -100px;
            animation-duration: 22s;
            animation-delay: 0.5s;
        }
        
        .car-5 {
            top: 62%;
            left: -100px;
            animation-duration: 18s;
            animation-delay: 2.5s;
        }
        
        .car-6 {
            top: 78%;
            left: -100px;
            animation-duration: 24s;
            animation-delay: 4s;
        }
        
        .car-7 {
            top: 12%;
            right: -100px;
            left: auto;
            animation-duration: 19s;
            animation-delay: 1s;
            animation-direction: reverse;
        }
        
        .car-8 {
            top: 28%;
            right: -100px;
            left: auto;
            animation-duration: 15s;
            animation-delay: 3.5s;
            animation-direction: reverse;
        }
        
        .car-9 {
            top: 55%;
            right: -100px;
            left: auto;
            animation-duration: 21s;
            animation-delay: 2s;
            animation-direction: reverse;
        }
        
        .car-10 {
            top: 88%;
            right: -100px;
            left: auto;
            animation-duration: 17s;
            animation-delay: 4.5s;
            animation-direction: reverse;
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
            opacity: 0.5;
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
        
        .input-field:focus {
            outline: none;
            border-color: #facc15;
            box-shadow: 0 0 0 3px rgba(250,204,21,0.1);
        }
        
        .btn-register {
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
        
        .btn-register:hover {
            background: #fde047;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(250,204,21,0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 13px;
            color: var(--text-muted);
        }
        
        .login-link a {
            color: #facc15;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
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
        let currentTheme = localStorage.getItem('parkaid-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        function toggleTheme() {
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('parkaid-theme', newTheme);
            currentTheme = newTheme;
        }
        
        // Check password input and show/hide toggle button
        function checkPasswordInput(inputId) {
            const input = document.getElementById(inputId);
            let toggleButton;
            
            if (inputId === 'password') {
                toggleButton = document.getElementById('passwordToggle');
            } else if (inputId === 'password_confirmation') {
                toggleButton = document.getElementById('passwordConfirmToggle');
            }
            
            if (toggleButton) {
                if (input.value.length > 0) {
                    toggleButton.classList.remove('hidden');
                    toggleButton.classList.add('visible');
                } else {
                    toggleButton.classList.remove('visible');
                    toggleButton.classList.add('hidden');
                    // Reset to 🙈 when field is empty
                    toggleButton.textContent = '🙈';
                }
            }
        }
        
        // Password visibility toggle function
        function togglePasswordVisibility(inputId, buttonElement) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                buttonElement.textContent = '🐵';  // Monkey face when password is visible
            } else {
                input.type = 'password';
                buttonElement.textContent = '🙈';  // See-no-evil monkey when password is hidden
            }
        }
        
        // Initialize on page load (check if password fields have value from autofill)
        document.addEventListener('DOMContentLoaded', function() {
            checkPasswordInput('password');
            checkPasswordInput('password_confirmation');
        });
    </script>
</body>
</html>