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

        if($categoryName != 'Addons'){

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
        dd($request->all());
    }
}
