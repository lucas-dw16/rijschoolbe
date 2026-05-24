<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Voeg een nieuw testproduct toe.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('producten.store') }}" class="max-w-2xl rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="naam" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Naam</label>
                    <input type="text" id="naam" name="naam" value="{{ old('naam') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="beschrijving" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Beschrijving</label>
                    <input type="text" id="beschrijving" name="beschrijving" value="{{ old('beschrijving') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="prijs" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Prijs</label>
                    <input type="number" step="0.01" min="0" id="prijs" name="prijs" value="{{ old('prijs') }}" required class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Opslaan
                </button>
                <a href="{{ route('producten.index') }}" class="inline-flex items-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800" wire:navigate>
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>
