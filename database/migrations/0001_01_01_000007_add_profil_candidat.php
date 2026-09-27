<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('titre_poste')->nullable();
            $table->string('telephone')->nullable();
            $table->string('ville')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('portfolio')->nullable();
            $table->string('disponibilite')->nullable();
            $table->string('experience')->nullable();
            $table->text('a_propos')->nullable();
            $table->text('competences')->nullable();
            $table->string('cv_path')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['titre_poste','telephone','ville','linkedin','portfolio','disponibilite','experience','a_propos','competences','cv_path']);
        });
    }
};
