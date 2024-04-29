<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard()
    {
        $products = Product::all();
        $category = Category::all();
        return view('Sale Assistant.Dashboard')->with(['Products' => $products, 'Categories' => $category]);
    }

    public function salesmanCategoryDashboard($categoryName)
    {
        $products = Product::where('category_name', $categoryName)->get();
        $categories = Category::all();
        return view('Sale Assistant.Dashboard')->with(['Products' => $products, 'Categories' => $categories]);
    }

    public function placeOrder(Request $request)
    {
        dd($request->all());
    }
}
