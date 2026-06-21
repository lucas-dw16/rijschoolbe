<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Alle voertuigen</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht van alle voertuigen (wel en niet toegewezen).</p>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800">
                Terug naar Instructeurs
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 w-full rounded-none border border-emerald-700 bg-emerald-700 px-4 py-3 text-center text-lg font-semibold text-white">
                <div class="flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.31 7.31a1 1 0 01-1.414 0l-3.09-3.09a1 1 0 011.414-1.414l2.383 2.383 6.603-6.603a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-error" class="mb-4 w-full rounded-none border border-rose-700 bg-rose-700 px-4 py-3 text-center text-lg font-semibold text-white">
                <div class="flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3a1 1 0 002 0V7zm-1 7a1.25 1.25 0 110-2.5A1.25 1.25 0 0110 14z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>

            <script>
                setTimeout(function () {
                    const el = document.getElementById('flash-error');
                    if (!el) return;
                    el.style.transition = 'opacity 300ms ease';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 350);
                }, 3000);
            </script>
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
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Instructeur naam</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Wijzigen</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Verwijderen</th>
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
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">
                                    @if(! empty($voertuig->InstructeurNaam))
                                        {{ $voertuig->InstructeurNaam }}
                                    @elseif(! empty($voertuig->InstructeurId))
                                        Onbekend (ID: {{ $voertuig->InstructeurId }})
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-center dark:border-zinc-800">
                                    <a href="{{ route('voertuigen.edit', $voertuig->Id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-300 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" title="Wijzigen" wire:navigate>
                                        ✎
                                    </a>
                                </td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-center dark:border-zinc-800">
                                    <form action="{{ route('voertuigen.destroy', $voertuig->Id) }}" method="POST" onsubmit="return confirm('Weet u zeker dat u dit voertuig wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-300 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" title="Verwijderen">
                                            ✖
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen voertuigen gevonden.</td>
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
    @if (session('success'))
        <script>
            setTimeout(function () {
                window.location.href = "{{ route('voertuigen.index') }}";
            }, 3000);
        </script>
    @endif
</x-layouts::app>
