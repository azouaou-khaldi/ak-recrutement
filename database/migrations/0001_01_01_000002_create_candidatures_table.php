<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // candidat
            $table->text('message')->nullable();
            $table->string('cv_path')->nullable();
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            $table->timestamps();

            $table->unique(['offre_id', 'user_id']); // un candidat ne postule qu'une fois par offre
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
