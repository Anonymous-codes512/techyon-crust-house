<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard()
    {
        $products = Product::all();
        $category = Category::all();
        $deals = Deal::all();
        return view('Sale Assistant.Dashboard')->with(['Products' => $products,'Deals' => $deals, 'Categories' => $category, 'AllProducts' => $products]);
    }

    public function salesmanCategoryDashboard($categoryName)
    {
        $categories = Category::all();
        $allProducts = Product::all();
    
        if ($categoryName == 'Deals') {
            $deals = $this->deals();
            return view('Sale Assistant.Dashboard')->with(['Products' => null, 'Deals' => $deals, 'Categories' => $categories, 'AllProducts' => $allProducts]);
        } else {
            $products = Product::where('category_name', $categoryName)->get();
            return view('Sale Assistant.Dashboard')->with(['Products' => $products, 'Categories' => $categories, 'AllProducts' => $allProducts]);
        }
    }
    
    public function deals()
    {
        $deals = Deal::all();
        return $deals;
    }


    public function placeOrder(Request $request)
    {
        dd($request->all());
    }
}
