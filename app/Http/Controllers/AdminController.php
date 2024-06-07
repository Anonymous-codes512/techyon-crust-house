<?php

namespace App\Http\Controllers;

use App\Models\BranchRevenue;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Handler;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Stock;
use Dompdf\Dompdf;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function viewAdminDashboard()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $totalCategories = $this->totalCategories();
        $totalProducts = $this->totalProducts();
        $totalstocks = $this->totalstocks();

        $totalBranchRevenueData = $this->totalBranchRevenue();
        $branchRevenueArray = $totalBranchRevenueData['branchRevenueArray'];
        $totalRevenue = $totalBranchRevenueData['totalRevenue'];

        return view('Admin.AdminDashboard')->with([
            'totalCategories' => $totalCategories,
            'totalProducts' => $totalProducts,
            'totalStocks' => $totalstocks,
            'branchRevenueArray' => $branchRevenueArray,
            'totalRevenue' => $totalRevenue,
            'title' => 'dash'
        ]);
    }

    public function readNotification($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $notification = Notification::find($id);
        $notification->is_read = true;
        $notification->save();
        return Redirect()->route('viewStockPage');
    }

    public function deleteNotification($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $notification = Notification::find($id);
        $notification->delete();
        return Redirect()->route('viewStockPage');
    }

    public function redirectNotification($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $notification = Notification::find($id);
        $notification->is_read = true;
        $notification->save();

        $stocks = Stock::all();
        return view('Admin.Stock')->with(['stockData' => $stocks, 'notification' => null]);
    }

    /*
    |---------------------------------------------------------------|
    |======================= Category Functions ====================|
    |---------------------------------------------------------------|
    */

    public function viewCategoryPage()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $category = Category::all();
        return view('Admin.Category')->with(['categories' => $category]);
    }

    public function createCategory(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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

        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $validatedData = $request->validate([
            'categoryName' => 'required|string|max:255',
        ]);
        $category = Category::find($request->id);

        if ($request->hasFile('CategoryImage')) {

            $imageName = null;
            $existingImagePath = public_path('Images/CategoryImages') . '/' . $category->categoryImage;
            File::delete($existingImagePath);

            $image = $request->file('CategoryImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/CategoryImages'), $imageName);
            $category->categoryImage = $imageName;
        }

        $category->categoryName = $validatedData['categoryName'];
        $category->save();

        return redirect()->route('viewCategoryPage');
    }

    public function deleteCategory($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }
        $categories = Category::all();
        $products = Product::orderBy('category_name')->get();
        if ($categories->isEmpty()) {
            return view('Admin.Product');
        }

        return view('Admin.Product')->with(['categoryData' => $categories, 'productsData' => $products]);
    }

    public function createProduct(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        [$categoryId, $categoryName] = explode(',', $request->categoryId);

        $total_variations = intval($request->noOfVariations);

        $imageContent = null;
        if ($request->hasFile('productImage')) {
            $uploadedImage = $request->file('productImage');
            $imageContent = file_get_contents($uploadedImage->getRealPath());
        }

        for ($i = 0; $i < $total_variations; $i++) {
            $product = new Product();
            $product->productName = $request->productName;
            $product->productVariation = $request->{"productVariation" . ($i + 1)};
            $product->productPrice = $request->{"price" . ($i + 1)};
            $product->category_id = $categoryId;
            $product->category_name = $categoryName;

            if ($imageContent) {
                $uniqueImageName = time() . '_' . $i . '_' . mt_rand(1000, 9999) . '.' . $uploadedImage->getClientOriginalExtension();
                $destinationPath = public_path('Images/ProductImages/' . $uniqueImageName);

                try {
                    file_put_contents($destinationPath, $imageContent);
                    $product->productImage = $uniqueImageName;
                } catch (\Exception $e) {

                    Log::error('File upload error: ' . $e->getMessage());
                    return response()->json(['error' => 'File upload error: ' . $e->getMessage()], 500);
                }
            }

            $product->save();
        }
        return redirect()->route('viewProductPage');
    }

    public function updateProduct(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $product = Product::find($request->pId);

        if ($request->hasFile('productImage')) {

            $existingImagePath = public_path('Images/ProductImages') . '/' .  $product->productImage;
            File::delete($existingImagePath);

            $imageName = null;
            $image = $request->file('productImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/ProductImages'), $imageName);
            $product->productImage = $imageName;
        }

        $product->productName = $request->productName;
        $product->productVariation = $request->productVariation;
        $product->productPrice = $request->Price;
        $product->save();

        return redirect()->route('viewProductPage');
    }

    public function deleteProduct($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $handlers = Handler::with('deal')->get();
        $deals = $handlers->pluck('deal')->unique();
        $handlersAndProducts = Handler::join('products', 'handlers.product_id', '=', 'products.id')
            ->select('handlers.id as handler_id', 'handlers.*', 'products.*')
            ->get();
        return view('Admin.Deal')->with(['dealsData' => $deals, 'dealProducts' => $handlersAndProducts]);
    }

    public function viewDealProductsPage()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $products = Product::orderBy('category_id')->get();
        return view('Admin.DealProducts')->with(['Products' => $products]);
    }

    public function viewUpdateDealProductsPage($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $products = Product::orderBy('category_id')->get();
        $handler = Handler::find($id);
        return view('Admin.UpdateDealProduct')->with(['Products' => $products, 'dealId' => $id, 'dealproducts' => $handler]);
    }

    public function createDeal(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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

    public function addDealProduct(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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

        $dealActualPrice = intval(str_replace(' Pkr', '', $deal->dealActualPrice));
        $dealPrice = intval(str_replace(' Pkr', '', $deal->dealDiscountedPrice));

        $dealActualPrice = $dealActualPrice + intval(str_replace(' Pkr', '', $request->currentDealPrice));
        $dealPrice = $dealPrice + intval($request->dealFinalPrice);

        $deal->dealActualPrice = $dealActualPrice . " Pkr";
        $deal->dealDiscountedPrice = $dealPrice . " Pkr";

        foreach ($productDetails as $productDetail) {
            $handler = new handler();

            $handler->deal_id = $request->id;
            $handler->product_id = $productDetail['product_id'];
            $handler->product_quantity = $productDetail['quantity'];
            $handler->product_total_price = $productDetail['total_price'];

            $handler->save();
        }

        $deal->save();

        return redirect()->route('viewDealPage');
    }

    public function deleteDeal($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $deal = Deal::find($id);
        $deal->delete();
        $imagePath = public_path('Images/DealImages') . '/' . $deal->dealImage;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        return redirect()->route('viewDealPage');
    }

    public function deleteDealProduct($id, $dId)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $handler = Handler::find($id);
        $deals = Handler::with(['deal', 'product'])->get();

        if (!$handler) {
            return redirect()->route('viewDealPage')->with('error', 'Handler not found.');
        }
        $deal = $handler->deal;


        if (!$deal) {
            return redirect()->route('viewDealPage')->with('error', 'Deal not found.');
        }

        $productPrice = intval($handler->product_total_price);

        $dealActualPrice = intval(str_replace(' Pkr', '', $deal->dealActualPrice));
        $dealDiscountedPrice = intval(str_replace(' Pkr', '', $deal->dealDiscountedPrice));

        $updatedDealActualPrice = ($dealActualPrice - $productPrice) . " Pkr";
        $updatedDealDiscountedPrice = ($dealDiscountedPrice - $productPrice) . " Pkr";

        $deal->dealActualPrice = $updatedDealActualPrice;
        $deal->dealDiscountedPrice = $updatedDealDiscountedPrice;

        $deal->save();
        $handler->delete();

        return redirect()->route('viewDealPage')
            ->with('deals', $deals)
            ->with('deal', $deal);
    }

    /*
    |---------------------------------------------------------------|
    |====================== Stock's Functions ======================|
    |---------------------------------------------------------------|
    */

    public function viewStockPage()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $conversionMap = [
            'g' => 1,           // grams
            'kg' => 1000,       // kilograms
            'mg' => 0.001,      // milligrams
            'lbs' => 453.592,   // pounds
            'oz' => 28.3495,    // ounces
            'ml' => 1,          // milliliters
            'l' => 1000,        // liters
            'liter' => 1000,    // liters
            'gal' => 3785.41,   // gallons
        ];

        $stocks = Stock::all();
        $notifications = [];

        foreach ($stocks as $stock) {
            if (preg_match('/([0-9.]+)\s*([a-zA-Z]+)/', $stock->itemQuantity, $matches)) {
                $quantity = (float)$matches[1];
                $unit = strtolower($matches[2]);
            } else {
                continue;
            }

            if (preg_match('/([0-9.]+)\s*([a-zA-Z]+)/', $stock->mimimumItemQuantity, $minMatches)) {
                $minimumQuantity = (float)$minMatches[1];
                $minimumUnit = strtolower($minMatches[2]);
            } else {
                continue;
            }

            $quantityInBaseUnit = isset($conversionMap[$unit]) ? $quantity * $conversionMap[$unit] : $quantity;
            $minimumQuantityInBaseUnit = isset($conversionMap[$minimumUnit]) ? $minimumQuantity * $conversionMap[$minimumUnit] : $minimumQuantity;

            if ($quantityInBaseUnit <= $minimumQuantityInBaseUnit) {
                $notificationMessage = "Quantity of {$stock->itemName} is below or equal to the minimum level";
                Notification::create(['message' => $notificationMessage]);
                $notifications[] = $notificationMessage;
            }
        }

        $notify = Notification::where('is_read', false)->get();
        session(['Notifications' => $notify]);
        return view('Admin.Stock')->with(['stockData' => $stocks, 'notification' => $notifications]);
    }

    public function createStock(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $existingStock = Stock::where('itemName', $request->itemName)->first();

        if ($existingStock) {
            preg_match('/([0-9.]+)\s*([a-zA-Z]+)/', $existingStock->itemQuantity, $matches);
            $quantity = (float) ($matches[1] ?? 0);
            $unit = strtolower($matches[2] ?? 'unit');
            $conversionMap = [
                'g' => 1,
                'kg' => 1000,
                'mg' => 0.001,
                'lbs' => 453.592,
                'oz' => 28.3495,
                'ml' => 1,
                'liter' => 1000,
                'gal' => 3785.41,
            ];

            $isLiquidUnit = in_array($unit, ['ml', 'liter', 'gal']);
            $quantityInBaseUnit = isset($conversionMap[$unit]) ? $quantity * $conversionMap[$unit] : $quantity;
            $incomingQuantityInBaseUnit = isset($conversionMap[$request->unit1]) ? $request->stockQuantity * $conversionMap[$request->unit1] : $request->stockQuantity;
            $totalQuantityInBaseUnit = $quantityInBaseUnit + $incomingQuantityInBaseUnit;

            $updatedQuantity = $totalQuantityInBaseUnit / $conversionMap[$unit];

            $existingStock->itemQuantity = $updatedQuantity . $unit;
            $existingStock->mimimumItemQuantity = $request->minStockQuantity . $request->unit2;
            $existingStock->unitPrice = $request->unitPrice . ' Pkr';

            $existingStock->save();
            return redirect()->route('viewStockPage');
        } else {
            $newStock = new Stock();
            $newStock->itemName = $request->itemName;
            $newStock->itemQuantity = $request->stockQuantity . $request->unit1;
            $newStock->mimimumItemQuantity = $request->minStockQuantity . $request->unit2;
            $newStock->unitPrice = $request->unitPrice . ' Pkr';

            $newStock->save();
            return redirect()->route('viewStockPage');
        }
    }

    public function updateStock(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }
        $stockData = Stock::find($request->sId);

        $stockData->itemName = $request->itemName;
        $stockData->itemQuantity = $request->stockQuantity . $request->unit1;
        $stockData->mimimumItemQuantity = $request->minStockQuantity . $request->unit2;
        $stockData->unitPrice = $request->unitPrice . ' Pkr';

        $stockData->save();
        return redirect()->route('viewStockPage');
    }

    public function deleteStock($id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }
        $categories = Category::all();
        $stocks = Stock::all();
        session(['showproductRecipe' => false]);
        return view('Admin.Recipe')->with(['categoryProducts' => null, 'categories' => $categories, 'stocks' => $stocks, 'recipes' => null]);
    }

    public function createRecipe(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $requestData = $request->all();

        $category_id = $requestData['cId'];
        $product_id = $requestData['pId'];

        $recipeItems = array_filter(explode(',', $requestData['recipeItems']), function ($item) {
            return trim($item) !== '';
        });
        foreach ($recipeItems as $item) {
            $itemParts = explode('~', trim($item));
            if (count($itemParts) == 2) {
                $quantity = trim($itemParts[0]);
                $stockId = trim($itemParts[1]);

                $newRecipe = new Recipe();

                $newRecipe->category_id = $category_id;
                $newRecipe->product_id = $product_id;
                $newRecipe->stock_id = $stockId;
                $newRecipe->quantity = $quantity;

                $newRecipe->save();
            }
        }
        return redirect()->route('viewRecipePage');
    }

    public function viewProductRecipe($category_id, $product_id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $recipes = Recipe::where('product_id', $product_id)->where('category_id', $category_id)->with('stock', 'product')->get();

        $products = Product::with('category')->get();
        $categories = $products->pluck('category')->unique();
        $stocks = Stock::all();
        session(['showproductRecipe' => true]);

        return view('Admin.Recipe')->with([
            'categoryProducts' => $products,
            'categories' => $categories,
            'stocks' => $stocks,
            'recipes' => $recipes
        ]);
    }

    public function deleteStockFromRecipe($id, $cId, $pId)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $recipe = Recipe::find($id);
        if ($recipe) {
            $recipe->delete();
        }

        return redirect()->route('viewProductRecipe', ['category_id' => $cId, 'product_id' => $pId]);
    }

    public function showCategoryProducts($category_id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }
        $categories = Category::all();
        $stocks = Stock::all();
        $categoryProducts = Product::where('category_id', $category_id)->get();
        return view('Admin.Recipe')->with(['categoryProducts' => $categoryProducts, 'categories' => $categories, 'stocks' => $stocks, 'recipes' => null]);
    }

    /*
    |---------------------------------------------------------------|
    |====================== Orders Functions =======================|
    |---------------------------------------------------------------|
    */

    public function viewOrdersPage()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }
        $orders = Order::with('salesman')->get();
        return view('Admin.Order')->with(['orders' => $orders, 'orderItems' => null]);
    }

    public function viewOrderProducts($order_id)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $orders = Order::with('salesman')->get();
        $orderItems = OrderItem::where('order_id', $order_id)->get();
        return view('Admin.Order')->with(['orders' => $orders, 'orderItems' => $orderItems]);
    }
    
    public function printRecipt($order_id){
        $order = Order::with('salesman')->where('id', $order_id)->first();
        $products = OrderItem::where('order_id', $order_id)->get();
        $html = view('reciept', ['products' => $products, 'orderData'=>$order])->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $height = $dompdf->getCanvas()->get_height();
        $dompdf->setPaper([0, 0, 300, $height], 'portrait');
        $dompdf->render();
        $dompdf->stream($order->order_number.'.pdf');
    }

    public function cancelOrder($order_id){
        $order = Order::with('salesman')->where('id', $order_id)->first();
        $order->status = 3;
        $order->save();
        return redirect()->back();
    }

    public function viewStaffPage()
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

        $staff = User::whereIn('role', ['salesman', 'chef'])->get();
        return view('Admin.Staff')->with(['Staff' => $staff]);
    }

    public function updateStaff(Request $req)
    {
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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
        if (!session()->has('admin')) {
            return redirect()->route('viewLoginPage');
        }

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

    public function totalstocks()
    {
        $Stocks = Stock::count();
        session(['totalStocks' => $Stocks]);
        return $Stocks;
    }

    public function totalBranchRevenue()
    {
        $branchRevenues = BranchRevenue::all();
        $branchRevenueArray = [];
        $totalRevenue = 0;
        foreach ($branchRevenues as $revenue) {
            $branchName = $revenue->branch_name;
            $revenueValue = (int)str_replace(' Pkr', '', $revenue->monthly_revenue);
            if (isset($branchRevenueArray[$branchName])) {
                $branchRevenueArray[$branchName][] = $revenueValue;
            } else {
                $branchRevenueArray[$branchName] = [$revenueValue];
            }
            $totalRevenue += $revenueValue;
        }
        session(['totalRevenue' => $totalRevenue . ' Pkr']);

        return [
            'branchRevenueArray' => $branchRevenueArray,
            'totalRevenue' => $totalRevenue
        ];
    }
}

    /* public function viewStockPage()
    {
        $stocks = Stock::all();
        $notifications = [];
        foreach ($stocks as $stock) {
            $quantity = (int)preg_replace('/[^0-9]/', '', $stock->itemQuantity);
            $unit = preg_replace('/[0-9\s]/', '', $stock->itemQuantity);
            $minimumQuantity = (int)preg_replace('/[^0-9]/', '', $stock->mimimumItemQuantity);
            $minimumUnit = preg_replace('/[0-9\s]/', '', $stock->mimimumItemQuantity);

            $quantity = $unit === "Kg" ? $quantity * 1000 : $quantity;
            $minimumQuantity = $minimumUnit === "Kg" ? $minimumQuantity * 1000 : $minimumQuantity;
            
            if ($quantity <= $minimumQuantity) {
                $notificationMessage = "Quantity of {$stock->itemName} is below or equal to the minimum level";
                Notification::create(['message' => $notificationMessage]);
                $notifications[] = $notificationMessage;
            }
        }
  
        $notify = Notification::where('is_read', false)->get();
        session(['Notifications' => $notify]);
        return view('Admin.Stock')->with(['stockData' => $stocks, 'notification' => $notifications]);
    }*/
