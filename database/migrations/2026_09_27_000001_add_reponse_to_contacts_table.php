<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Réponse de l'admin à un message de contact (envoyée par e-mail à l'expéditeur)
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('reponse')->nullable();
            $table->timestamp('repondu_le')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['reponse', 'repondu_le']);
        });
    }
};
