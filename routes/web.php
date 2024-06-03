<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\SalesmanController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------|
|======================= Owner's Routes ========================|
|---------------------------------------------------------------|
*/

Route::get('/',[AuthController::class, 'loginIndex'])->name('viewLoginPage');
Route::get('/viewRegisterPage',[AuthController::class, 'registrationIndex'])->name('viewRegisterPage');

Route::post('/storeRegistrationData',[AuthController::class, 'register'])->name('storeRegistrationData');
Route::post('/login',[AuthController::class, 'login'])->name('login');

Route::get('/logout',function(){
    return view('Auth.Login');
})->name('logout');

Route::get('/dashboard', [OwnerController::class, 'viewOwnerDashboard'])->name('dashboard');

// Route::get('/dashboard2', function () {
//     return view('Owner.Dashboard2');
// }); 

Route::get('/addnewbranch', [OwnerController ::class, 'addNewBranchIndex'])->name('addNewBranch');
Route::post('/storeNewBranchData',[OwnerController::class,'newBranch'])->name('storeNewBranchData');
Route::get('/branchesDashboard', [OwnerController::class, 'viewBranchesDashboard'])->name('branchesDashboard');

Route::get('/addnewbranch1', function(){
    return view('Owner.AddNewBranch1');
});

Route::get('/addnewbranch2', function(){
    return view('Owner.AddNewBranch2');
});

Route::get('/addnewbranch3', function(){
    return view('Owner.AddNewBranch3');
});

Route::get('/mystaff', function(){
    return view('Owner.MyStaff');
})->name('staff');

Route::get('/onlineorders', function(){
    return view('Owner.OnlineOrder');
})->name('onlineorders');

Route::get('/diningtable', function(){
    return view('Owner.DiningTable');
})->name('diningtable');


/*
|---------------------------------------------------------------|
|======================= Admin's Routes ========================|
|---------------------------------------------------------------|
*/

Route::get('/admindashboard',[AdminController::class,'viewAdminDashboard'])->name('admindashboard');
Route::get('/readNotification/{id}',[AdminController::class,'readNotification'])->name('readNotification');
Route::get('/deleteNotification/{id}',[AdminController::class,'deleteNotification'])->name('deleteNotification');
Route::get('/redirectNotification/{id}',[AdminController::class,'redirectNotification'])->name('redirectNotification');

/*
|---------------------------------------------------------------|
|======================= Categories Routes =====================|
|---------------------------------------------------------------|
*/

Route::get('/viewCategoryPage',[AdminController::class,'viewCategoryPage'])->name('viewCategoryPage');
Route::post('/createCategory',[AdminController::class,'createCategory'])->name('createCategory');
Route::post('/updateCategory',[AdminController::class,'updateCategory'])->name('updateCategory');
Route::get('/deleteCategory/{id}',[AdminController::class,'deleteCategory'])->name('deleteCategory');

/*
|---------------------------------------------------------------|
|======================= Products Routes =======================|
|---------------------------------------------------------------|
*/

Route::get('/viewProductPage', [AdminController::class,'viewProductPage'])->name('viewProductPage');
Route::post('/createProduct', [AdminController::class,'createProduct'])->name('createProduct');
Route::post('/updateProduct',[AdminController::class,'updateProduct'])->name('updateProduct');
Route::get('/deleteProduct/{id}',[AdminController::class,'deleteProduct'])->name('deleteProduct');

/*
|---------------------------------------------------------------|
|========================== Deals Routes =======================|
|---------------------------------------------------------------|
*/

