@extends('frontend.layouts.app')
@section('content')

<style>
/* ── Core Variables ── */
:root {
    --primary:    #6c47ff;
    --primary-dk: #4f2fe0;
    --accent:     #ff6b6b;
    --dark:       #0f0e17;
    --card-bg:    #ffffff;
    --muted:      #6b7280;
    --border:     #e5e7eb;
    --radius:     14px;
    --shadow:     0 20px 60px rgba(108,71,255,.13);
    --input-bg:   #f8f7ff;
}

/* ── Page wrapper ── */
.auth-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px 60px;
}

/* ── Card ── */
.signup-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 48px 44px;
    width: 100%;
    max-width: 460px;
    border: 1px solid rgba(108,71,255,.1);
    position: relative;
    overflow: hidden;
}

.signup-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: var(--radius) var(--radius) 0 0;
}

/* ── Title ── */
.form-title {
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: .08em;
    color: var(--dark);
    margin-bottom: 8px;
    text-align: center;
}

.form-subtitle {
    text-align: center;
    color: var(--muted);
    font-size: .9rem;
    margin-bottom: 32px;
}

/* ── Labels ── */
.form-label {
    font-size: .82rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    display: block;
    letter-spacing: .03em;
}

.form-label span { color: var(--accent); margin-left: 2px; }

/* ── Inputs ── */
.form-control {
    background: var(--input-bg);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 12px 16px;
    font-size: .95rem;
    width: 100%;
    color: var(--dark);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(108,71,255,.12);
    background: #fff;
}

/* ── Password wrapper ── */
.pass-wrap {
    position: relative;
}

.pass-wrap .form-control {
    padding-right: 48px;
}

.pass-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: var(--muted);
    transition: color .2s;
    display: flex;
    align-items: center;
}

.pass-toggle:hover { color: var(--primary); }

.pass-toggle svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* ── Form field spacing ── */
.field-group {
    margin-bottom: 20px;
    width: 100%;
}

/* ── Forgot password link ── */
.forgot-link-wrap {
    text-align: right;
    margin-top: 6px;
}

.forgot-link-wrap a {
    font-size: .8rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: color .2s;
}

.forgot-link-wrap a:hover {
    color: var(--primary-dk);
    text-decoration: underline;
}

/* ── Submit button ── */
.btn-register {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dk));
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: .05em;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s, opacity .15s;
    box-shadow: 0 6px 20px rgba(108,71,255,.35);
    margin-top: 8px;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(108,71,255,.45);
}

.btn-register:active {
    transform: translateY(0);
    opacity: .9;
}

/* ── Bottom link ── */
.login-link {
    text-align: center;
    margin-top: 24px;
    font-size: .88rem;
    color: var(--muted);
}

.login-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
    transition: color .2s;
}

.login-link a:hover {
    color: var(--primary-dk);
    text-decoration: underline;
}

/* ── Error / Success alerts ── */
.auth-alert {
    padding: 11px 14px;
    border-radius: 9px;
    font-size: .85rem;
    margin-bottom: 20px;
}

.auth-alert.error {
    background: #fff1f1;
    border: 1px solid #fca5a5;
    color: #b91c1c;
}

.auth-alert.success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #15803d;
}

/* ── Responsive ── */
@media (max-width: 575px) {
    .signup-card {
        padding: 36px 22px 32px;
        border-radius: 16px;
    }

    .form-title { font-size: 1.45rem; }
}

@media (max-width: 360px) {
    .signup-card { padding: 28px 16px 26px; }
}
</style>

<div class="auth-page">
    <div class="signup-card mt-5">

        <div class="form-title">LOGIN</div>
        <p class="form-subtitle">Welcome back! Please sign in to continue.</p>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="auth-alert success">{{ session('success') }}</div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="auth-alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field-group">
                <label class="form-label">Email <span>*</span></label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Email Address"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                />
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label class="form-label">Password <span>*</span></label>
                <div class="pass-wrap">
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        id="password"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    />
                    <button type="button" class="pass-toggle" onclick="togglePass('password', this)" aria-label="Toggle password">
                        {{-- Eye Open --}}
                        <svg class="eye-show" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        {{-- Eye Closed (hidden by default) --}}
                        <svg class="eye-hide" viewBox="0 0 24 24" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                {{-- Forgot Password --}}
                <div class="forgot-link-wrap">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </div>

            {{-- Submit --}}
            <div class="field-group" style="margin-bottom:0">
                <button type="submit" class="btn-register">Login</button>
            </div>
        </form>

        <div class="login-link">
          Or

          <a href="{{ route('social.redirect', 'google') }}" class="btn btn-danger">
            <i class="fab fa-google"></i> Login with Google
        </a>
        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-primary">
            <i class="fab fa-facebook"></i> Login with Facebook
        </a>
        </div>

    </div>
</div>

<br><br>
<footer class="footer-section">
    @include('frontend.layouts.partials.footer')
</footer>

<script>
function togglePass(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const eyeShow = btn.querySelector('.eye-show');
    const eyeHide = btn.querySelector('.eye-hide');

    if (input.type === 'password') {
        input.type = 'text';
        eyeShow.style.display = 'none';
        eyeHide.style.display = 'block';
        btn.style.color = 'var(--primary)';
    } else {
        input.type = 'password';
        eyeShow.style.display = 'block';
        eyeHide.style.display = 'none';
        btn.style.color = '';
    }
}
</script>

@endsection