<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Handler;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard($id)
    {
        // dd($id);
        $products = Product::all();
        $categories = Category::all();
        $deals = Deal::all();

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
            'id' => $id
        ]);
    }
    
    public function salesmanCategoryDashboard($categoryName, $id)
    {
        // dd($id);
        $categories = Category::all();
        $allProducts = Product::all();

        $filteredCategories = $categories->reject(function ($category) {
            return $category->categoryName === 'Addons';
        });

        if ($categoryName != 'Addons') {

            if ($categoryName == 'Deals') {
                $deals = $this->deals();
                return view('Sale Assistant.Dashboard')->with(['Products' => null, 'Deals' => $deals, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts, 'id'=>$id]);
            } else {
                $products = Product::where('category_name', $categoryName)->get();
                return view('Sale Assistant.Dashboard')->with(['Products' => $products, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts, 'id'=>$id]);
            }
        }
    }

    public function deals()
    {
        $deals = Handler::with('deal', 'product')->get();
        // foreach ($deals as  $value) {
        //     echo $value->deal->dealTitle . '<br>';
        // }
        // foreach ($deals as  $value) {
        //     echo $value->product->productName . '<br>'  ;
        // }
        // dd($deals);
        return $deals;
    }

    public function placeOrder(Request $request)
    {

        dd($request->all());
        $totalBill = preg_replace('/[^0-9.]/', '', $request->totalbill);
        $totalBill = preg_replace('/^[.]+/', '', $totalBill);

        $order = new Order();
        $order->total_bill = $totalBill;
        $order->save();

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'product') !== false) {
                $product = json_decode($value, true);

                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_name = $product['name'];
                $orderItem->product_variation = $product['variation'];
                $orderItem->addons = $product['addons'];
                $orderItem->product_price = $product['price'];
                $orderItem->product_quantity = $product['quantity'];
                $orderItem->total_price = $product['price'] * $product['quantity'];

                $orderItem->save();
            }
        }

        return redirect()->back();
    }
}
