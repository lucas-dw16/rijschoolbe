<x-layouts::app :title="$title">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 sm:p-6 lg:p-8">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Wijzigen voertuiggegevens</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Alle gegevens zijn vooraf ingevuld. Bouwjaar is readonly.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-200">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('voertuigen.update', $voertuig->Id) }}" class="max-w-4xl rounded-2xl border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @method('PUT')

            <input type="hidden" name="context_instructeur_id" value="{{ $contextInstructeurId }}">

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="InstructeurId" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Instructeur</label>
                    <select id="InstructeurId" name="InstructeurId" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        <option value="">Geen instructeur</option>
                        @foreach ($instructeurs as $instructeur)
                            <option value="{{ $instructeur->Id }}" @selected(old('InstructeurId', $voertuig->InstructeurId) == $instructeur->Id)>{{ $instructeur->Naam }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="TypeVoertuigId" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Type Voertuig</label>
                    <select id="TypeVoertuigId" name="TypeVoertuigId" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                        @foreach ($typeVoertuigen as $typeVoertuig)
                            <option value="{{ $typeVoertuig->Id }}" @selected(old('TypeVoertuigId', $voertuig->TypeVoertuigId) == $typeVoertuig->Id)>{{ $typeVoertuig->TypeVoertuig }} - {{ $typeVoertuig->Rijbewijscategorie }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="Type" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Type</label>
                    <input type="text" id="Type" name="Type" value="{{ old('Type', $voertuig->Type) }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>

                <div>
                    <label for="Bouwjaar" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Bouwjaar</label>
                    <input type="date" id="Bouwjaar" name="Bouwjaar" value="{{ old('Bouwjaar', \Illuminate\Support\Carbon::parse($voertuig->Bouwjaar)->format('Y-m-d')) }}" readonly class="block w-full rounded-xl border-zinc-300 bg-zinc-100 px-4 py-2.5 text-zinc-500 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Brandstof</label>
                    <div class="flex flex-wrap gap-4 rounded-xl border border-zinc-300 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-950">
                        @foreach (['Diesel', 'Benzine', 'Elektrisch'] as $brandstof)
                            <label class="inline-flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-200">
                                <input type="radio" name="Brandstof" value="{{ $brandstof }}" @checked(old('Brandstof', $voertuig->Brandstof) === $brandstof) class="border-zinc-300 text-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950">
                                {{ $brandstof }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="Kenteken" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Kenteken</label>
                    <input type="text" id="Kenteken" name="Kenteken" value="{{ old('Kenteken', $voertuig->Kenteken) }}" class="block w-full rounded-xl border-zinc-300 bg-white px-4 py-2.5 text-zinc-900 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100">
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Wijzig
                </button>

                <a href="{{ url()->previous() ?: route('instructeurs.voertuigen', $contextInstructeurId) }}" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800" wire:navigate>
                    Annuleren
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>
