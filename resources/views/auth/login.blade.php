@extends('layouts.app')
@section('title', 'Entrar')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <span class="text-5xl">⭐</span>
            <h1 class="text-2xl font-bold mt-3">Entrar no ReviewHub</h1>
            <p class="text-gray-500 text-sm mt-1">
                Não tem conta? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Cadastre-se grátis</a>
            </p>
        </div>

        <form action="{{ route('login') }}" method="POST"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" autofocus required
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                              {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" class="accent-indigo-600">
                    Lembrar de mim
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition">
                Entrar
            </button>

            <p class="text-center text-xs text-gray-400">
                Teste: admin@reviewhub.com / password
            </p>
        </form>
    </div>
</div>
@endsection
