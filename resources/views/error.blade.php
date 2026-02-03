<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title : __('laravel-exceptions::messages.title') }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vendor/juniorfontenele/laravel-exceptions/css/app.css') }}">
</head>

<body>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <!-- Ícone de erro -->
                    <div class="mb-1 flex h-9 w-9 items-center justify-center">
                        <svg class="size-9 text-foreground" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <div class="space-y-2 text-center">
                        <!-- Mensagem de erro -->
                        <h1 class="text-xl font-medium">
                            {{ $message ?? __('laravel-exceptions::messages.message') }}
                        </h1>

                        <!-- Descrição adicional -->
                        <p class="text-center text-sm text-muted-foreground">
                            {{ __('laravel-exceptions::messages.apology') }}
                        </p>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ __('laravel-exceptions::messages.error_code_info') }}
                        </p>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button onclick="window.history.back()"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('laravel-exceptions::messages.back') }}
                    </button>

                    <a href="{{ '/' }}"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('laravel-exceptions::messages.home') }}
                    </a>
                </div>

                <!-- Link de suporte adicional -->
                <div class="space-y-2 border-t border-border pt-6 text-center">
                    {{-- <p class="text-sm text-muted-foreground">
                        {{ __('laravel-exceptions::messages.need_help') }}
                        <a href="mailto:{{ config('app.support_contact_email') }}?subject=Suporte%20-%20Erro%20{{ $errorId ?? '' }}&body=Código%20do%20Erro:%20{{ $errorId ?? 'N/A' }}%0D%0AMensagem:%20{{ $message ?? 'N/A' }}%0D%0A"
                            class="text-primary hover:underline">
                            {{ __('laravel-exceptions::messages.contact_support') }}
                        </a>
                    </p> --}}

                    @isset($errorId)
                        <p class="text-sm text-foreground">
                            {{ __('laravel-exceptions::messages.error_code', ['code' => $errorId]) }}
                        </p>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <!-- Script para melhorar a experiência de navegação -->
    <script>
        // Verifica se há histórico de navegação disponível
        if (window.history.length <= 1) {
            // Se não há histórico, esconde o botão voltar e ajusta o layout
            const backButton = document.querySelector('button[onclick="window.history.back()"]');
            if (backButton) {
                backButton.style.display = 'none';
            }
        }

        // Adiciona funcionalidade de teclado para melhor acessibilidade
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</body>

</html>
