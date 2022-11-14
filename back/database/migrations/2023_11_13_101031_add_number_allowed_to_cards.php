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
        Schema::table('cards', function (Blueprint $table) {
            $table->integer('number_allowed')->default(3);
        });

        // Set the number allowed for all cards
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://db.ygoprodeck.com/api/v7/cardinfo.php?banlist=tcg');
        $banList = $response->getBody()->getContents();

        $banList = json_decode($banList, true);

        foreach ($banList['data'] as $cardBan) {

            $card = Card::where('api_id', $cardBan['id'])->first();
            if ($card) {
                switch ($cardBan['banlist_info']['ban_tcg']) {
                    case 'Limited':
                        $card->number_allowed = 1;
                        break;
                    case 'Semi-Limited':
                        $card->number_allowed = 2;
                        break;
                    case 'Banned':
                        $card->number_allowed = 0;
                        break;
                }
                $card->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            //
        });
    }
};
