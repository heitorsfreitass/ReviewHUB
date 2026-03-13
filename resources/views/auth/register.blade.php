@extends('layouts.app')
@section('title', 'Criar conta')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5">

            <div class="text-center mb-4">
                <i class="bi bi-star-fill text-rh-accent" style="font-size:3rem;"></i>
                <h1 class="h3 fw-700 mt-2">Criar conta</h1>
                <p class="text-rh-muted fs-sm">
                    Já tem conta? <a href="{{ route('login') }}">Entrar</a>
                </p>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="rh-form-label">Nome</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            autofocus required
                            class="rh-form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                        @error('name') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="rh-form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Senha</label>
                        <input type="password" name="password" required
                            class="rh-form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        @error('password') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Confirmar senha</label>
                        <input type="password" name="password_confirmation" required
                            class="rh-form-control">
                    </div>

                    <button type="submit" class="btn-rh-primary w-100 justify-content-center py-2">
                        <i class="bi bi-person-check"></i> Criar conta
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection