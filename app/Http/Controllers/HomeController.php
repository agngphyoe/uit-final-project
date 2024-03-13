<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Category;
use App\Models\PurchaseProduct;
use DB;

class HomeController extends Controller
{
    public function index(){
        $client = new Client();
        $response = $client->get('http://localhost:8080/api/paints');
        $categories = Category::all();
        // $datas = json_decode($response->getBody()->getContents(), true);
        $datas = PurchaseProduct::select('product_id', DB::raw('COUNT(*) as count'))
                                ->groupBy('product_id')
                                ->orderByDesc('count')
                                ->limit(3)
                                ->get();
                                // dd($datas);
        
        return view('home.index', ['datas' => $datas, 'categories' => $categories]);
    }

    public function about(){
        $categories = Category::all();
        return view('about.index', compact('categories'));
    }

    public function contactUs(){
        $categories = Category::all();
        
        return view('contact.index',compact('categories'));
    }
}
