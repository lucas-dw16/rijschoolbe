<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TypeVoertuig', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('TypeVoertuig', 100);
            $table->string('Rijbewijscategorie', 10);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt', 6);
            $table->dateTime('DatumGewijzigd', 6);
        });

        Schema::create('Instructeur', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Voornaam', 100);
            $table->string('Tussenvoegsel', 100)->nullable();
            $table->string('Achternaam', 100);
            $table->string('Mobiel', 20);
            $table->date('DatumInDienst');
            $table->unsignedTinyInteger('AantalSterren');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt', 6);
            $table->dateTime('DatumGewijzigd', 6);
        });

        Schema::create('Voertuig', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Kenteken', 20);
            $table->string('Type', 100);
            $table->date('Bouwjaar');
            $table->string('Brandstof', 20);
            $table->unsignedInteger('TypeVoertuigId');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt', 6);
            $table->dateTime('DatumGewijzigd', 6);

            $table->foreign('TypeVoertuigId')
                ->references('Id')
                ->on('TypeVoertuig')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::create('VoertuigInstructeur', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('VoertuigId');
            $table->unsignedInteger('InstructeurId');
            $table->date('DatumToekenning');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 250)->nullable();
            $table->dateTime('DatumAangemaakt', 6);
            $table->dateTime('DatumGewijzigd', 6);

            $table->foreign('VoertuigId')
                ->references('Id')
                ->on('Voertuig')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('InstructeurId')
                ->references('Id')
                ->on('Instructeur')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('VoertuigInstructeur');
        Schema::dropIfExists('Voertuig');
        Schema::dropIfExists('Instructeur');
        Schema::dropIfExists('TypeVoertuig');
    }
};