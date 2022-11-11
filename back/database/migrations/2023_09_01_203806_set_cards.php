<?php

use App\Models\Card;
use App\Models\Image;
use App\Models\CardSet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        //
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://db.ygoprodeck.com/api/v7/cardinfo.php');
        $cards = $response->getBody()->getContents();

        $cards = json_decode($cards, true);
        foreach ($cards['data'] as $card) {
            $newCard = Card::create([
                'name' => $card['name'] ?? null,
                'type' => $card['type'] ?? null,
                'desc' => $card['desc'] ?? null,
                'race' => $card['race'] ?? null,
                'archetype' => $card['archetype'] ?? null,
                'api_id' => $card['id'],
                'atk' => $card['atk'] ?? null,
                'def' => $card['def'] ?? null,
                'level' => $card['level'] ?? null,
                'attribute' => $card['attribute'] ?? null,
                'linkval' => $card['linkval'] ?? null,
                'linkmarkers' => array_key_exists('linkmarkers', $card) ? serialize($card['linkmarkers']) : null,
                'scale' => $card['scale'] ?? null,
            ]);

            if (array_key_exists('card_sets', $card)) {
                foreach ($card['card_sets'] as $set) {
                    CardSet::create([
                        'set_name' => $set['set_name'] ?? null,
                        'set_code' => $set['set_code'] ?? null,
                        'set_rarity' => $set['set_rarity'] ?? null,
                        'set_rarity_code' => $set['set_rarity_code'] ?? null,
                        'set_price' => $set['set_price'] ?? null,
                        'card_id' => $newCard->id,
                    ]);
                }
            }

            if (array_key_exists('card_images', $card)) {
                foreach ($card['card_images'] as $image) {
                    $imageExtension = substr($image['image_url'], strrpos($image['image_url'], '.') + 1);
                    // $imageSmallExtension = substr($image['image_url_small'], strrpos($image['image_url_small'], '.') + 1);

                    $imageContent = file_get_contents($image['image_url']);
                    $path = 'card_images' . DIRECTORY_SEPARATOR . $image['id'] . '.' . $imageExtension;
                    Storage::put($path, $imageContent);

                    // $imageSmallContent = file_get_contents($image['image_url_small']);
                    // $pathSmall = 'card_images' . DIRECTORY_SEPARATOR . $image['id'] . '_small' . '.' . $imageSmallExtension;
                    // Storage::put($pathSmall, $imageSmallContent);

                    $newImage = Image::create([
                        'api_id' => $image['id'] ?? null,
                        'path' => $path,
                        'name' => $image['id'],
                        'extension' => $imageExtension,
                        'type' => 'card_image',
                    ]);

                    // $newImageSmall = Image::create([
                    //     'api_id' => $image['id'] ?? null,
                    //     'path' => $pathSmall,
                    //     'name' => $image['id'] . '_small',
                    //     'extension' => $imageSmallExtension,
                    //     'type' => 'card_image_small',
                    // ]);

                    $newCard->image_id = $newImage->id;
                    // $newCard->image_small_id = $newImageSmall->id;
                    $newCard->save();
                }
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
        DB::table('cards')->truncate();
        DB::table('card_sets')->truncate();
        DB::table('images')->truncate();
    }
};
