<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RijschoolDataRepository
{
    private const PER_PAGE = 4;

    public function allInstructeurs(): Collection
    {
        return DB::table('Instructeur')
            ->where('IsActief', 1)
            ->orderByDesc('AantalSterren')
            ->orderBy('Voornaam')
            ->get()
            ->map(fn (object $instructeur): object => $this->hydrateInstructeur($instructeur))
            ->values();
    }

    public function paginateInstructeurs(int $perPage = self::PER_PAGE): LengthAwarePaginator
    {
        return $this->paginateCollection($this->allInstructeurs(), $perPage, 'page');
    }

    public function getInstructeur(int $instructeurId): ?object
    {
        $instructeur = DB::table('Instructeur')
            ->where('Id', $instructeurId)
            ->where('IsActief', 1)
            ->first();

        return $instructeur ? $this->hydrateInstructeur($instructeur) : null;
    }

    public function getTypeVoertuigen(): Collection
    {
        return DB::table('TypeVoertuig')
            ->where('IsActief', 1)
            ->orderBy('Rijbewijscategorie')
            ->orderBy('TypeVoertuig')
            ->get()
            ->values();
    }

    public function getVoertuig(int $voertuigId): ?object
    {
        $voertuig = DB::table('Voertuig as v')
            ->leftJoin('TypeVoertuig as tv', 'tv.Id', '=', 'v.TypeVoertuigId')
            ->leftJoin('VoertuigInstructeur as vi', function ($join): void {
                $join->on('vi.VoertuigId', '=', 'v.Id')
                    ->where('vi.IsActief', '=', 1);
            })
            ->leftJoin('Instructeur as i', 'i.Id', '=', 'vi.InstructeurId')
            ->where('v.Id', $voertuigId)
            ->where('v.IsActief', 1)
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'v.TypeVoertuigId',
                'v.IsActief',
                'v.Opmerking',
                'v.DatumAangemaakt',
                'v.DatumGewijzigd',
                'tv.TypeVoertuig',
                'tv.Rijbewijscategorie',
                'vi.InstructeurId',
                DB::raw("CONCAT_WS(' ', i.Voornaam, NULLIF(i.Tussenvoegsel, ''), i.Achternaam) AS InstructeurNaam"),
            ])
            ->first();

        return $voertuig ? $this->hydrateVoertuig($voertuig) : null;
    }

    public function paginateVoertuigenVanInstructeur(int $instructeurId, int $perPage = self::PER_PAGE): LengthAwarePaginator
    {
        $voertuigen = DB::table('Voertuig as v')
            ->join('VoertuigInstructeur as vi', function ($join) use ($instructeurId): void {
                $join->on('vi.VoertuigId', '=', 'v.Id')
                    ->where('vi.IsActief', '=', 1)
                    ->where('vi.InstructeurId', '=', $instructeurId);
            })
            ->join('TypeVoertuig as tv', 'tv.Id', '=', 'v.TypeVoertuigId')
            ->where('v.IsActief', 1)
            ->orderBy('tv.Rijbewijscategorie')
            ->orderBy('v.Type')
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'v.TypeVoertuigId',
                'v.IsActief',
                'v.Opmerking',
                'v.DatumAangemaakt',
                'v.DatumGewijzigd',
                'tv.TypeVoertuig',
                'tv.Rijbewijscategorie',
                'vi.InstructeurId',
            ])
            ->get()
            ->map(fn (object $voertuig): object => $this->hydrateVoertuig($voertuig))
            ->values();

        return $this->paginateCollection($voertuigen, $perPage, 'page');
    }

    public function paginateBeschikbareVoertuigen(int $perPage = self::PER_PAGE): LengthAwarePaginator
    {
        $voertuigen = DB::table('Voertuig as v')
            ->leftJoin('VoertuigInstructeur as vi', function ($join): void {
                $join->on('vi.VoertuigId', '=', 'v.Id')
                    ->where('vi.IsActief', '=', 1);
            })
            ->join('TypeVoertuig as tv', 'tv.Id', '=', 'v.TypeVoertuigId')
            ->whereNull('vi.Id')
            ->where('v.IsActief', 1)
            ->orderBy('tv.Rijbewijscategorie')
            ->orderBy('v.Type')
            ->select([
                'v.Id',
                'v.Kenteken',
                'v.Type',
                'v.Bouwjaar',
                'v.Brandstof',
                'v.TypeVoertuigId',
                'v.IsActief',
                'v.Opmerking',
                'v.DatumAangemaakt',
                'v.DatumGewijzigd',
                'tv.TypeVoertuig',
                'tv.Rijbewijscategorie',
            ])
            ->get()
            ->map(fn (object $voertuig): object => $this->hydrateVoertuig($voertuig))
            ->values();

        return $this->paginateCollection($voertuigen, $perPage, 'page');
    }

    public function assignVoertuigToInstructeur(int $voertuigId, int $instructeurId): ?object
    {
        return DB::transaction(function () use ($voertuigId, $instructeurId): ?object {
            $voertuig = DB::table('Voertuig')
                ->where('Id', $voertuigId)
                ->where('IsActief', 1)
                ->lockForUpdate()
                ->first();

            if (! $voertuig) {
                return null;
            }

            $assignment = DB::table('VoertuigInstructeur')
                ->where('VoertuigId', $voertuigId)
                ->where('IsActief', 1)
                ->lockForUpdate()
                ->first();

            $now = $this->now();

            if ($assignment === null) {
                DB::table('VoertuigInstructeur')->insert([
                    'VoertuigId' => $voertuigId,
                    'InstructeurId' => $instructeurId,
                    'DatumToekenning' => $now->format('Y-m-d'),
                    'IsActief' => 1,
                    'Opmerking' => null,
                    'DatumAangemaakt' => $now->format('Y-m-d H:i:s.u'),
                    'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                ]);
            } elseif ((int) $assignment->InstructeurId !== $instructeurId) {
                DB::table('VoertuigInstructeur')
                    ->where('Id', $assignment->Id)
                    ->update([
                        'InstructeurId' => $instructeurId,
                        'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                    ]);
            }

            return $this->getVoertuig($voertuigId);
        });
    }

    public function updateVoertuig(int $voertuigId, array $payload): ?object
    {
        return DB::transaction(function () use ($voertuigId, $payload): ?object {
            $voertuig = DB::table('Voertuig')
                ->where('Id', $voertuigId)
                ->where('IsActief', 1)
                ->lockForUpdate()
                ->first();

            if (! $voertuig) {
                return null;
            }

            $originalInstructeurId = $this->voertuigInstructeurId($voertuigId);
            $selectedInstructeurId = array_key_exists('InstructeurId', $payload) && $payload['InstructeurId'] !== null && $payload['InstructeurId'] !== ''
                ? (int) $payload['InstructeurId']
                : null;

            if ($selectedInstructeurId === null && $originalInstructeurId !== null) {
                $selectedInstructeurId = $originalInstructeurId;
            }

            $now = $this->now();

            DB::table('Voertuig')
                ->where('Id', $voertuigId)
                ->update([
                    'TypeVoertuigId' => (int) $payload['TypeVoertuigId'],
                    'Type' => (string) $payload['Type'],
                    'Kenteken' => (string) $payload['Kenteken'],
                    'Brandstof' => (string) $payload['Brandstof'],
                    'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                ]);

            if ($selectedInstructeurId !== $originalInstructeurId) {
                $assignment = DB::table('VoertuigInstructeur')
                    ->where('VoertuigId', $voertuigId)
                    ->where('IsActief', 1)
                    ->lockForUpdate()
                    ->first();

                if ($selectedInstructeurId === null) {
                    if ($assignment !== null) {
                        DB::table('VoertuigInstructeur')
                            ->where('Id', $assignment->Id)
                            ->update([
                                'IsActief' => 0,
                                'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                            ]);
                    }
                } elseif ($assignment === null) {
                    DB::table('VoertuigInstructeur')->insert([
                        'VoertuigId' => $voertuigId,
                        'InstructeurId' => $selectedInstructeurId,
                        'DatumToekenning' => $now->format('Y-m-d'),
                        'IsActief' => 1,
                        'Opmerking' => null,
                        'DatumAangemaakt' => $now->format('Y-m-d H:i:s.u'),
                        'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                    ]);
                } else {
                    DB::table('VoertuigInstructeur')
                        ->where('Id', $assignment->Id)
                        ->update([
                            'InstructeurId' => $selectedInstructeurId,
                            'DatumGewijzigd' => $now->format('Y-m-d H:i:s.u'),
                        ]);
                }
            }

            return $this->getVoertuig($voertuigId);
        });
    }

    public function getOriginalInstructeurId(int $voertuigId): ?int
    {
        return $this->voertuigInstructeurId($voertuigId);
    }

    private function hydrateInstructeur(object $instructeur): object
    {
        $instructeur->Naam = $this->formatNaam(
            (string) $instructeur->Voornaam,
            $instructeur->Tussenvoegsel,
            (string) $instructeur->Achternaam
        );

        $instructeur->VoertuigenCount = DB::table('VoertuigInstructeur')
            ->where('InstructeurId', (int) $instructeur->Id)
            ->where('IsActief', 1)
            ->count();

        return $instructeur;
    }

    private function hydrateVoertuig(object $voertuig): object
    {
        if ((int) ($voertuig->InstructeurId ?? 0) === 0) {
            $voertuig->InstructeurNaam = null;

            return $voertuig;
        }

        if (! property_exists($voertuig, 'InstructeurNaam') || $voertuig->InstructeurNaam === '') {
            $voertuig->InstructeurNaam = $this->instructeurNaamById((int) $voertuig->InstructeurId);
        }

        return $voertuig;
    }

    private function voertuigInstructeurId(int $voertuigId): ?int
    {
        $assignment = DB::table('VoertuigInstructeur')
            ->where('VoertuigId', $voertuigId)
            ->where('IsActief', 1)
            ->first();

        return $assignment ? (int) $assignment->InstructeurId : null;
    }

    private function instructeurNaamById(int $instructeurId): ?string
    {
        $instructeur = DB::table('Instructeur')
            ->where('Id', $instructeurId)
            ->where('IsActief', 1)
            ->first();

        return $instructeur
            ? $this->formatNaam((string) $instructeur->Voornaam, $instructeur->Tussenvoegsel, (string) $instructeur->Achternaam)
            : null;
    }

    private function formatNaam(string $voornaam, ?string $tussenvoegsel, string $achternaam): string
    {
        return trim(implode(' ', array_filter([$voornaam, $tussenvoegsel, $achternaam], fn (?string $part): bool => filled($part))));
    }

    private function paginateCollection(Collection $items, int $perPage, string $pageName): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $total = $items->count();
        $results = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    private function now(): CarbonImmutable
    {
        return CarbonImmutable::now();
    }
}