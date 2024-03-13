<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Paint;
use GuzzleHttp\Client;
use App\Models\PurchaseProduct;
use App\Models\Purchase;
use Auth;
use DB;

class ProductController extends Controller
{
    public function index(){

        $categories = Category::all();
        $artists = Artist::all();
        $products = Product::all();

        foreach ($products as $product) {
            $product->artist = Artist::where('id', $product->artist_id)->value('name');
            $product->category = Category::where('id', $product->category_id)->value('name');
        }

        return view('product.index', ['products' => $products, 'categories' => $categories, 'artists' => $artists]);
    }

    public function details(Request $request){
        $client = new Client();
        $url = 'http://localhost:8080/api/paints/'. $request->id;

        $response = $client->get($url);        
        $data = json_decode($response->getBody()->getContents(), true);
        
        $alt_datas = Product::where('category_id', $data['category'] ['id'])
                            ->get();
                            

        foreach ($alt_datas as $data) {
            $product_url = 'http://localhost:8080/api/paints/'. $data->id;
            $alt = $client->get($url);
            $alt_data = json_decode($alt->getBody()->getContents(), true);
            $data->price = $alt_data['price'];
            $data->category = $alt_data['category'];
            $data->artist = $alt_data['artist'];
            $data->imagePath = $alt_data['imagePath'];
        }

        $categories = Category::all();
        
        return view('product.detail', ['alt_datas' => $alt_datas, 'data' => $data, 'categories' => $categories]);
    }

    public function categoryProduct(Request $request){
    
        $categories = Category::all();
        $categoryName = Category::where('id',$request->id)->value('name');
        
        $products = Paint::where('category_id', $request->id)->get();

        return view ('product.categoryProduct', ['products' => $products, 'categoryName' => $categoryName, 'categories' => $categories]);
    }

    public function recomandedProuct(){
        $products = PurchaseProduct::select('product_id', DB::raw('COUNT(*) as count'))
                                ->groupBy('product_id')
                                ->orderByDesc('count')
                                ->limit(3)
                                ->get();

        $categories = Category::all();
        return view('product.recomanded', compact('products', 'categories'));
    }
}
