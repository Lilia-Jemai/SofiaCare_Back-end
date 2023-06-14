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
        Schema::create('medics', function (Blueprint $table) {
            $table->id();
            // $table->increments('id');
     
            //$table->string('fcmtoken')->nullable();
          
            // $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('spec_id');
            //$table->string('Specialité');
            $table->integer('num_tel');
            $table->string('adresse');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('spec_id')->constrained('specialites')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medics');
    }
};
