<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function siteMap() {
        $products = Product::all();
        $news = News::all();

        $data = [];
        $index = 0;
        foreach ($products as $product) {
            $data[$index]['alias'] = $product->alias;
            $data[$index]['created_at'] = $product->created_at;
            $index ++;
        }
        foreach ($news as $new) {
            $data[$index]['alias'] = $new->alias;
            $data[$index]['created_at'] = $new->created_at;
            $index ++;
        }

        return response()->view('frontend.siteMap.index', compact(['data']))->header('Content-Type', 'text/xml');
    }
}
