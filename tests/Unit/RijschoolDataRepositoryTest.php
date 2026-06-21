<?php

use App\Support\RijschoolDataRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed();
});

test('deactivating an instructor releases their active vehicle assignments', function () {
    $repository = app(RijschoolDataRepository::class);

    $updated = $repository->toggleInstructeurActief(5);

    expect($updated)->not->toBeNull();
    expect((int) $updated->IsActief)->toBe(0);
    expect(DB::table('VoertuigInstructeur')->where('InstructeurId', 5)->where('IsActief', 1)->count())->toBe(0);
    expect($repository->paginateVoertuigenVanInstructeur(5)->total())->toBe(0);
});

test('reassigning a vehicle creates history instead of overwriting the old assignment', function () {
    $repository = app(RijschoolDataRepository::class);

    $repository->assignVoertuigToInstructeur(1, 4);

    expect(DB::table('VoertuigInstructeur')->where('VoertuigId', 1)->where('InstructeurId', 5)->count())->toBeGreaterThan(0);
    expect(DB::table('VoertuigInstructeur')->where('VoertuigId', 1)->where('InstructeurId', 4)->where('IsActief', 1)->count())->toBe(1);
    expect(DB::table('VoertuigInstructeur')->where('VoertuigId', 1)->where('InstructeurId', 5)->where('IsActief', 0)->count())->toBe(1);
});

test('reactivating an instructor restores previously assigned vehicles when still free', function () {
    $repository = app(RijschoolDataRepository::class);

    // initial count of active assignments for instructeur 5
    $initial = DB::table('VoertuigInstructeur')->where('InstructeurId', 5)->where('IsActief', 1)->count();
    expect($initial)->toBeGreaterThanOrEqual(0);

    // deactivate
    $updated = $repository->toggleInstructeurActief(5);
    expect((int) $updated->IsActief)->toBe(0);
    expect($repository->paginateVoertuigenVanInstructeur(5)->total())->toBe(0);

    // reactivate
    $updated2 = $repository->toggleInstructeurActief(5);
    expect((int) $updated2->IsActief)->toBe(1);

    // previously assigned vehicles should be visible again
    expect($repository->paginateVoertuigenVanInstructeur(5)->total())->toBe($initial);

    // those vehicles should not be in the available list
    $voertuigIds = DB::table('VoertuigInstructeur')->where('InstructeurId', 5)->where('IsActief', 1)->pluck('VoertuigId')->all();
    foreach ($voertuigIds as $vid) {
        expect(DB::table('Voertuig as v')
            ->leftJoin('VoertuigInstructeur as vi', function ($join) use ($vid) {
                $join->on('vi.VoertuigId', '=', 'v.Id')->where('vi.IsActief', 1);
            })
            ->where('v.Id', $vid)
            ->whereNull('vi.Id')
            ->exists())->toBeFalse();
    }
});

test('a vehicle reassigned during absence stays visible with a different current instructor', function () {
    $repository = app(RijschoolDataRepository::class);

    $repository->toggleInstructeurActief(5);
    $repository->assignVoertuigToInstructeur(1, 4);
    $repository->toggleInstructeurActief(5);

    $voertuigen = $repository->paginateVoertuigenVanInstructeur(5);
    $voertuig = $voertuigen->getCollection()->firstWhere('Id', 1);

    expect($voertuig)->not->toBeNull();
    expect((int) $voertuig->HuidigeInstructeurId)->toBe(4);
});

test('all vehicles shows the active instructors name for assigned vehicles', function () {
    $repository = app(RijschoolDataRepository::class);

    $repository->assignVoertuigToInstructeur(1, 4);

    $voertuig = $repository->paginateAllVoertuigen()->getCollection()->firstWhere('Id', 1);

    expect($voertuig)->not->toBeNull();
    expect((int) $voertuig->InstructeurId)->toBe(4);
    expect($voertuig->InstructeurNaam)->not->toBeEmpty();
});