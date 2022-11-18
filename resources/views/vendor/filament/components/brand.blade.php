@if (filled($brand = config('filament.brand')))
    <div @class([
        'filament-brand text-xl font-bold tracking-tight',
        'dark:text-white' => config('filament.dark_mode'),
    ])>
         {{-- {{ $brand }} --}}
    </div>
    <h1 alt="Logo" class="h-10 font-bold">Abigu Oil</h1>
@endif
