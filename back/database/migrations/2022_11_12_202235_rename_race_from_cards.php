<?php

use App\Models\Card;
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
        Schema::table('cards', function (Blueprint $table) {
            foreach(Card::all() as $card) {
                if($card->type == 'Spell Card') {
                    $card->race = $card->race . ' spell';
                    $card->save();
                }

                if($card->type == 'Trap Card') {
                    $card->race = $card->race . ' trap';
                    $card->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {

        });
    }
};
