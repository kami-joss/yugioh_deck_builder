<?php

use App\Models\Card;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Card::all() as $card) {
            if (strtolower($card->type) == 'spell card') {
                $card->race = strtolower($card->race) . ' spell';
            }

            if (strtolower($card->type) == 'trap card') {
                $card->race = $card->race . ' trap';
            }
            $card->save();
        };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (Card::all() as $card) {
            if (strtolower($card->type) == 'spell card') {
                $pos = strpos($card->race, ' spell');
                if ($pos) $card->race = substr($card->race, 0, $pos);
            }

            if (strtolower($card->type == 'trap card')) {
                $pos = strpos($card->race, ' trap');
                if ($pos) $card->race = substr($card->race, 0, $pos);
            }
            $card->save();
        }
    }
};
