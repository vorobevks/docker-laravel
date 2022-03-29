<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Storage;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::withTrashed()->get();
        foreach ($categories as $category) {
            $category->forceDelete();
        }
//        var_dump(resource_path());die;

        $file = file(resource_path().'/Products.csv');
        $data = array_slice($file, 1);
        $parts = array_chunk($data, 5000);
        foreach ($parts as $index => $part) {
            $fileName = resource_path('parts/'.date('y-m-d').$index.'.csv');
            file_put_contents($fileName, $part);
        }
//        die;

        $path = resource_path('parts/*.csv');
        $g = glob($path);
//        var_dump($g);die;
        foreach (array_slice($g, 0) as $file) {
            var_dump($file);


            $stream = fopen($file, 'r');

            $csv = Reader::createFromStream($stream);
            $csv->setDelimiter(',');

            $stmt = Statement::create();
            $i=1;
            $records = $stmt->process($csv);
            foreach ($records as $record) {
                var_dump($i++);
                $category = Category::where('name', $record[6])->first();
//            if (!$category) {
                $category = new Category();
                $category->name = $record[6];
                $category->save();
//            }
            }
//            var_dump($file);
        }
        die;

        $files = Storage::files(resource_path().'/parts'); // Все файлы в указанном каталоге
        var_dump($files);die;




        $csvFileName = "Products.csv";
        $csvFile = resource_path($csvFileName);

        $stream = fopen($csvFile, 'r');

        $csv = Reader::createFromStream($stream);
        $csv->setDelimiter(',');

        $stmt = Statement::create();

        $records = $stmt->process($csv);
        foreach ($records as $record) {
            $category = Category::where('name', $record[6])->first();
//            if (!$category) {
                $category = new Category();
                $category->name = $record[6];
                $category->save();
//            }
        }
//        die;


    }
}
