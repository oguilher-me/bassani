@extends('layouts/blankLayout')

@section('title', 'Login - Bassani Móveis')

@section('page-style')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: #1F2A44;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .auth-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* Left Panel - Image Background */
    .auth-decoration {
        flex: 1.2;
        position: relative;
        background-image: url('{{ asset("assets/img/backgrounds/loja-bassani.jpg") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem;
        overflow: hidden;
    }

    /* Gradient Overlay */
    .auth-decoration::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            to bottom,
            rgba(31, 42, 68, 0.3) 0%,
            rgba(31, 42, 68, 0.5) 30%,
            rgba(31, 42, 68, 0.7) 60%,
            rgba(31, 42, 68, 0.95) 100%
        );
        z-index: 1;
    }

    /* Right Edge Mask - Smooth transition */
    .auth-decoration::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 100%;
        background: linear-gradient(to left, #ffffff 0%, transparent 100%);
        z-index: 2;
    }

    /* Decorative accent line */
    .decoration-accent {
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #DE0802 0%, #B3211A 50%, transparent 100%);
        z-index: 3;
    }

    .decoration-content {
        position: relative;
        z-index: 4;
        color: white;
    }

    .decoration-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(222, 8, 2, 0.9);
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .decoration-badge svg {
        width: 16px;
        height: 16px;
    }

    .decoration-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
        line-height: 1.2;
    }

    .decoration-subtitle {
        font-size: 1.1rem;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 10px rgba(0, 0, 0, 0.3);
    }

    .decoration-features {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .feature-item svg {
        width: 18px;
        height: 18px;
        color: #DE0802;
    }

    /* Right Panel - Form */
    .auth-form-panel {
        flex: 1;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem 4rem;
        position: relative;
    }

    .auth-form-container {
        width: 100%;
        max-width: 380px;
    }

    .auth-logo-container {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .auth-logo {
        height: 60px;
    }

    .auth-welcome {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-welcome h1 {
        color: #1F2A44;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .auth-welcome p {
        color: #666;
        font-size: 0.9rem;
    }

    /* Form Styles */
    .form-group {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 0.875rem 0.875rem 2.75rem;
        border: 1.5px solid #D2D4DA;
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-input:focus {
        outline: none;
        border-color: #DE0802;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(222, 8, 2, 0.1);
    }

    .form-input::placeholder {
        color: #999;
    }

    .form-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        transition: color 0.3s ease;
    }

    .form-icon svg {
        width: 18px;
        height: 18px;
    }

    .form-group:focus-within .form-icon {
        color: #DE0802;
    }

    .form-label {
        position: absolute;
        left: 2.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 0.95rem;
        pointer-events: none;
        transition: all 0.3s ease;
        background: transparent;
        padding: 0 0.25rem;
    }

    .form-input:focus + .form-label,
    .form-input:not(:placeholder-shown) + .form-label {
        top: 0;
        left: 0.625rem;
        font-size: 0.7rem;
        color: #DE0802;
        background: linear-gradient(to bottom, transparent 50%, #fff 50%);
        padding: 0 0.375rem;
    }

    /* Checkbox */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .custom-checkbox input {
        display: none;
    }

    .checkmark {
        width: 18px;
        height: 18px;
        border: 1.5px solid #D2D4DA;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .custom-checkbox input:checked + .checkmark {
        background: #DE0802;
        border-color: #DE0802;
    }

    .checkmark svg {
        width: 10px;
        height: 10px;
        fill: none;
        stroke: white;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s ease;
    }

    .custom-checkbox input:checked + .checkmark svg {
        opacity: 1;
        transform: scale(1);
    }

    .custom-checkbox span:last-child {
        color: #666;
        font-size: 0.85rem;
    }

    .forgot-link {
        color: #DE0802;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .forgot-link:hover {
        color: #B3211A;
    }

    /* Submit Button */
    .btn-submit {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #DE0802 0%, #B3211A 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 0.95rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(222, 8, 2, 0.4);
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* Footer */
    .auth-form-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
    }

    .auth-form-footer p {
        color: #999;
        font-size: 0.8rem;
    }

    /* Error Messages */
    .error-message {
        color: #DE0802;
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .form-input.is-invalid {
        border-color: #DE0802;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .auth-decoration {
            display: none;
        }

        .auth-form-panel {
            flex: 1;
            padding: 2rem;
        }
    }

    @media (max-width: 576px) {
        .auth-form-panel {
            padding: 1.5rem;
        }

        .auth-welcome h1 {
            font-size: 1.25rem;
        }

        .decoration-title {
            font-size: 1.75rem;
        }

        .decoration-features {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <!-- Left Panel - Image Background -->
    <div class="auth-decoration">
        <!-- Accent Line -->
        <div class="decoration-accent"></div>
        
        <!-- Content Overlay -->
        <div class="decoration-content">
            <div class="decoration-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Móveis Planejados
            </div>
            <h1 class="decoration-title">Bassani Móveis</h1>
            <p class="decoration-subtitle">Excelência em móveis planejados para seu negócio</p>
            
            <div class="decoration-features">
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Qualidade garantida</span>
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Entrega no prazo</span>
                </div>
                <div class="feature-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Atendimento personalizado</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="auth-form-panel">
        <div class="auth-form-container">
            <!-- Logo -->
            <div class="auth-logo-container">
                <img src="{{ asset('assets/img/bassani.png') }}" alt="Bassani Móveis" class="auth-logo">
            </div>
            
            <!-- Welcome Text -->
            <div class="auth-welcome">
                <h1>Bem-vindo(a)!</h1>
                <p>Faça login para acessar o sistema</p>
            </div>

            <!-- Login Form -->
            <form id="formAuthentication" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email/Username Field -->
                <div class="form-group">
                    <span class="form-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    <input type="text" class="form-input @error('email-username') is-invalid @enderror" id="email" name="email-username" placeholder=" " autofocus value="{{ old('email-username') }}" />
                    <label class="form-label" for="email">Email ou Usuário</label>
                    @error('email-username')
                        <div class="error-message">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <span class="form-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </span>
                    <input type="password" class="form-input @error('password') is-invalid @enderror" id="password" name="password" placeholder=" " />
                    <label class="form-label" for="password">Senha</label>
                    @error('password')
                        <div class="error-message">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Options -->
                <div class="form-options">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="remember-me" />
                        <span class="checkmark">
                            <svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <span>Lembrar-me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu a senha?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Entrar
                </button>
            </form>
            
            <!-- Footer -->
            <div class="auth-form-footer">
                <p>&copy; {{ date('Y') }} Bassani Móveis. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>
</div>
@endsection
