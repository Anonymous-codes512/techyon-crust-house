<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Handler;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use finfo;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard($id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $products = Product::all();
        $categories = Category::all();
        $deals = Deal::all();
        $deals = Handler::with('deal', 'product')->get();
        $cartproducts = Cart::where('salesman_id', $id)->get();

        $filteredCategories = $categories->reject(function ($category) {
            return $category->categoryName === 'Addons';
        });

        $filteredProducts = $products->reject(function ($product) {
            return $product->category_name === 'Addons';
        });

        return view('Sale Assistant.Dashboard')->with([
            'Products' => $filteredProducts,
            'Deals' => $deals,
            'Categories' => $filteredCategories,
            'AllProducts' => $products,
            'id' => $id,
            'cartProducts' => $cartproducts
        ]);
    }

    public function salesmanCategoryDashboard($categoryName, $id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $categories = Category::all();
        $allProducts = Product::all();
        $cartproducts = Cart::where('salesman_id', $id)->get();

        $filteredCategories = $categories->reject(function ($category) {
            return $category->categoryName === 'Addons';
        });
        $deals = $this->deals();

        if ($categoryName != 'Addons') {

            if ($categoryName == 'Deals') {
                return view('Sale Assistant.Dashboard')->with(['Products' => null, 'Deals' => $deals, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts, 'id' => $id, 'cartProducts' => $cartproducts]);
            } else {
                $products = Product::where('category_name', $categoryName)->get();
                return view('Sale Assistant.Dashboard')->with(['Products' => $products,  'Deals' => $deals, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts, 'id' => $id, 'cartProducts' => $cartproducts]);
            }
        }
    }

    public function deals()
    {
        $deals = Handler::with('deal', 'product')->get();
        // dd($deals);
        return $deals;
    }

    public function placeOrder($salesman_id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $order = new Order();
        $cartedProducts = Cart::where('salesman_id', $salesman_id)->get();
        $totalBill = 0.0;

        $order->total_bill = $totalBill;
        $order->salesman_id = $salesman_id;
        $order->save();

        foreach ($cartedProducts as $cartItem) {
            preg_match('/\d+(\.\d+)?/', $cartItem->totalPrice, $matches);
            $numericPart = $matches[0];
            $totalProductPrice = floatval($numericPart);
            $quantity = intval($cartItem->productQuantity);
            $totalBill += $totalProductPrice;

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_name = $cartItem->productName;
            $orderItem->product_variation = $cartItem->productVariation;
            $orderItem->addons = $cartItem->productAddon;
            $orderItem->product_price = 'Rs. ' . ($totalProductPrice / $quantity);
            $orderItem->product_quantity = $quantity;
            $orderItem->total_price = $cartItem->totalPrice;
            $orderItem->save();
        }

        foreach ($cartedProducts as $cartItem) {
            $cartItem->delete();
        }

        $order->total_bill = 'Rs. ' . $totalBill;
        $order->save();

        $products = OrderItem::where('order_id',  $order->id)->get();
        $html = view('reciept', ['products' => $products, 'saleman' => $order->salesman->name])->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $height = $dompdf->getCanvas()->get_height();
        $dompdf->setPaper([0, 0, 300, $height], 'portrait');
        $dompdf->render();

        $dompdf->stream('crust house.pdf');
        return redirect()->back();
    }


    public function saveToCart(Request $request)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }
        $productOrder = new Cart();

        $salesman_id = $request->input('salesman_id');
        $drinkFlavour = explode(' (Rs. ', rtrim($request->input('drinkFlavour'), ')'));
        $addon = explode(' (Rs. ', rtrim($request->input('addOn'), ')'));
        $variations = explode(' (Rs. ', rtrim($request->input('prodVariation'), ')'));

        $productOrder->salesman_id = $salesman_id;
        $productOrder->productName = $request->input('productname');
        $productOrder->productPrice = $request->input('productprice');
        $productOrder->productAddon = $addon[0] ?? null;
        $productOrder->addonPrice = isset($addon[1]) ? 'Rs. ' . $addon[1] : null;
        $productOrder->productVariation = $variations[0] ?? null;
        $productOrder->variationPrice = isset($variations[1]) ? 'Rs. ' . $variations[1] : null;
        $productOrder->drinkFlavour = $drinkFlavour[0] ?? null;
        $productOrder->drinkFlavourPrice = isset($drinkFlavour[1]) ? 'Rs. ' . $drinkFlavour[1] : null;
        $productOrder->productQuantity = $request->input('prodQuantity');
        $productOrder->totalPrice = $request->input('totalprice');

        $productOrder->save();

        return redirect()->route('salesman_dashboard', ['id' => $salesman_id]);
    }

    public function clearCart($salesman_id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $cartedProducts = Cart::where('salesman_id', $salesman_id)->get();
        foreach ($cartedProducts as $cartItem) {
            $cartItem->delete();
        }

        return redirect()->back();
    }

    public function removeOneProduct($id, $salesman_id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $cartedProduct = Cart::where('id', $id)->where('salesman_id', $salesman_id)->first();
        if ($cartedProduct) {
            $cartedProduct->delete();
        }
        return redirect()->route('salesman_dashboard', ['id' => $salesman_id]);
    }

    public function increaseQuantity($id, $salesman_id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }

        $cartedProduct = Cart::find($id);
        $productPrice = $cartedProduct->totalPrice;

        preg_match('/\d+(\.\d+)?/', $productPrice, $matches);
        $numericPart = $matches[0];
        $productPrice =  floatval($numericPart);
        $singleProductPrice = floatval($numericPart) / intval($cartedProduct->productQuantity);

        $cartedProduct->totalPrice =  'Rs. ' . ($productPrice + $singleProductPrice);
        $cartedProduct->productQuantity =  intval($cartedProduct->productQuantity) + 1;
        $cartedProduct->save();

        return redirect()->route('salesman_dashboard', ['id' => $salesman_id]);
    }

    public function decreaseQuantity($id, $salesman_id)
    {
        $salesman_id = Session::get('user_id');

        if (!$salesman_id) {
            return redirect()->route('viewLoginPage');
        }
        
        $cartedProduct = Cart::find($id);
        if ($cartedProduct->productQuantity > 1) {
            $productPrice = $cartedProduct->totalPrice;

            preg_match('/\d+(\.\d+)?/', $productPrice, $matches);
            $numericPart = $matches[0];
            $productPrice = floatval($numericPart);
            $quantity = intval($cartedProduct->productQuantity);

            if ($quantity > 1) {
                $singleProductPrice = $productPrice / $quantity;
                $cartedProduct->totalPrice = 'Rs. ' . ($productPrice - $singleProductPrice);
                $cartedProduct->productQuantity = $quantity - 1;
            } else {
                return redirect()->route('salesman_dashboard', ['id' => $salesman_id]);
            }

            $cartedProduct->save();
        }

        return redirect()->route('salesman_dashboard', ['id' => $salesman_id]);
    }
}
