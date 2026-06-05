<?php

use App\Support\RijschoolDataRepository;

it('formats a full name with tussenvoegsel', function () {
    $repository = new RijschoolDataRepository();

    expect($repository->formatNaam('Mohammed', 'El', 'Yassidi'))
        ->toBe('Mohammed El Yassidi');
});

it('formats a name without tussenvoegsel', function () {
    $repository = new RijschoolDataRepository();

    expect($repository->formatNaam('Bert', '', 'Sali'))
        ->toBe('Bert Sali');
});