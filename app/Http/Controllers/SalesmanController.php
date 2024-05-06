<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard()
    {
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
        ]);
    }

    public function salesmanCategoryDashboard($categoryName)
    {
        $categories = Category::all();
        $allProducts = Product::all();

        $filteredCategories = $categories->reject(function ($category) {
            return $category->categoryName === 'Addons';
        });

        if ($categoryName != 'Addons') {

            if ($categoryName == 'Deals') {
                $deals = $this->deals();
                return view('Sale Assistant.Dashboard')->with(['Products' => null, 'Deals' => $deals, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts]);
            } else {
                $products = Product::where('category_name', $categoryName)->get();
                return view('Sale Assistant.Dashboard')->with(['Products' => $products, 'Categories' => $filteredCategories, 'AllProducts' => $allProducts]);
            }
        }
    }

    public function deals()
    {
        $deals = Deal::all();
        return $deals;
    }

    public function placeOrder(Request $request)
    {
        $productName = null;
        $productVariation = null;
        $addons = null;
        $productQuantity = null;
        $productPrice = null;
        $index = 1;

        while ($request->has("product$index")) {
            $product = json_decode($request->input("product$index"), true);

            $productName = $productName === null ? trim($product['name']) : $productName . ", " . trim($product['name']);
            // $productVariation = $productVariation === null ? $product['variation'] : $productVariation . ", " . $product['variation'];

            if ($product['variation'] === "") {
                $productVariation = $productVariation === null ? "~" : $productVariation .",". "~";
            } else {
                $productVariation = $productVariation  == null ? $product['variation'] :$productVariation ."," . $product['variation'];
            }

            if ($product['addons'] === "") {
                $addons = $addons == null ? "~" : $addons . "," . "~";
            } else {
                $addons = $addons == null ? $product['addons'] : $addons . "," . $product['addons'];
            }

            $productQuantity = $productQuantity === null ? $product['quantity'] : $productQuantity . ", " . $product['quantity'];
            $productPrice = $productPrice === null ? $product['price'] : $productPrice . ", " . $product['price'];

            $index++;
        }

        $totalBill = preg_replace('/[^0-9.]/', '', $request->totalbill);
        $totalBill = preg_replace('/^[.]+/', '', $totalBill);

        $newOrder = new Order();

        $newOrder->productName = $productName;
        $newOrder->productVariation = $productVariation;
        $newOrder->addons = $addons;
        $newOrder->productQuantity = $productQuantity;
        $newOrder->productPrice = $productPrice;

        $newOrder->total_bill_amount = $totalBill;

        $newOrder->save();

        return redirect()->back();
    }
}






// [{"name":" Crown Crust",
//     "variation":"Small",
//     "addons":"",
//     "price":650,
//     "quantity":"1"},
//     {"name":" Grilled Pizza","variation":"Large","addons":"Chicken Topping","price":2700,"quantity":"2"}]