<div>
    @if (session('status') === 'profile-updated')
        <div class="profile-success">
            Profile updated successfully!
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="profile-form-group">
            <label class="profile-form-label">Name</label>
            <input class="profile-form-input" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
            @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form-group">
            <label class="profile-form-label">Email</label>
            <input class="profile-form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="profile-btn-primary">Save Changes</button>
        </div>
    </form>
</div>