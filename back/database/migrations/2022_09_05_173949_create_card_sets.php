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
        Schema::create('card_sets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('set_name')->nullable()->default(null);
            $table->string('set_code')->nullable()->default(null);
            $table->string('set_rarity')->nullable()->default(null);
            $table->string('set_rarity_code')->nullable()->default(null);
            $table->string('set_price')->nullable()->default(null);
            $table->integer('card_id')->nullable()->default(null);
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
        Schema::dropIfExists('card_sets');
    }
};
