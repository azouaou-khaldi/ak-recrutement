<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('entreprise')->nullable();
            $table->string('secteur')->nullable();
            $table->string('taille_entreprise')->nullable();
            $table->text('description_entreprise')->nullable();
            $table->string('site_web')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['entreprise','secteur','taille_entreprise','description_entreprise','site_web']);
        });
    }
};
