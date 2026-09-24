@extends('backend.auth.layout.master')
@push('user-title')
    <title>Create New User — Admin</title>
@endpush

@push('extra-styles')
<style>
    .auth-shell { max-width: 520px; }

    .user-avatar-upload {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-bottom: 4px;
    }
    .avatar-preview-ring {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px dashed #c7d2e0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
        position: relative;
    }
    .avatar-preview-ring:hover {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.12);
    }
    .avatar-preview-ring img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-preview-ring .avatar-icon {
        font-size: 2.2rem;
        color: #94a3b8;
    }
    .avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
        border-radius: 50%;
    }
    .avatar-preview-ring:hover .avatar-overlay { opacity: 1; }
    .avatar-overlay i { color: #fff; font-size: 1.2rem; }

    .form-floating-group { position: relative; }
    .form-floating-group .field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        z-index: 5;
    }
    .form-floating-group input,
    .form-floating-group select {
        padding-left: 40px;
    }

    .role-selector { display: flex; gap: 10px; margin-bottom: 4px; }
    .role-option {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .role-option:hover { border-color: #93c5fd; background: #eff6ff; }
    .role-option input[type="radio"] { display: none; }
    .role-option.selected {
        border-color: #3b82f6;
        background: #eff6ff;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
    }
    .role-option .role-icon { font-size: 1.5rem; display: block; margin-bottom: 4px; }
    .role-option .role-label { font-size: 0.82rem; font-weight: 600; color: #374151; }
    .role-option .role-desc { font-size: 0.72rem; color: #6b7280; }

    .strength-bar { height: 4px; border-radius: 2px; background: #e2e8f0; margin-top: 6px; overflow: hidden; }
    .strength-fill { height: 100%; width: 0; border-radius: 2px; transition: width 0.3s, background 0.3s; }

    .btn-create {
        background: linear-gradient(135deg, #1a4d8c 0%, #2e74c9 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(26,77,140,0.3);
    }
    .btn-create:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(26,77,140,0.38);
        color: #fff;
    }
    .btn-create:active { transform: translateY(0); }
    .btn-create .spinner-border { display: none; }
    .btn-create.loading .spinner-border { display: inline-block; }
    .btn-create.loading .btn-text { display: none; }

    .divider-text {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #9ca3af;
        font-size: 0.78rem;
        margin: 18px 0;
    }
    .divider-text::before, .divider-text::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e5e7eb;
    }

    .page-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #1d4ed8;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('backend-auth-content')
<div class="auth-card-wrap" style="padding: 36px 32px 28px;">

    {{-- Header --}}
    <div class="text-center mb-4">
        <div class="page-header-badge">
            <i class="bi bi-shield-lock-fill"></i> Admin Panel
        </div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;">Create New User</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Add a team member to the admin system</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2 mb-4" style="border-radius:10px; font-size:0.875rem;">
            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
            <ul class="mb-0 ps-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" id="registerForm">
        @csrf

        {{-- Avatar Upload --}}
        <div class="user-avatar-upload mb-4">
            <div class="avatar-preview-ring" id="avatarRing" onclick="document.getElementById('imageInput').click()">
                <img id="avatarPreview" src="" alt="" style="display:none;">
                <i class="bi bi-person-fill avatar-icon" id="avatarIcon"></i>
                <div class="avatar-overlay">
                    <i class="bi bi-camera-fill"></i>
                </div>
            </div>
            <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;">
            <span class="text-muted" style="font-size:0.8rem;">Click to upload avatar <span class="text-muted">(optional)</span></span>
        </div>

        {{-- Full Name --}}
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.875rem;">Full Name</label>
            <div class="form-floating-group">
                <i class="bi bi-person field-icon"></i>
                <input class="form-control @error('name') is-invalid @enderror"
                       name="name" type="text" id="nameInput"
                       value="{{ old('name') }}"
                       placeholder="Enter full name" required
                       style="border-radius:10px;">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.875rem;">Email Address</label>
            <div class="form-floating-group">
                <i class="bi bi-envelope field-icon"></i>
                <input class="form-control @error('email') is-invalid @enderror"
                       type="email" name="email" id="emailInput"
                       value="{{ old('email') }}"
                       placeholder="name@school.edu.np" required
                       style="border-radius:10px;">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Role selector (only for super admin adding editors) --}}
        @if(Auth::check() && Auth::user()->a_type === 'A')
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.875rem;">Role</label>
            <div class="role-selector">
                <label class="role-option selected" id="editorOption">
                    <input type="radio" name="_role_display" value="editor" checked>
                    <span class="role-icon">✍️</span>
                    <div class="role-label">Editor</div>
                    <div class="role-desc">Manage content</div>
                </label>
            </div>
            <small class="text-muted">New users are assigned the <strong>Editor</strong> role automatically.</small>
        </div>
        @endif

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.875rem;">Password</label>
            <div class="form-floating-group">
                <i class="bi bi-lock field-icon"></i>
                <input type="password" id="passwordInput" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required
                       style="border-radius:10px; padding-right:42px;">
                <span id="togglePassword" style="position:absolute;right:13px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;font-size:1rem;z-index:5;">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Strength bar --}}
            <div class="strength-bar mt-2">
                <div class="strength-fill" id="strengthFill"></div>
            </div>
            <div id="strengthLabel" class="mt-1" style="font-size:0.75rem; color:#9ca3af;"></div>
            <small class="text-muted d-block mt-1">Minimum 8 characters</small>
        </div>

        {{-- Submit --}}
        <div class="mt-4">
            <button class="btn btn-create w-100" type="submit" id="submitBtn">
                <span class="spinner-border spinner-border-sm me-2"></span>
                <span class="btn-text"><i class="bi bi-person-plus-fill me-2"></i>Create User</span>
            </button>
        </div>
    </form>

    <div class="divider-text">or</div>
    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-muted" style="font-size:0.875rem;">
            <i class="bi bi-arrow-left me-1"></i>Back to Sign In
        </a>
    </div>
</div>
@endsection

@push('extra-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- Avatar preview ---
    const imageInput = document.getElementById('imageInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarIcon = document.getElementById('avatarIcon');

    imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                avatarPreview.src = ev.target.result;
                avatarPreview.style.display = 'block';
                avatarIcon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // --- Password toggle ---
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    });

    // --- Password strength ---
    const strengthFill = document.getElementById('strengthFill');
    const strengthLabel = document.getElementById('strengthLabel');

    passwordInput.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const configs = [
            { width: '0%',   color: '#e2e8f0', label: '' },
            { width: '25%',  color: '#ef4444', label: '🔴 Weak' },
            { width: '50%',  color: '#f97316', label: '🟠 Fair' },
            { width: '75%',  color: '#eab308', label: '🟡 Good' },
            { width: '100%', color: '#22c55e', label: '🟢 Strong' },
        ];
        const cfg = configs[score];
        strengthFill.style.width = cfg.width;
        strengthFill.style.background = cfg.color;
        strengthLabel.textContent = cfg.label;
        strengthLabel.style.color = cfg.color;
    });

    // --- Submit loading state ---
    document.getElementById('registerForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });
});
</script>
@endpush
