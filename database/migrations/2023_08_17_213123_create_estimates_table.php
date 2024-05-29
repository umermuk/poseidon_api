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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_type_id')->nullable();
            $table->unsignedBigInteger('steep_roof_id')->nullable();
            $table->unsignedBigInteger('currently_roof_id')->nullable();
            $table->unsignedBigInteger('installed_roof_id')->nullable();
            $table->string('when_start')->nullable();
            $table->string('interested_financing')->nullable();
            $table->mediumText('address')->nullable();
            $table->string('roof_size')->nullable();
            $table->mediumText('about')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->foreign('building_type_id')->references('id')->on('building_types')->onDelete('CASCADE');
            $table->foreign('steep_roof_id')->references('id')->on('steep_roofs')->onDelete('CASCADE');
            $table->foreign('currently_roof_id')->references('id')->on('roofs')->onDelete('CASCADE');
            $table->foreign('installed_roof_id')->references('id')->on('roofs')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
