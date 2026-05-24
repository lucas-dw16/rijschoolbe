<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Door Instructeur gebruikte voertuigen</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $instructeur->Naam }} - alle voertuigen die aan deze instructeur zijn toegewezen.</p>
            </div>

            <a href="{{ route('instructeurs.beschikbare-voertuigen', $instructeur->Id) }}" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" wire:navigate>
                Toevoegen Voertuig
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

        <div class="overflow-hidden rounded-2xl border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-zinc-100 dark:bg-zinc-800">
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">TypeVoertuig</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Type</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Kenteken</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Bouwjaar</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Brandstof</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Rijbewijscategorie</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Wijzigen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($voertuigen as $voertuig)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $voertuig->TypeVoertuig }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $voertuig->Type }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $voertuig->Kenteken }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ \Illuminate\Support\Carbon::parse($voertuig->Bouwjaar)->format('d-m-Y') }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $voertuig->Brandstof }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $voertuig->Rijbewijscategorie }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-center dark:border-zinc-800">
                                    <a href="{{ route('voertuigen.edit', $voertuig->Id) }}?context_instructeur_id={{ $instructeur->Id }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-300 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" title="Wijzigen" wire:navigate>
                                        ✎
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen voertuigen toegewezen aan {{ $instructeur->Naam }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $voertuigen->links() }}
        </div>
    </div>
</x-layouts::app>
