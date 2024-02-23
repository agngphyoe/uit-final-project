<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index(){
        $client = new Client();
        $response = $client->get('http://localhost:8080/api/paints');
        $datas = json_decode($response->getBody()->getContents(), true);
        
        return view('home.index', ['datas' => $datas]);
    }

    public function about(){
        return view('about.index');
    }

    public function contactUs(){
        return view('contact.index');
    }
}
