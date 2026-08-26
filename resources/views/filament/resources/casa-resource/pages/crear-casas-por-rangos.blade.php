<x-filament-panels::page>
    <form wire:submit="crear">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Crear casas
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>