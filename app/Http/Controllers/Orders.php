<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;

use Illuminate\Support\Facades\Auth;


class Orders extends Controller
{
    function makeOrder (Request $request) {
        $data = json_decode($request->getContent());

        $cart = Cart::where('uuid', $data->cartUuid)->first();
       
        $order = new Order();
        $order->uuid = Order::generateUuid();
        $order->user_id = Auth::user()->id;
        $order->payload = 'One two three';
        $order->save();

        //get items in the associated cart
        $items = CartItem::where('cart_id', $cart->id)->get();

        foreach ($items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $cart->id;
            $orderItem->package_id = $item->package_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->save();
        }

        
        return response()->json(
            $order
        );

    }

    function get (Request $request) {
        /*$cart = Cart::where('uuid', $uuid)->first();

        //get items in the associated cart
        $items = CartItem::where('cart_id', $cart->id);

        //Move the items to Order Cart

        $order = new Order();
        $order->uuid = */
        $uuid = Order::generateUuid();

        return response()->json(
            ["uuid" => $uuid]
        );

    }
}
