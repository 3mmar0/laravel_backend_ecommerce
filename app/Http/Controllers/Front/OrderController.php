<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\Fron\SingleProductResource;
use App\Models\Admin\Address;
use App\Models\Admin\Order;
use App\Models\Admin\OrderItem;
use App\Models\Admin\Product;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function makeOrder(Request $request)
    {
        try {
            //code...
            // Extracting nested address data
            $address = $request->address;
            // dd($address);
            $firstName = $address['first_name'];
            $lastName = $address['last_name'];
            $phone = $address['phone'];
            $addressLine = $address['address'];
            $country = $address['country'];

            // Extracting and parsing products data
            $products = $request->products;

            $total = 0;
            foreach ($products as $key => $value) {
                $product = $this->getValues($value);
                $productId = $product['product_id'];
                $quantity = $product['quantity'];
                $prod = Product::where('id', $productId)->select('price')->first();
                $total += $prod->price * $quantity;
            }

            // dd($total);
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'total_price' => $total,
            ]);

            foreach ($products as $key => $value) {
                $product = $this->getValues($value);
                $productId = $product['product_id'];
                $quantity = $product['quantity'];
                $prod = Product::where('id', $productId)->select('price')->first();
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $prod->price,
                    'total_price' => $prod->price,
                ]);
            }

            Address::create([
                'order_id' => $order->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'address' => $addressLine,
                'country' => $country,
            ]);
            DB::commit();

            return Helper::sendSuccess('Order make successfully', [], 200);
        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                dd('validation', $e);
                return Helper::sendError($e->getMessage(), $e->errors(), 422);
            }
            if ($e instanceof GuzzleException) {
                dd('exception', $e);
                return Helper::sendError($e->getMessage(), [], $e->getCode());
            }
            // dd('none', $e);
            return Helper::sendError($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function getValues($value)
    {
        // Remove "{" and "}" characters
        $data = str_replace(['{', '}'], '', $value);

        // Explode the string by ","
        $parts = explode(',', $data);
        $result = [];

        foreach ($parts as $part) {
            // Explode each part by ":"
            $keyValue = explode(':', $part);
            // Trim whitespace from the key and value
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);
            // Store key-value pair in the result array
            $result[$key] = $value;
        }
        return $result;
    }
}