Route::get('/viewDealPage', [AdminController::class,'viewDealPage'])->name('viewDealPage');
Route::post('/createDeal', [AdminController::class,'createDeal'])->name('createDeal');
Route::get('/viewDealProductsPage', [AdminController::class,'viewDealProductsPage'])->name('viewDealProductsPage');
Route::post('/createDealProducts', [AdminController::class,'createDealProducts'])->name('createDealProducts');
Route::post('/updateDeal',[AdminController::class,'updateDeal'])->name('updateDeal');
Route::get('/viewUpdateDealProductsPage/{id}', [AdminController::class,'viewUpdateDealProductsPage'])->name('viewUpdateDealProductsPage');
// Route::get('/viewUpdateDealProductsPage', [AdminController::class,'viewUpdateDealProductsPage'])->name('viewUpdateDealProductsPage');
Route::post('/addDealProduct',[AdminController::class,'addDealProduct'])->name('addDealProduct');
// Route::get('/editDeal/{id}',[AdminController::class,'editDeal'])->name('editDeal');
Route::get('/deleteDeal/{id}',[AdminController::class,'deleteDeal'])->name('deleteDeal');
Route::get('/deleteDealProduct/{id}/{dId}',[AdminController::class,'deleteDealProduct'])->name('deleteDealProduct');

/*
|---------------------------------------------------------------|
|========================= Stock Routes ========================|
|---------------------------------------------------------------|
*/

Route::get('/viewStockPage', [AdminController::class,'viewStockPage'])->name('viewStockPage');
Route::post('/createStock', [AdminController::class,'createStock'])->name('createStock');
Route::post('/updateStock',[AdminController::class,'updateStock'])->name('updateStock');
Route::get('/deleteStock/{id}',[AdminController::class,'deleteStock'])->name('deleteStock');

/*
|---------------------------------------------------------------|
|========================= Recipe Routes =======================|
|---------------------------------------------------------------|
*/

Route::get('/viewRecipePage', [AdminController::class,'viewRecipePage'])->name('viewRecipePage');
Route::post('/createRecipe', [AdminController::class,'createRecipe'])->name('createRecipe');
Route::get('/viewProductRecipe/{category_id}/{product_id}', [AdminController::class,'viewProductRecipe'])->name('viewProductRecipe');
Route::get('/deleteStockFromRecipe/{id}/{cid}/{pId}', [AdminController::class, 'deleteStockFromRecipe'])->name('deleteStockFromRecipe');

/*
|---------------------------------------------------------------|
|==================== Orders And Staff Routes ==================|
|---------------------------------------------------------------|
*/

Route::get('/viewOrdersPage', [AdminController::class,'viewOrdersPage'])->name('viewOrdersPage');
Route::get('/viewStaffPage', [AdminController::class,'viewStaffPage'])->name('viewStaffPage');
Route::get('/updateStaff', [AdminController::class,'updateStaff'])->name('updateStaff');
Route::get('/deleteStaff/{id}', [AdminController::class,'deleteStaff'])->name('deleteStaff');

/*
|---------------------------------------------------------------|
|==================== Sales Man Routes =========================|
|---------------------------------------------------------------|
*/

Route::get('salesman/dashboard/{id}', [SalesmanController::class,'viewSalesmanDashboard'])->name('salesman_dashboard');
Route::get('salesman/dashboard/{categoryName}/{id}', [SalesmanController::class,'salesmanCategoryDashboard'])->name('salesman_dash');
Route::get('salesman/deals/', [SalesmanController::class,'deals'])->name('deals');
Route::get('salesman/placeOrder/{salesman_id}', [SalesmanController::class,'placeOrder'])->name('placeOrder');
Route::post('salesman/saveToCart', [SalesmanController::class,'saveToCart'])->name('saveToCart');
Route::get('salesman/clearCart/{salesman_id}', [SalesmanController::class,'clearCart'])->name('clearCart');
Route::get('salesman/removeOneProduct/{id}/{salesman_id}', [SalesmanController::class,'removeOneProduct'])->name('removeOneProduct');
Route::get('salesman/increaseQuantity/{id}/{salesman_id}', [SalesmanController::class,'increaseQuantity'])->name('increaseQuantity');
Route::get('salesman/decreaseQuantity/{id}/{salesman_id}', [SalesmanController::class,'decreaseQuantity'])->name('decreaseQuantity');