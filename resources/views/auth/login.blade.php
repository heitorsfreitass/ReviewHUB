@extends('layouts.app')
@section('title', 'Entrar')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5">

            <div class="text-center mb-4">
                <i class="bi bi-star-fill text-rh-accent" style="font-size:3rem;"></i>
                <h1 class="h3 fw-700 mt-2">Entrar no ReviewHub</h1>
                <p class="text-rh-muted fs-sm">
                    Não tem conta?
                    <a href="{{ route('register') }}">Cadastre-se grátis</a>
                </p>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="rh-form-label">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            autofocus required
                            class="rh-form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Senha</label>
                        <input type="password" name="password" required
                            class="rh-form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        @error('password') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label fs-sm" for="remember">Lembrar de mim</label>
                        </div>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="fs-sm">Esqueceu a senha?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-rh-primary w-100 justify-content-center py-2 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>

                    <p class="text-center fs-xs text-rh-muted mb-0">
                        Teste: <code>admin@reviewhub.com</code> / <code>password</code>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection