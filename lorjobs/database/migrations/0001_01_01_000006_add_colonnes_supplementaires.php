<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('suspendu')->default(false);
        });
        Schema::table('offres', function (Blueprint $table) {
            $table->boolean('approuvee')->default(true);
        });
        Schema::table('contacts', function (Blueprint $table) {
            $table->boolean('lu')->default(false);
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('suspendu'); });
        Schema::table('offres', function (Blueprint $table) { $table->dropColumn('approuvee'); });
        Schema::table('contacts', function (Blueprint $table) { $table->dropColumn('lu'); });
    }
};
