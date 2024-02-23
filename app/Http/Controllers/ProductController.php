<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Artist;
use GuzzleHttp\Client;

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
        
        return view('product.detail', ['alt_datas' => $alt_datas, 'data' => $data]);
    }

    public function addToCart(Request $request){
        $client = new Client();
        $product_id = $request->product_id;
        $url = 'http://localhost:8080/api/paints/'. $product_id;
        $response = $client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);
        
        $quantity = $request->input('quantity', 1);

        $cartItems = session()->get('cart', []);
        $cartItems[$product_id] = [
            'product_id' => $product_id,
            'name' => $data['title'],
            'price' => $data['price'],
            'quantity' => isset($cartItems[$product_id]) ? $cartItems[$product_id]['quantity'] + $quantity : $quantity,
            'imagePath' => $data['imagePath'],
        ];

        session(['cart' => $cartItems]);

        return response()->json(['data' => $cartItems]);
    }
}
