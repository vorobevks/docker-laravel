<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function main()
    {
        $paginator = Item::select('id','name', 'price')->paginate(50);
        $page_count = (int)$paginator->lastPage();
        $items = $paginator->items();

        return View('site.main', ['items' => $items, 'page_count' => $page_count]);
    }
}
