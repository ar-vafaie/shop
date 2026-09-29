@extends('layouts.master')

@section('title', 'Login & Register')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/auth.js') }}" defer></script>
@endpush


@section('body')
<main>
    <section class="auth-section container">
        <div class="auth-card">

            {{-- ===== Tabs ===== --}}
            <div class="auth-tabs">
                <button type="button" class="auth-tab {{ $errors->has('register') ? '' : 'active' }}" data-tab="login">Login</button>
                <button type="button" class="auth-tab {{ $errors->has('register') ? 'active' : '' }}" data-tab="register">Register</button>
            </div>

            {{-- ================= LOGIN FORM ================= --}}
            <form class="auth-form {{ $errors->has('register') ? '' : 'active' }}"
                  id="loginForm"
                  method="POST"
                  action="{{ route('login.post') }}"
                  novalidate>
                @csrf

                <h2 class="auth-title">Welcome Back</h2>

                @if ($errors->has('login'))
                    <div class="server-error">{{ $errors->first('login') }}</div>
                @endif

                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <input type="email"
                           id="loginEmail"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="your@email.com"
                           autocomplete="email"
                           required>
                    <span class="error-msg" id="loginEmailError"></span>
                    @error('email')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password"
                           id="loginPassword"
                           name="password"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           required>
                    <span class="error-msg" id="loginPasswordError"></span>
                    @error('password')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn--primary btn--full">Sign In</button>

                <p class="auth-switch">
                    Don't have an account?
                    <a href="#" id="switchToRegister">Register</a>
                </p>
            </form>


            {{-- ================= REGISTER FORM ================= --}}
            <form class="auth-form {{ $errors->has('register') ? 'active' : '' }}"
                  id="registerForm"
                  method="POST"
                  action="{{ route('register.post') }}"
                  novalidate>
                @csrf

                <h2 class="auth-title">Create Account</h2>

                <div class="form-group">
                    <label for="regName">Name</label>
                    <input type="text"
                           id="regName"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="John"
                           autocomplete="given-name"
                           required>
                    <span class="error-msg" id="regNameError"></span>
                    @error('name')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regFamily">Family</label>
                    <input type="text"
                           id="regFamily"
                           name="family"
                           value="{{ old('family') }}"
                           placeholder="Doe"
                           autocomplete="family-name"
                           required>
                    <span class="error-msg" id="regFamilyError"></span>
                    @error('family')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regUsername">Username</label>
                    <input type="text"
                           id="regUsername"
                           name="username"
                           value="{{ old('username') }}"
                           placeholder="johndoe"
                           autocomplete="username"
                           required>
                    <span class="error-msg" id="regUsernameError"></span>
                    @error('username')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <input type="email"
                           id="regEmail"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="your@email.com"
                           autocomplete="email"
                           required>
                    <span class="error-msg" id="regEmailError"></span>
                    @error('email')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password"
                           id="regPassword"
                           name="password"
                           placeholder="Min 6 chars, 1 number & 1 uppercase"
                           autocomplete="new-password"
                           required>
                    <span class="error-msg" id="regPasswordError"></span>
                    @error('password')
                        <span class="error-msg server">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="regConfirm">Confirm Password</label>
                    <input type="password"
                           id="regConfirm"
                           name="password_confirmation"
                           placeholder="Re-enter password"
                           autocomplete="new-password"
                           required>
                    <span class="error-msg" id="regConfirmError"></span>
                </div>

                <button type="submit" class="btn btn--primary btn--full">Register</button>

                <p class="auth-switch">
                    Already have an account?
                    <a href="#" id="switchToLogin">Login</a>
                </p>
            </form>

        </div>

        {{-- Toast --}}
        <div class="toast-message" id="toastMessage"></div>
    </section>
</main>
@endsection