<?php

namespace App\Http\Controllers;

use App\Support\RijschoolDataRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InstructeurVoertuigController extends Controller
{
    public function __construct(private readonly RijschoolDataRepository $repository)
    {
    }

    public function index(): View
    {
        $instructeurs = $this->repository->paginateInstructeurs(4);

        return view('dashboard', [
            'title' => 'Instructeurs in dienst',
            'instructeurs' => $instructeurs,
            'aantalInstructeurs' => $instructeurs->total(),
        ]);
    }

    public function show(int $instructeur): View
    {
        $instructeurModel = $this->repository->getInstructeur($instructeur);
        abort_if(! $instructeurModel, 404);

        return view('instructeurs.voertuigen', [
            'title' => 'Door Instructeur gebruikte voertuigen',
            'instructeur' => $instructeurModel,
            'voertuigen' => $this->repository->paginateVoertuigenVanInstructeur($instructeur, 4),
        ]);
    }

    public function available(int $instructeur): View
    {
        $instructeurModel = $this->repository->getInstructeur($instructeur);
        abort_if(! $instructeurModel, 404);

        return view('instructeurs.beschikbare-voertuigen', [
            'title' => 'Alle beschikbare voertuigen',
            'instructeur' => $instructeurModel,
            'voertuigen' => $this->repository->paginateBeschikbareVoertuigen(4),
        ]);
    }

    public function assign(Request $request, int $instructeur, int $voertuig): RedirectResponse
    {
        try {
            $voertuigModel = $this->repository->assignVoertuigToInstructeur($voertuig, $instructeur);

            abort_if(! $voertuigModel, 404);

            return redirect()
                ->route('instructeurs.voertuigen', $instructeur)
                ->with('success', 'Voertuig succesvol toegewezen.');
        } catch (\Throwable $exception) {
            Log::error('Voertuig toewijzen mislukt', [
                'instructeur_id' => $instructeur,
                'voertuig_id' => $voertuig,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Voertuig kon niet worden toegewezen.');
        }
    }

    public function edit(Request $request, int $voertuig): View
    {
        $voertuigModel = $this->repository->getVoertuig($voertuig);
        abort_if(! $voertuigModel, 404);

        return view('instructeurs.wijzigen-voertuig', [
            'title' => 'Wijzigen voertuiggegevens',
            'voertuig' => $voertuigModel,
            'instructeurs' => $this->repository->allInstructeurs(),
            'typeVoertuigen' => $this->repository->getTypeVoertuigen(),
            'contextInstructeurId' => $request->integer('context_instructeur_id') ?: $voertuigModel->InstructeurId,
            'origineleInstructeurId' => $this->repository->getOriginalInstructeurId($voertuig),
        ]);
    }

    public function update(Request $request, int $voertuig): RedirectResponse
    {
        $data = $request->validate([
            'InstructeurId' => ['nullable', 'integer'],
            'TypeVoertuigId' => ['required', 'integer'],
            'Type' => ['required', 'string', 'max:100'],
            'Kenteken' => ['required', 'string', 'max:20'],
            'Brandstof' => ['required', 'in:Diesel,Benzine,Elektrisch'],
            'Bouwjaar' => ['nullable', 'date'],
        ]);

        try {
            $voertuigModel = $this->repository->updateVoertuig($voertuig, $data);
            abort_if(! $voertuigModel, 404);

            $redirectInstructeurId = $this->resolveRedirectInstructeurId($voertuig, $request, $data['InstructeurId'] ?? null);

            return $redirectInstructeurId
                ? redirect()->route('instructeurs.voertuigen', $redirectInstructeurId)->with('success', 'Voertuig succesvol gewijzigd.')
                : redirect()->route('dashboard')->with('success', 'Voertuig succesvol gewijzigd.');
        } catch (\Throwable $exception) {
            Log::error('Voertuig wijzigen mislukt', [
                'voertuig_id' => $voertuig,
                'message' => $exception->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Voertuig kon niet worden gewijzigd.');
        }
    }

    private function resolveRedirectInstructeurId(int $voertuigId, Request $request, mixed $submittedInstructeurId): ?int
    {
        $contextInstructeurId = $request->integer('context_instructeur_id') ?: null;
        $originalInstructeurId = $this->repository->getOriginalInstructeurId($voertuigId);

        if ($contextInstructeurId) {
            return $contextInstructeurId;
        }

        if ($submittedInstructeurId !== null && $submittedInstructeurId !== '') {
            return (int) $submittedInstructeurId;
        }

        return $originalInstructeurId;
    }
}
