<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {

        if ($request->ajax()) {
            $data = Order::query()->with(['user', 'product'])->orderBy('id');

            if (isset($request->startDate)) {
                $data = $data->where('order_date', '>=', date($request->startDate));
            }
            if (isset($request->endDate)) {
                $data = $data->where('order_date', '<=', date($request->endDate));
            }
            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($order) {
                    return view('admin.orders.buttons', ['order' => $order]);
                })
                ->addColumn('user', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('product', function ($order) {

                    return count($order->product);
                })
                ->addColumn('address', function ($order) {
                    return $order->address;
                })
                ->addColumn('postal_code', function ($order) {
                    return $order->postal_code;
                })
                ->addColumn('cost', function ($order) {
                    $totalCost = 0;

                    foreach ($order->product as $product) {
                        $totalCost += $product->price;
                    }

                    return $totalCost . '€';
                })
                ->rawColumns(['action'])
                ->blacklist(['action', 'price'])
                ->make(true);
        }
        return null;
    }

    function form(Request $request)
    {

        $addresses = Address::query()->where('user_id', Auth::user()->id)->get();

        $arrayProducts = $request->products;
        $amountProducts = 0;
        foreach ($arrayProducts as $item) {
            $product = Product::query()->where('id', json_decode($item)->id)->first();

            if (json_decode($item)->amount > $product->stock) {

                return redirect()->route('cart')->withErrors(['product_name' => $product->name, 'message' => 'Has pedido demasiadas unidades, quedan: ' . $product->stock . ' unidades']);

            }

            $amountProducts += 1*json_decode($item)->amount;
        }

        $products = [];
        foreach ($arrayProducts as $productString) {

            $productItem = json_decode($productString);
            $product = Product::query()->where('id', $productItem->id)->first();
            $products[] = ['product' => json_encode($product), 'amount' => $productItem->amount];
        }

        return view('client.orders.form', ['addresses' => $addresses, 'products' => json_encode($products), 'total' => $request->total, 'amountProducts' => $amountProducts]);

    }

    public function createOrder(Request $request)
    {

        $order = Order::factory()->create([
            'user_id' => Auth::user()->id,
            'address' => $request->addresses,
            'order_date' => now(),
            'delivery_date' => null,

        ]);
        foreach ($request->products as $item) {
            $product = Product::query()->where('id', json_decode(json_decode($item)->product)->id)->first();
            $order->product()->attach($product);
            $product->stock = $product->stock-json_decode($item)->amount;
            $product->save();
        }




        return view('client.orders.done');

    }
}
