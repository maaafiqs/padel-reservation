<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->enum('type', ['percentage', 'nominal'])->default('percentage')->after('code');
            $table->decimal('nominal_amount', 12, 2)->nullable()->after('percentage');
            $table->decimal('percentage', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['type', 'nominal_amount']);
            $table->decimal('percentage', 5, 2)->nullable(false)->change();
        });
    }
};
