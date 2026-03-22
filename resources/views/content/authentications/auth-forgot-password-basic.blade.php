@extends('layouts/blankLayout')

@section('title', 'Recuperar Senha - Bassani Móveis')

@section('page-style')
<style>
    /* Forgot Password Page Custom Styles */
    .auth-forgot-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    
    .auth-forgot-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
        pointer-events: none;
    }
    
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        max-width: 420px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .auth-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        text-align: center;
        color: white;
        position: relative;
    }
    
    .auth-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        right: 0;
        height: 40px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 50% 50% 0 0;
    }
    
    .auth-logo {
        height: 80px;
        margin-bottom: 1rem;
        filter: brightness(0) invert(1);
    }
    
    .auth-body {
        padding: 2rem;
    }
    
    .form-floating-custom {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-floating-custom input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-floating-custom input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }
    
    .form-floating-custom label {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
        transition: all 0.3s ease;
        background: white;
        padding: 0 0.5rem;
    }
    
    .form-floating-custom input:focus + label,
    .form-floating-custom input:not(:placeholder-shown) + label {
        top: 0;
        font-size: 0.75rem;
        color: #667eea;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    
    .auth-footer {
        text-align: center;
        padding: 1.5rem 2rem;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }
    
    .auth-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .auth-link:hover {
        color: #764ba2;
    }
    
    .auth-link i {
        margin-right: 0.5rem;
    }
    
    .furniture-pattern {
        position: absolute;
        bottom: 20px;
        right: 20px;
        opacity: 0.1;
        font-size: 120px;
        color: white;
        z-index: 0;
    }
    
    .brand-tagline {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-top: 0.5rem;
        font-weight: 300;
    }
    
    .icon-lock {
        font-size: 48px;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    
    @media (max-width: 768px) {
        .auth-card {
            margin: 1rem;
        }
        
        .furniture-pattern {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-forgot-page">
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header with Logo -->
            <div class="auth-header">
                <img src="{{ asset('assets/img/bassani.png') }}" alt="Bassani Móveis" class="auth-logo">
                <h2 class="mb-0">Recuperar Senha</h2>
                <p class="brand-tagline">Móveis Planejados</p>
            </div>
            
            <!-- Body with Form -->
            <div class="auth-body">
                <div class="text-center mb-4">
                    <i class="bx bx-lock-open icon-lock text-primary"></i>
                    <h4 class="mb-2">Esqueceu sua senha?</h4>
                    <p class="text-muted">Informe seu email para receber instruções de recuperação</p>
                </div>

                <form id="formAuthentication" action="{{ url('/') }}" method="GET">
                    <div class="form-floating-custom">
                        <input type="text" class="form-control" id="email" name="email" placeholder=" " autofocus />
                        <label for="email">Seu endereço de email</label>
                    </div>
                    
                    <button type="submit" class="btn-primary-custom mb-4">
                        Enviar Link de Recuperação
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="auth-footer">
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="bx bx-chevron-left"></i>
                    Voltar para o login
                </a>
                <div class="mt-3">
                    <small class="text-muted">
                        © {{ date('Y') }} Bassani Móveis. Todos os direitos reservados.
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Element -->
    <div class="furniture-pattern">
        <i class="bx bx-home"></i>
    </div>
</div>
@endsection
