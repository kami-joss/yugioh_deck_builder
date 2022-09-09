<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->text('desc')->nullable()->default(null);
            $table->integer('atk')->nullable()->default(null);
            $table->integer('def')->nullable()->default(null);
            $table->integer('level')->nullable()->default(null);
            $table->string('attribute')->nullable()->default(null);
            $table->integer('linkval')->nullable()->default(null);
            $table->string('linkmarkers')->nullable()->default(null);
            $table->integer('scale')->nullable()->default(null);
            $table->string('archetype')->nullable()->default(null);
            $table->integer('api_id')->nullable()->default(null);
            $table->string('race')->nullable()->default(null);
            $table->integer('image_id')->nullable();
            $table->integer('image_small_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
