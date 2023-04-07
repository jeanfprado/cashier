<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug_name');
            $table->string('description')->nullable();
            $table->enum('type', ['monthly', 'triennial', 'semestrial', 'yearly']);
            $table->decimal('amount');
            $table->json('features');
            $table->json('options')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('archived')->default(false);
            $table->uuid('public_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
