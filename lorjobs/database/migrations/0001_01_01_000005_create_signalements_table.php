<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signaleur_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['offre', 'utilisateur']);
            $table->foreignId('offre_id')->nullable()->constrained('offres')->onDelete('cascade');
            $table->foreignId('cible_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('raison');
            $table->enum('statut', ['en_attente', 'traite', 'ignore'])->default('en_attente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('signalements'); }
};
