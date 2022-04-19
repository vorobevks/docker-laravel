<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\Http;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\Log;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        ini_set('memory_limit', '2024M');
        $categories = Category::withTrashed()->get();
        foreach ($categories as $category) {
            $category->forceDelete();
        }
        $items = Item::withTrashed()->get();
        foreach ($items as $item) {
            $item->forceDelete();
        }

        $i = 0;
        $reader = Reader::createFromPath(resource_path().'/Products.csv', 'r');
        foreach ($reader as $record) {
            if (!$i) {
                $i++;
                continue;
            }


            $parentCategoryForItem = null;

            $category1 = Category::where('name', $record[1])->where('parent_id', null)->first();
            if (!$category1) {
                $category1 = new Category();
            }
                $category1->image = '';
                $category1->name = $record[1];
                $category1->save();
                $parentCategoryForItem = $category1->id;

            if ($record[2]) {
                $category2 = Category::where('name', $record[2])->where('parent_id', $category1->id)->first();
                if (!$category2) {
                    $category2 = new Category();
                }
                    $category2->image = '';
                    $category2->name = $record[2];
                    $category2->parent_id = $category1->id;
                    $category2->save();
                    $parentCategoryForItem = $category2->id;


                if ($record[3]) {
                    $category3 = Category::where('name', $record[3])->where('parent_id', $category2->id)->first();
                    if (!$category3) {
                        $category3 = new Category();
                    }
                    $category3->image = '';
                    $category3->name = $record[3];
                    $category3->parent_id = $category2->id;
                    $category3->save();
                    $parentCategoryForItem = $category3->id;
                }
            }


            //todo этот блок кода нужен был для того чтобы подтягивать картинки по названию товара из яндекса
            // но там получается бан при частых запросах и яндекс говорит что надо ввести капчу
            // пока оставляю, может быть позже что нибудь придумаю
//            $client = new \GuzzleHttp\Client();
//            $response = $client->request('GET', "https://yandex.ru/images/search?text=$record[6]&isize=eq&iw=1366&ih=768");
//            $body = $response->getBody()->getContents();
//            $dom = new Dom;
//            $dom->loadStr($body);
//            $a = $dom->getElementsbyTag('img');
//            foreach ($a as $item) {
//                var_dump($item->src);
//            }

            $images = [
                'https://avatars.mds.yandex.net/i?id=31240204612f6119af37531fa549ab5d-5558158-images-thumbs&n=13',
                'https://avatars.mds.yandex.net/i?id=cc03e8956ceec86cddda3a7d9ab363c5-5888889-images-thumbs&n=13',
                'https://avatars.mds.yandex.net/i?id=00e8a4218187a9a18d2ae6e02bc34302-5087276-images-thumbs&n=13',
                'https://avatars.mds.yandex.net/i?id=37bf19723c8c9ee89bc9dcdaac4542bb-5381133-images-thumbs&n=13'
            ];



            $item = new Item();
            $item->article = $record[4];
            $item->name = $record[6];
            $item->price = (double)str_replace(' ', '', $record[8]);
            $item->description = $record[10];

//            $images = explode(' ', $record[13]);

//            $item->preview_image = $a[3]->src;
            $item->preview_image = $images[random_int(0,count($images)-1)];
//            $item->images = [$a[2]->src, $a[4]->src];
            $item->images = [$images[random_int(0,count($images)-1)], $images[random_int(0,count($images)-1)]];
            $item->save();
            $item->categories()->attach($parentCategoryForItem);
        }
    }
}
