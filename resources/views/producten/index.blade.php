<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $title }}</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht van alle testproducten.</p>
            </div>

            <a href="{{ route('producten.create') }}" class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200" wire:navigate>
                Nieuw product
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Naam</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Beschrijving</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-zinc-600 dark:text-zinc-300">Prijs</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-zinc-600 dark:text-zinc-300">Wijzig</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-zinc-600 dark:text-zinc-300">Verwijder</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @forelse ($producten as $product)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">{{ $product->Naam }}</td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-300">{{ $product->Beschrijving }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">EUR {{ number_format($product->Prijs, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('producten.edit', $product->Id) }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-emerald-500" wire:navigate>
                                        Wijzig
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('producten.destroy', $product->Id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-rose-500">
                                            Verwijder
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen producten beschikbaar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $producten->links() }}
        </div>
    </div>
</x-layouts::app>
