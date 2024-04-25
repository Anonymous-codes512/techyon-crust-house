<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
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
Route::get('/viewUpdateDealProductsPage', [AdminController::class,'viewUpdateDealProductsPage'])->name('viewUpdateDealProductsPage');
Route::post('/updateDealProducts',[AdminController::class,'updateDealProducts'])->name('updateDealProducts');
Route::get('/deleteDeal/{id}',[AdminController::class,'deleteDeal'])->name('deleteDeal');

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
|========================= Stock Routes ========================|
|---------------------------------------------------------------|
*/

Route::get('/viewRecipePage', [AdminController::class,'viewRecipePage'])->name('viewRecipePage');
Route::post('/createRecipe', [AdminController::class,'createRecipe'])->name('createRecipe');
Route::get('/deleteRecipe/{id}',[AdminController::class,'deleteRecipe'])->name('deleteRecipe');
// Route::post('/updateStock',[AdminController::class,'updateStock'])->name('updateStock');