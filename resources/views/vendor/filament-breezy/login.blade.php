<x-filament-breezy::auth-card action="authenticate">

    <div class="w-full flex justify-center">
        <x-filament::brand />
    </div>

    <div>
        <h2 class="font-bold tracking-tight text-center text-2xl">
            {{ __('filament::login.heading') }}
        </h2>
        @if (config('filament-breezy.enable_registration'))
            <p class="mt-2 text-sm text-center">
                {{ __('filament-breezy::default.or') }}
                <a class="text-primary-600" href="{{ route(config('filament-breezy.route_group_prefix') . 'register') }}">
                    {{ strtolower(__('filament-breezy::default.registration.heading')) }}
                </a>
            </p>
        @endif
    </div>

    {{ $this->form }}

    <x-filament::button type="submit" class="w-full">
        {{ __('filament::login.buttons.submit.label') }}
    </x-filament::button>
</x-filament-breezy::auth-card>
