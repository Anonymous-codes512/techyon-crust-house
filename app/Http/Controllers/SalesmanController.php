<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Handler;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

class SalesmanController extends Controller
{
    public function viewSalesmanDashboard($id)
    {
        if (!session()->has('salesman')) {
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
        if (!session()->has('salesman')) {
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

// public function placeOrder($salesman_id)
    // {
    //     if (!session()->has('salesman')) {
    //         return redirect()->route('viewLoginPage');
    //     }

    //     $newOrderNumber = 0;

    //     $lastOrder = Order::orderBy('id', 'desc')->first();
    //     if ($lastOrder) {
    //         $lastOrderNumber = intval(substr($lastOrder->order_number, 3));
    //         $newOrderNumber = 'CH-' . sprintf('%03d', ($lastOrderNumber + 1));
    //     } else {
    //         $newOrderNumber = 'CH-100';
    //     }

    //     $order = new Order();
    //     $cartedProducts = Cart::where('salesman_id', $salesman_id)->get();
    //     $totalBill = 0.0;

    //     $order->order_number = $newOrderNumber;
    //     $order->total_bill = $totalBill;
    //     $order->salesman_id = $salesman_id;
    //     $order->save();

    //     foreach ($cartedProducts as $cartItem) {
    //         preg_match('/\d+(\.\d+)?/', $cartItem->totalPrice, $matches);
    //         $numericPart = $matches[0];
    //         $totalProductPrice = floatval($numericPart);
    //         $quantity = intval($cartItem->productQuantity);
    //         $totalBill += $totalProductPrice;

    //         $orderItem = new OrderItem();
    //         $orderItem->order_id = $order->id;
    //         $orderItem->order_number = $newOrderNumber;
    //         $orderItem->product_name = $cartItem->productName;
    //         $orderItem->product_variation = $cartItem->productVariation;
    //         $orderItem->addons = $cartItem->productAddon;
    //         $orderItem->product_price = 'Rs. ' . ($totalProductPrice / $quantity);
    //         $orderItem->product_quantity = $quantity;
    //         $orderItem->total_price = $cartItem->totalPrice;
    //         $orderItem->save();
    //     }

    //     foreach ($cartedProducts as $cartItem) {
    //         $cartItem->delete();
    //     }

    //     $order->total_bill = 'Rs. ' . $totalBill;
    //     $order->save();


    //     $products = OrderItem::where('order_id', $order->id)->get();
    //     $customerRecipt = view('reciept', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    //     $dompdf1 = new Dompdf();
    //     $dompdf1->loadHtml($customerRecipt);
    //     $height = $dompdf1->getCanvas()->get_height();
    //     $dompdf1->setPaper([0, 0, 300, $height], 'portrait');
    //     $dompdf1->render();

    //     $dompdf1->stream($newOrderNumber . '.pdf');

    //     $KitchenRecipt = view('KitchenRecipt', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    //     $dompdf2 = new Dompdf();
    //     $dompdf2->loadHtml($KitchenRecipt);
    //     $height = $dompdf2->getCanvas()->get_height();
    //     $dompdf2->setPaper([0, 0, 300, $height], 'portrait');
    //     $dompdf2->render();

    //     $dompdf2->stream($newOrderNumber . '.pdf');

    //     return redirect()->back();
// }

public function placeOrder($salesman_id)
{
    if (!session()->has('salesman')) {
        return redirect()->route('viewLoginPage');
    }

    $newOrderNumber = 0;

    $lastOrder = Order::orderBy('id', 'desc')->first();
    if ($lastOrder) {
        $lastOrderNumber = intval(substr($lastOrder->order_number, 3));
        $newOrderNumber = 'CH-' . sprintf('%03d', ($lastOrderNumber + 1));
    } else {
        $newOrderNumber = 'CH-100';
    }

    $order = new Order();
    $cartedProducts = Cart::where('salesman_id', $salesman_id)->get();
    $totalBill = 0.0;

    $order->order_number = $newOrderNumber;
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
        $orderItem->order_number = $newOrderNumber;
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

    $products = OrderItem::where('order_id', $order->id)->get();

    $customerRecipt = view('reciept', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    $dompdf1 = new Dompdf();
    $dompdf1->loadHtml($customerRecipt);
    $dompdf1->setPaper([0, 0, 300, 675, 'portrait']);
    $dompdf1->render();
    $customerPdfContent = $dompdf1->output();

    $KitchenRecipt = view('KitchenRecipt', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    $dompdf2 = new Dompdf();
    $dompdf2->loadHtml($KitchenRecipt);
    $dompdf2->setPaper([0, 0, 300, 675,'portrait']);
    $dompdf2->render();
    $kitchenPdfContent = $dompdf2->output();

    $customerPdfPath = storage_path('app/public/') . $newOrderNumber . '_customer.pdf';
    $kitchenPdfPath = storage_path('app/public/') . $newOrderNumber . '_kitchen.pdf';
    file_put_contents($customerPdfPath, $customerPdfContent);
    file_put_contents($kitchenPdfPath, $kitchenPdfContent);

    $pdf = new Fpdi();
    $pdf->AddPage('P', [105, 180]);
    $pdf->setSourceFile($customerPdfPath);
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId);

    $pdf->AddPage('P', [105, 105]);
    $pdf->setSourceFile($kitchenPdfPath);
    $tplId = $pdf->importPage(1);
    $pdf->useTemplate($tplId);

    $combinedPdfPath = storage_path('app/public/') . $newOrderNumber . '_combined.pdf';
    $pdf->Output($combinedPdfPath, 'F');

    unlink($customerPdfPath);
    unlink($kitchenPdfPath);

    return response()->download($combinedPdfPath)->deleteFileAfterSend(true);
}


    // private function streamPdfContent($content)
    // {
    //     $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
    //     file_put_contents($tempFile, $content);
    //     return $tempFile;
    // }




    public function saveToCart(Request $request)
    {
        if (!session()->has('salesman')) {
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
        if (!session()->has('salesman')) {
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
        if (!session()->has('salesman')) {
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
        if (!session()->has('salesman')) {
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
        if (!session()->has('salesman')) {
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
