<x-guest-layout>
    
        <!-- Cabeçalho -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-600">Gerenciador de Tarefas</h1>
            <p class="text-sm text-gray-500">Faça o login ou crie sua conta para acessar.</p>
        </div>

        <!-- Status da Sessão -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Endereço de Email -->
            <div>
                <x-input-label for="email" :value="'E-mail'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div class="mt-4">
                <x-input-label for="password" :value="'Senha'" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Lembrar-me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Lembrar-me</span>
                </label>
            </div>

            <!-- Ações -->
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        Esqueceu a senha?
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    Entrar
                </x-primary-button>
            </div>
        </form>

        <!-- Link para registro -->
        <div class="text-center mt-6">
            <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">
                Não tem uma conta? Cadastre-se aqui
            </a>
        </div>
   
</x-guest-layout>
