<div>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="profile-form-group">
            <label class="profile-form-label">Current Password</label>
            <div class="password-wrapper">
                <input class="profile-form-input" id="current_password" type="password" name="current_password" required oninput="toggleEyeIcon('current_password')">
                <button type="button" class="password-toggle hidden" id="toggle_current_password" onclick="togglePasswordVisibility('current_password', this)">
                    🐵
                </button>
            </div>
            @error('current_password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form-group">
            <label class="profile-form-label">New Password</label>
            <div class="password-wrapper">
                <input class="profile-form-input" id="password" type="password" name="password" required oninput="toggleEyeIcon('password')">
                <button type="button" class="password-toggle hidden" id="toggle_password" onclick="togglePasswordVisibility('password', this)">
                    🐵
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form-group">
            <label class="profile-form-label">Confirm Password</label>
            <div class="password-wrapper">
                <input class="profile-form-input" id="password_confirmation" type="password" name="password_confirmation" required oninput="toggleEyeIcon('password_confirmation')">
                <button type="button" class="password-toggle hidden" id="toggle_password_confirmation" onclick="togglePasswordVisibility('password_confirmation', this)">
                    🐵
                </button>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="profile-btn-primary">Update Password</button>
        </div>
    </form>
</div>

<style>
    /* Password Wrapper Styles */
    .password-wrapper {
        position: relative;
        width: 100%;
    }

    /* Add margin/padding to the input field */
    .profile-form-input {
        width: 100%;
        padding: 10px 40px 10px 14px !important;
        margin: 0 !important;
        box-sizing: border-box;
    }

    /* Ensure the form group has proper spacing */
    .profile-form-group {
        margin-bottom: 1.25rem;
        width: 100%;
    }

    /* For the container of the form */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }

    /* Make sure the form itself doesn't have overflow issues */
    form {
        width: 100%;
        overflow: visible;
    }

    .password-toggle {
        position: absolute;
        right: 8px;
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
        transition: color 0.2s;
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
    }

    /* Light mode eye icon color */
    [data-theme="light"] .password-toggle {
        color: #6b7c90;
    }

    [data-theme="light"] .password-toggle:hover {
        color: #ca8a04;
    }

    /* Ensure the stat card doesn't clip the content */
    .stat-card {
        overflow: visible !important;
    }

    /* Add proper spacing for the form inside stat card */
    .stat-card form {
        width: 100%;
        padding: 0;
    }
</style>

<script>
    // Toggle eye icon visibility based on input value
    function toggleEyeIcon(inputId) {
        const input = document.getElementById(inputId);
        const toggleButton = document.getElementById('toggle_' + inputId);
        
        if (input.value.length > 0) {
            toggleButton.classList.remove('hidden');
            toggleButton.classList.add('visible');
        } else {
            toggleButton.classList.remove('visible');
            toggleButton.classList.add('hidden');
        }
    }
    
    // Password visibility toggle function
    function togglePasswordVisibility(inputId, buttonElement) {
        const input = document.getElementById(inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            buttonElement.textContent = '🙈';
        } else {
            input.type = 'password';
            buttonElement.textContent = '🐵';
        }
    }
    
    // Initialize eye icons on page load (check if fields already have values)
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInputs = ['current_password', 'password', 'password_confirmation'];
        passwordInputs.forEach(function(inputId) {
            const input = document.getElementById(inputId);
            if (input && input.value.length > 0) {
                toggleEyeIcon(inputId);
            }
        });
    });
</script>