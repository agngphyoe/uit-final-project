<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Paint;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Auth;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $cartProduct = Cart::where('product_id', $request->id)
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 'pending')
                            ->first();

        if($cartProduct){
            $cartProduct->quantity += 1;
            $cartProduct->save();
        }else{
            Cart::create([
                'product_id' => $request->id,
                'user_id' => Auth::user()->id,
                'quantity' => $request->quantity ?? 1,
                'status' => 'pending',
            ]);
        }
        
        return redirect()->back()->with('success', 'Added to Cart Successfully !!');
    }

    public function myCart(){
        $cartProducts = Cart::where('user_id', Auth::user()->id)
                            ->where('status', 'pending')
                            ->get();

        $totalPrice = 0;
        foreach ($cartProducts as $data) {
            $product = Paint::find($data->product_id);
            $totalPrice += $product->price * $data->quantity;
        }
        return view ('cart.myCart', compact('cartProducts', 'totalPrice'));
    }

    public function checkout(Request $request){
        // dd($request->all());
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $count = count($request->product_id);

        $purchase = Purchase::create([
            'inovice_no' => date('YmdHis'),
            'user_id' => Auth::user()->id,
            'quantity' => array_sum($quantity),
            'price' => 0
        ]);

        $i = 0;
        $totalPrice = 0;
        while ($i < $count) {
            $cartProduct = Cart::where('product_id', $product_id[$i])
                                ->where('user_id', Auth::user()->id)
                                ->where('status', 'pending')
                                ->first();
                                
            $cartProduct->delete();

            $product = Paint::find($product_id[$i]);
            $product->quantity -= $quantity[$i];

            PurchaseProduct::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product_id[$i],
                'quantity' => $quantity[$i],
                'price' => $product->price * $quantity[$i]
            ]);

            $totalPrice += $product->price * $quantity[$i];
           
            $i++;
        }

        $purchase->price = $totalPrice;
        $purchase->save();
        
        return redirect()->route('home')->with('success', 'Purchased Successfully !!');
    }
}
