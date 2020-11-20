<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;

use Illuminate\Support\Facades\Auth;
use App\Traits\ResponsesTraits;
use App\Traits\UserTraits;
use App\Traits\PackageTraits;
use App\Traits\ArrayTraits;
use App\Traits\MoneyMaths;

class Orders extends Controller
{

    use ResponsesTraits;
    use UserTraits;
    use PackageTraits;
    use ArrayTraits;
    use MoneyMaths;

    function makeOrder (Request $request) {
        if (!Auth::check()) {
            return $this->responseUnAuthorised();
        }

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
            $orderItem->order_id = $order->id;
            $orderItem->package_id = $item->package_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->save();
        }

        
        return response()->json(
            $order
        );

    }

    function get () {
        if (!Auth::check()) {
            return $this->responseUnAuthorised();
        }

        $orders = Order::where('user_id', '=', Auth::user()->id)->get();
        $orders_ids = Order::getUniqueColumnValues('id', $orders);

        $orderItems = OrderItem::whereIn('order_id', $orders_ids)->get();
        $ids = OrderItem::getUniqueColumnValues('package_id', $orderItems);
        $packages = Package::whereIn('id', $ids)->get();
        $this->addCropInfoToPackages($packages);
        $packagesHashed = $this->hashBy($packages, 'id');

        $orderItems->map(function (&$orderItem) use ($packagesHashed) {
            $orderItem->package = $packagesHashed[$orderItem->package_id];
        });


        return response()->json(
            ["orderItems" => $orderItems]
        );

    }


    function summary () {

        if (!Auth::check()) {
            return $this->responseUnAuthorised();
        }

        $orders = Order::where('user_id', '=', Auth::user()->id)->get();
        $orders_ids = Order::getUniqueColumnValues('id', $orders);

        $orderItems = OrderItem::whereIn('order_id', $orders_ids)->get();
        $ids = OrderItem::getUniqueColumnValues('package_id', $orderItems);
        $packages = Package::whereIn('id', $ids)->get();
        $this->addCropInfoToPackages($packages);
        $packagesHashed = $this->hashBy($packages, 'id');

        $orderItems = $orderItems->map(function (&$orderItem) use ($packagesHashed) {
            $orderItem->package = $packagesHashed[$orderItem->package_id];
            $orderItem->principal = $orderItem->calculatePrinciple();
            $orderItem->interest = $orderItem->calculateInterest();
            $orderItem->totalReturn =  $orderItem->principal + $orderItem->interest;
            return $orderItem;
        });

        $totalPrincipal = 0;
        $otalEarnings = 0;
        $balance = 0;
        $totalInterest = 0;
       

        foreach($orderItems as $orderItem) {
            $totalPrincipal += $orderItem->principal;
            $totalInterest += $orderItem->interest;
        }

        return response()->json(
            [
                "items" => $orderItems,
                "totalPrinciple" => $totalPrincipal,
                'totalInterest'=> $totalInterest,
                "totalReturn" =>  $totalPrincipal + $totalPrincipal,
                "available" => 0
            ]
        );
    }

}
