<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the place
            $table->string('place_name'); // Additional place name
            $table->text('address'); // Address (can be long)
            $table->string('email_address')->nullable(); // Email address (optional)
            $table->string('contact_no')->nullable(); // Contact number (optional)
            $table->text('description')->nullable(); // Description of the place
            $table->text('virtual_iframe')->nullable(); // Virtual iframe (e.g., for virtual tours)
            $table->text('map_iframe')->nullable(); // Map iframe (e.g., for embedded maps)
            $table->string('image_link')->nullable(); // Image link for the place
            $table->timestamps(); // Created and updated timestamps
            $table->string('entrance')->nullable(); // Entrance information (optional)
            $table->string('room_or_cottages_price')->nullable(); // Price information (optional)   
            $table->string('history')->nullable(); // History of the place (optional)
            $table->string('activities')->nullable(); // Activities available at the place (optional)
            $table->string('reason_for_rejection')->nullable(); // Reason for rejection (optional)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
