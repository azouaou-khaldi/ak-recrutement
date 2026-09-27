<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // recruteur
            $table->string('titre');
            $table->string('entreprise');
            $table->string('lieu');
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Alternance', 'Freelance']);
            $table->text('description');
            $table->text('competences_requises')->nullable();
            $table->string('salaire')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
