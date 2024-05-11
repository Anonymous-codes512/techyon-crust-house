<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deal;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function viewAdminDashboard()
    {
        $totalCategories = $this->totalCategories();
        $totalProducts = $this->totalProducts();

        return view('Admin.AdminDashboard')->with(['totalCategories' => $totalCategories, 'totalProducts' => $totalProducts]);
    }

    /*
    |---------------------------------------------------------------|
    |======================= Category Functions ====================|
    |---------------------------------------------------------------|
    */

    public function viewCategoryPage()
    {
        // $categories = Category::paginate(2);
        $category = Category::all();

        return view('Admin.Category')->with(['categories' => $category]);
    }

    public function createCategory(Request $request)
    {
        $validatedData = $request->validate([
            'CategoryImage' => 'required|image|mimes:jpeg,png,jpg|',
            'categoryName' => 'required|string|max:255',
        ]);

        if ($request->hasFile('CategoryImage')) {
            $image = $request->file('CategoryImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/CategoryImages'), $imageName);
        }

        $category = new Category();
        $category->categoryImage = $imageName;
        $category->categoryName = $validatedData['categoryName'];
        $category->save();

        return redirect()->route('viewCategoryPage');
    }

    public function updateCategory(Request $request)
    {
        $validatedData = $request->validate([
            'CategoryImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'categoryName' => 'required|string|max:255',
        ]);
        $category = Category::find($request->id);
        $imageName = null;

        if ($request->hasFile('CategoryImage')) {

            $existingImagePath = public_path('Images/CategoryImages') . '/' . $category->categoryImage;
            File::delete($existingImagePath);

            $image = $request->file('CategoryImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/CategoryImages'), $imageName);
        }

        $category->categoryImage = $imageName;
        $category->categoryName = $validatedData['categoryName'];
        $category->save();

        return redirect()->route('viewCategoryPage');
    }

    public function deleteCategory($id)
    {

        $category = Category::find($id);
        $category->delete();
        $imagePath = public_path('Images/CategoryImages') . '/' . $category->categoryImage;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        return redirect()->route('viewCategoryPage');
    }

    /*
    |---------------------------------------------------------------|
    |======================= Product Functions =====================|
    |---------------------------------------------------------------|
    */

    public function viewProductPage()
    {
        $categories = Category::all();
        $products = Product::all();

        if ($categories->isEmpty()) {
            return view('Admin.Product');
        }

        return view('Admin.Product')->with(['categoryData' => $categories, 'productsData' => $products]);
    }

    public function createProduct(Request $request)
    {
        [$categoryId, $categoryName] = explode(',', $request->categoryId);

        $imageName = null;
        if ($request->hasFile('productImage')) {
            $image = $request->file('productImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/ProductImages'), $imageName);
        }

        $product = new Product();
        $product->productImage = $imageName;
        $product->productName = $request->productName;
        $product->productSize = $request->productSize;
        $product->productPrice = $request->productPrice;
        $product->category_id = $categoryId;
        $product->category_name = $categoryName;

        $product->save();


        return redirect()->route('viewProductPage');
    }


    public function updateProduct(Request $request)
    {
        $validatedData = $request->validate([
            'categoryId' => 'required|exists:categories,id',
            'productName' => 'required|string|max:255',
            'productSize' => 'required|string|max:255',
            'productPrice' => 'required|string|max:255',
            'productImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $product = Product::find($request->pId);
        $imageName = null;

        if ($request->hasFile('productImage')) {

            $existingImagePath = public_path('Images/ProductImages') . '/' .  $product->productImage;
            File::delete($existingImagePath);

            $image = $request->file('productImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/ProductImages'), $imageName);
        }

        $product->productImage = $imageName;
        $product->productName = $validatedData['productName'];
        $product->productSize = $validatedData['productSize'];
        $product->productPrice = $validatedData['productPrice'];
        $product->category_id = $validatedData['categoryId'];
        $product->save();

        return redirect()->route('viewProductPage');
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        $product->delete();
        $imagePath = public_path('Images/ProductImages') . '/' . $product->productImage;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        return redirect()->route('viewProductPage');
    }

    /*
    |---------------------------------------------------------------|
    |========================= Deal Functions ======================|
    |---------------------------------------------------------------|
    */

    public function viewDealPage()
    {
        $deals = Deal::with('products')->get();
        return view('Admin.Deal')->with(['dealsData' => $deals]);
    }

    public function viewDealProductsPage()
    {
        $product = Product::all();
        return view('Admin.DealProducts')->with(['Products' => $product]);
    }

    public function viewUpdateDealProductsPage($id)
    {
        dd($id);
        $product = Product::all();
        return view('Admin.UpdateDealProduct')->with(['Products' => $product]);
    }

    public function createDeal(Request $request)
    {
        $deal = new Deal();

        $imageName = null;
        if ($request->hasFile('dealImage')) {
            $image = $request->file('dealImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/DealImages'), $imageName);
        }

        $deal->dealImage = $imageName;
        $deal->dealTitle = $request->dealTitle;
        $deal->dealStatus = $request->dealStatus;
        $deal->dealEndDate = $request->dealEndDate;

        $deal->save();
        session(['id' => $deal->id]);
        return redirect()->route('viewDealProductsPage');
    }

    public function createDealProducts(Request $request)
    {
        $productDetails = [];
        $index = 0;
        while ($request->has("product_name_{$index}")) {
            $productDetails[] = [
                'product_id' => $request->input("product_id{$index}"),
                'quantity' => $request->input("product_quantity_{$index}"),
                'total_price' => $request->input("product_total_price_{$index}"),
            ];
            $index++;
        }

        $deal = Deal::find($request->id);

        // Attach each product to the deal and set pivot data
        foreach ($productDetails as $productDetail) {
            $deal->products()->attach($productDetail['product_id'], [
                'product_quantity' => $productDetail['quantity'],
                'product_total_price' => $productDetail['total_price'],
            ]);
        }

        $deal->dealActualPrice = $request->input('currentDealPrice');
        $deal->dealDiscountedPrice = $request->input('dealFinalPrice') . " " . "Pkr";
        $deal->save();

        return redirect()->route('viewDealPage');
    }


    public function updateDeal(Request $request)
    {
        $deal = Deal::find($request->dId);

        if ($request->hasFile('dealImage')) {

            $existingImagePath = public_path('Images/DealImages') . '/' .  $deal->dealImage;
            File::delete($existingImagePath);

            $image = $request->file('dealImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/DealImages'), $imageName);
            $deal->dealImage = $imageName;
        }

        $deal->dealTitle = $request->dealTitle;
        $deal->dealDiscountedPrice = $request->dealprice . " Pkr";
        $deal->dealStatus = $request->dealStatus;
        $deal->dealEndDate = $request->dealEndDate;

        $deal->save();
        session(['id' => $deal->id]);
        return redirect()->route('viewDealPage');
    }

    public function editDeal($id)
    {
        dd($id);
        $deals = Deal::all();
        return view('Admin.Deal')->with(['dealsData' => $deals]);
    }

    public function updateDealProducts(Request $request)
    {
        dd($request->all());

        $productNames = '';
        $productQuantities = '';
        $productPrices = '';

        $index = 0;

        while ($request->has("product_name_{$index}")) {
            $productName = $request->input("product_name_{$index}");
            $productQuantity = $request->input("product_quantity_{$index}");
            $productPrice = $request->input("product_total_price_{$index}");

            $productNames .= $productName . ', ';
            $productQuantities .= $productQuantity . ', ';
            $productPrices .= $productPrice . ', ';
            $index++;
        }

        $productNames = rtrim($productNames, ', ');
        $productQuantities = rtrim($productQuantities, ', ');
        $productPrices = rtrim($productPrices, ', ');


        $currentDealPrice = $request->input('currentDealPrice');
        $dealFinalPrice = $request->input('dealFinalPrice');

        $deal = Deal::find($request->id);

        $deal->dealProductName = $productNames;
        $deal->dealProductQuantity = $productQuantities;
        $deal->dealProductPrice = $productPrices;
        $deal->dealActualPrice = $currentDealPrice;
        $deal->dealDiscountedPrice = $dealFinalPrice;

        $deal->save();

        return redirect()->route('viewDealPage');
    }

    public function deleteDeal($id)
    {
        $deal = Deal::find($id);
        $deal->delete();
        $imagePath = public_path('Images/DealImages') . '/' . $deal->dealImage;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        return redirect()->route('viewDealPage');
    }

    public function deleteDealProduct($productId, $dealId)
    {
        $deal = Deal::find($dealId);
        $product = $deal->products()->find($productId);

        if (!$deal || !$product) {
            return redirect()->route('viewDealPage')->with('error', 'Deal or product not found.');
        }

        $productPrice = intval($product->productPrice);
        $dealDiscountedPrice = intval(str_replace(' Pkr', '', $deal->dealDiscountedPrice));
        $updatedDealPrice = ($dealDiscountedPrice - $productPrice) . " Pkr";
        $deal->dealDiscountedPrice = $updatedDealPrice;
        $deal->save();

        $product->delete();

        return redirect()->route('viewDealPage')->with('success', 'Product deleted successfully.');
    }

    /*
    |---------------------------------------------------------------|
    |====================== Stock's Functions ======================|
    |---------------------------------------------------------------|
    */

    public function viewStockPage()
    {
        $stockData = Stock::all();
        return view('Admin.Stock')->with(['stockData' => $stockData]);
    }

    public function createStock(Request $request)
    {
        $existingStock = Stock::where('itemName', $request->itemName)->first();

        if ($existingStock) {

            $ESIQ = floatval($existingStock->itemQuantity) < 1000 ? floatval($existingStock->itemQuantity) * 1000 : floatval($existingStock->itemQuantity);         // $ESIQ ==> Existing Stock Item Quantity
            // $ESMIQ = floatval($existingStock->mimimumItemQuantity) ? floatval($existingStock->mimimumItemQuantity) : floatval($existingStock->mimimumItemQuantity);  // $ESMIQ ==> Existing Stock Minimun Item Quantity

            if ($request->unit === "g") {
                $itemQuantity = ($ESIQ + $request->stockQuantity) >= 1000 ? ($ESIQ + $request->stockQuantity) / 1000 . 'kg' : ($ESIQ + $request->stockQuantity) . 'g';
                $mimimumItemQuantity = ($request->minStockQuantity) >= 1000 ? ($request->minStockQuantity) / 1000 . 'kg' : ($request->minStockQuantity) . 'g';
            } else if ($request->unit === "ml") {
                $itemQuantity = ($ESIQ + $request->stockQuantity) >= 1000 ? ($ESIQ + $request->stockQuantity) / 1000 . 'ltr' : ($ESIQ + $request->stockQuantity) . 'ml';
                $mimimumItemQuantity = (+$request->minStockQuantity) >= 1000 ? ($request->minStockQuantity) / 1000 . 'ltr' : ($request->minStockQuantity) . 'ml';
            }

            $existingStock->itemName = $request->itemName;
            $existingStock->itemQuantity = $itemQuantity;
            $existingStock->mimimumItemQuantity = $mimimumItemQuantity;
            $existingStock->unitPrice = $request->unitPrice . ' Pkr';

            $existingStock->save();
            return redirect()->route('viewStockPage');
        } else {
            $newStock = new Stock();

            if ($request->unit === "g") {
                $itemQuantity = $request->stockQuantity >= 1000 ? ($request->stockQuantity / 1000) . 'kg' : $request->stockQuantity . 'g';
                $mimimumItemQuantity = $request->minStockQuantity >= 1000 ? ($request->minStockQuantity / 1000) . 'kg' : $request->minStockQuantity . 'g';
            } else if ($request->unit === "ml") {
                $itemQuantity = $request->stockQuantity >= 1000 ? ($request->stockQuantity / 1000) . 'ltr' : $request->stockQuantity . 'ml';
                $mimimumItemQuantity = $request->minStockQuantity >= 1000 ? ($request->minStockQuantity / 1000) . 'ltr' : $request->minStockQuantity . 'ml';
            }

            $newStock->itemName = $request->itemName;
            $newStock->itemQuantity = $itemQuantity;
            $newStock->mimimumItemQuantity = $mimimumItemQuantity;
            $newStock->unitPrice = $request->unitPrice . ' Pkr';

            $newStock->save();
            return redirect()->route('viewStockPage');
        }
    }

    public function updateStock(Request $request)
    {
        $stockData = Stock::find($request->sId);

        if ($request->unit === "g") {
            $itemQuantity = $request->stockQuantity >= 1000 ? ($request->stockQuantity / 1000) . 'kg' : $request->stockQuantity . 'g';
            $mimimumItemQuantity = $request->minStockQuantity >= 1000 ? ($request->minStockQuantity / 1000) . 'kg' : $request->minStockQuantity . 'g';
        } else if ($request->unit === "ml") {
            $itemQuantity = $request->stockQuantity >= 1000 ? ($request->stockQuantity / 1000) . 'ltr' : $request->stockQuantity . 'ml';
            $mimimumItemQuantity = $request->minStockQuantity >= 1000 ? ($request->minStockQuantity / 1000) . 'ltr' : $request->minStockQuantity . 'ml';
        }

        $stockData->itemName = $request->itemName;
        $stockData->itemQuantity = $itemQuantity;
        $stockData->mimimumItemQuantity = $mimimumItemQuantity;
        $stockData->unitPrice = $request->unitPrice . ' Pkr';

        $stockData->save();
        return redirect()->route('viewStockPage');
    }

    public function deleteStock($id)
    {
        $stockData = Stock::find($id);
        $stockData->delete();

        return redirect()->route('viewStockPage');
    }

    /*
        |---------------------------------------------------------------|
        |======================= Recipe Functions ======================|
        |---------------------------------------------------------------|
        */

    public function viewRecipePage()
    {
        $products = Product::all();
        $stocks = Stock::all();
        return view('Admin.Recipe')->with(['products' => $products, 'stocks' => $stocks]);
    }

    public function createRecipe(Request $request)
    {
        $recipe = new Recipe();
        $product = Product::find($request->pId);

        $productRecipe = $request->input('productRecipe');
        $formattedRecipe = str_replace("\n", "", trim($productRecipe));

        $recipe->productCategory = $product->category_name;
        $recipe->productSize = $product->productSize;
        $recipe->productName = $product->productName;
        $recipe->productRecipe = $formattedRecipe;

        $recipe->save();

        return redirect()->route('viewRecipePage');
    }

    public function deleteRecipe($id)
    {
        $recipe = Recipe::find($id);
        $recipe->delete();

        return redirect()->route('viewRecipePage');
    }

    /*
    |---------------------------------------------------------------|
    |====================== Orders Functions =======================|
    |---------------------------------------------------------------|
    */

    public function viewOrdersPage()
    {
        return view('Admin.Order');
    }

    public function viewStaffPage()
    {
        $staff = User::whereIn('role', ['salesman', 'chef'])->get();
        return view('Admin.Staff')->with(['Staff' => $staff]);
    }

    public function updateStaff(Request $req)
    {
        dd($req->all());
        $validateData = $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:8|confirmed',
        ]);

        $auth = new User();
        $auth->name = $req->name;
        $auth->email = $req->email;
        $auth->role = $req->role;
        $auth->password = Hash::make($req->password);
        $auth->save();

        return redirect()->route('viewStaffPage');
    }
    public function deleteStaff($id)
    {
        $staff = User::find($id);
        $staff->delete();
        return redirect()->route('viewStaffPage');
    }

    /*
    |---------------------------------------------------------------|
    |==================== Dashboard Functions ======================|
    |---------------------------------------------------------------|
    */

    public function totalCategories()
    {
        $Categories = Category::count();
        session(['totalCategories' => $Categories]);
        return $Categories;
    }

    public function totalProducts()
    {
        $Products = Product::count();
        session(['totalProducts' => $Products]);
        return $Products;
    }
}
