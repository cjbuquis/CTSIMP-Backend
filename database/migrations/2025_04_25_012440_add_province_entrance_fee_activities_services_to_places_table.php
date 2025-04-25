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
        Schema::table('places', function (Blueprint $table) {
            $table->string('province')->nullable();               // e.g. “Agusan del Norte”
            $table->decimal('entrance_fee', 8, 2)->default(0.00); // monetary, two decimals
            $table->json('activities')->nullable();              // list of activities
            $table->json('services')->nullable();                // list of services
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            //
        });
    }
};
