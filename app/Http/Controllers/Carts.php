<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Traits\ArrayTraits;
use App\Traits\MoneyMaths;
use App\Traits\PackageTraits;

use App\Models\Package;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;



class Carts extends Controller
{
    use ArrayTraits;
    use MoneyMaths;
    use PackageTraits;

    private function getItems ($ids, $getBy = 'uuid') {
        $items = Package::whereIn($getBy, $ids)->get();
        return $this->hashBy($items, $getBy);
    }


    function placeOrder (Request $request) {
        $data = json_decode($request->getContent(), true);

        $packageUuids = array_map(function ($item) {
            return $item["uuid"];
        }, $data["itemsInCart"]);

        $items = $this->getItems($packageUuids);
        $backTotal = $this->calculateCartTotal($data['itemsInCart'], $items);

        if ($backTotal !== $data['total']) {
            //Todo Fix Response when total is not accurate
            response()->json([$items, $backTotal], 401);
        }


        $uuid = (String) Str::orderedUuid();
        $cart = new Cart();
        $cart->uuid = $uuid;
        $cart->user_id = Auth::user()->id;
        $cart->save();

        //Save cart Items

        foreach($data['itemsInCart'] as $item) {
            $cartItem = new CartItem();
            $cartItem->package_id = $items[$item['uuid']]->id;
            $cartItem->cart_id = $cart->id;
            $cartItem->quantity = $item['quantity'];
            $cartItem->save();
        }

        //Save Cart //Return UUID


        return response()->json(['uuid' => $uuid]);

    }

    function calculatePackageTotal ($price, $quantity) {
        return $this->multiply($price, $quantity);
    }

    function calculateCartTotal ($cartItems, $packageItems) {
        $sum = 0;
        foreach($cartItems as $cartItem) {
            $sum += $this->multiply($packageItems[$cartItem['uuid']]->price,
                $cartItem['quantity']
            );
        }
        return $sum;
    }


    function getItemsFromCart ($cartUuid) {
        $cart = Cart::where('uuid', $cartUuid)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        $ids = $cartItems->map(function ($cartItem) {
            return $cartItem->package_id;
        });

        $cartItemsHashed = $this->hashBy($cartItems, 'package_id');

        $items = Package::whereIn('id', $ids)->get()->map(
            function (&$item, $key) use ($cartItemsHashed) {
            $item['quantity'] = $cartItemsHashed[$item['id']]->quantity;
            $item['total'] = $this->calculatePackageTotal($item->price, $item->quantity);
            return $item;
        });

        $this->addCropInfoToPackages($items);

        $amount = $items->reduce(function ($carry, $item) {
            return $carry + $this->calculatePackageTotal($item->price, $item->quantity);
        });

        return response()->json(["items" => $items, "status" => true, "total" => $amount ]);
    }

}
