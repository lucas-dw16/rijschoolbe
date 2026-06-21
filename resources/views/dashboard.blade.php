<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Instructeurs in dienst</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Overzicht van alle instructeurs die in dienst zijn bij de autorijschool.</p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-700 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-200">
                Aantal instructeurs: <span class="font-semibold text-zinc-900 dark:text-white">{{ $aantalInstructeurs }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if (session('success'))
                <div id="dashboard-flash-success" class="border-b border-emerald-700 bg-emerald-700 px-4 py-3 text-center text-sm font-semibold text-white">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="border-b border-rose-700 bg-rose-700 px-4 py-3 text-center text-sm font-semibold text-white">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-zinc-100 dark:bg-zinc-800">
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Voornaam</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Tussenvoegsel</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Achternaam</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Mobiel</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Datum in dienst</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Aantal sterren</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Voertuigen</th>
                            <th class="border-b border-zinc-300 px-4 py-3 text-center text-sm font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">Ziekte/Verlof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructeurs as $instructeur)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $instructeur->Voornaam }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $instructeur->Tussenvoegsel }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $instructeur->Achternaam }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ $instructeur->Mobiel }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ \Illuminate\Support\Carbon::parse($instructeur->DatumInDienst)->format('d-m-Y') }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-200">{{ str_repeat('★', $instructeur->AantalSterren) }}</td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-center dark:border-zinc-800">
                                    <a href="{{ route('instructeurs.voertuigen', $instructeur->Id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-300 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" title="Voertuigen van {{ $instructeur->Voornaam }}" wire:navigate>
                                        🚗
                                    </a>
                                </td>
                                <td class="border-b border-zinc-200 px-4 py-3 text-center dark:border-zinc-800">
                                    <form action="{{ route('instructeurs.status', $instructeur->Id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-300 bg-white text-lg text-zinc-700 shadow-sm transition hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:bg-zinc-800" title="{{ $instructeur->IsActief ? 'Ziek/verlof melden' : 'Terug actief zetten' }}">
                                            {{ $instructeur->IsActief ? '👍' : '🩹' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">Geen instructeurs gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $instructeurs->links() }}
        </div>
    </div>

    @if (session('success'))
        <script>
            setTimeout(function () {
                const message = document.getElementById('dashboard-flash-success');
                if (message) {
                    message.remove();
                }
            }, 3000);
        </script>
    @endif
</x-layouts::app>
