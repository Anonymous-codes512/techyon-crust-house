<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    * {
        margin: 0;
        box-sizing: border-box;
        font-family: "VT323", monospace;
        color: #111;
        font-weight: bold;
    }

    .container {

        background: #f1f1f1;
        padding: 20px 10px;
        font-size: 12px;
    }

    .bold {
        font-weight: bold;
    }

    .center {
        text-align: center;
    }

    .receipt {
        width: 300px;
        /* min-height: 50vh; */
        /* height: 100%; */
        background: #fff;
        margin: 0 auto;
        box-shadow: 5px 5px 19px #ccc;
        padding: 10px;
    }

    .logo {
        text-align: center;
        padding: 5px;
    }

    .barcode {
        font-family: "Libre Barcode 128", cursive;
        font-size: 1vw;
        text-align: center;
    }

    .address {
        text-align: left;
        margin-bottom: 10px;
    }

    .cashier {
        text-align: left;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .details {
        display: flex;
        justify-content: space-between;
        margin: 0 0 0;
    }

    .transactionDetails {
        /* border: 1px solid black; */
        display: flex;
        justify-content: space-between;
        margin: 0 10px 0;
        font-size: 12px;

        /* background-color: rgb(207, 122, 17); */
    }

    .transactionDetails .id {
        text-transform: uppercase;
        margin-bottom: 0;
        /* background-color: rgb(255, 0, 76); */
        width: 10%;
    }

    .transactionDetails .detail {
        text-transform: uppercase;
        font-size: 12px;
        margin-bottom: 0;
        font-weight: bold;
        /* background-color: aqua; */
        width: 40%;
    }

    .transactionDetails .quantity {
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        margin: 0 10px;
        width: 10%;
        /* background-color: rgb(0, 255, 13); */

    }

    .transactionDetails .price {
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        margin: 0 10px;
        width: 30%;
        /* background-color: rgb(255, 0, 179); */

    }

    .centerItem {
        display: flex;
        justify-content: center;
        margin-bottom: 8px;
    }

    .survey {
        text-align: center;
        margin-bottom: 12px;
        font-size: 22px;
    }

    .survey .surveyID {
        font-size: 15px;
        margin-top: 10px;
        font-weight: bold;
    }

    .paymentDetails {
        display: flex;
        justify-content: space-between;
        /* margin: 0 auto; */
        width: 165px;
    }

    .creditDetails {
        margin: 10px auto;
        width: 230px;
        font-size: 14px;
        text-transform: uppercase;
    }

    .receiptBarcode {
        margin: 10px 0;
        text-align: center;
    }

    .returnPolicy {
        text-align: center;
        font-size: 14px;
        margin: 10px 10px;
        width: 250px;
        display: flex;
        justify-content: space-between;
    }

    .returnPolicy .detail {
        text-align: left;
        font-size: 10px;
        margin: 0px 20px;
        width: 200px;
        display: flex;
        justify-content: space-between;
    }

    .coupons {
        margin-top: 20px;
    }

    .tripSummary {
        margin: auto;
        width: 255px;
    }

    .tripSummary .item {
        display: flex;
        justify-content: space-between;
        margin: auto;
        width: 220px;
    }

    .feedback {
        margin: 20px auto;
    }

    .feedback h3.clickBait {
        font-size: 1vw;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
    }

    .feedback h4.web {
        font-size: 1rem;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
    }

    .feedback .break {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0;
    }

    .couponContainer {
        border-top: 1px dashed #1f1f1f;
        margin-bottom: 20px;
    }

    .couponContainer .discount {
        font-size: 35px;
        text-align: center;
        margin-bottom: 10px;
    }

    .couponContainer .discountDetails {
        font-size: 20px;
        text-align: center;
        margin-bottom: 15px;
    }

    .couponContainer .barcode {
        margin: 10px 0 0;
    }

    .couponContainer .legal {
        font-size: 12px;
        margin-bottom: 12px;
    }

    .couponContainer .barcodeID {
        margin-bottom: 8px;
    }

    .couponContainer .expiration {
        display: flex;
        justify-content: space-between;
        margin: 10px;
    }

    table thead {
        border-bottom: 2px solid black;
    }

    .couponContainer .couponBottom {
        font-size: 13px;
        text-align: center;
    }
</style>

<body>
    @php
        date_default_timezone_set('Asia/Karachi');
        $orderData = $orderData;
        $date_time = $orderData->created_at;
        $date = date('F d, Y', strtotime($date_time));
        $time = date('g:i A', strtotime($date_time));

        $subtotal = 0.0;
    @endphp

    <div id="showScroll" class="container">
        <div class="receipt">
            <h1 class="logo">Crust House</h1>

            <div class="address">
                Minar Road, Wah, Pk
            </div>

            <div class="date-time">
                <div class="detail">Date : {{ $date }}</div>
                <div class="detail">Time : {{ $time }}</div>
            </div>

            <br>
            <div class="details">
                <div class="detail">{{ $orderData->ordertype }}</div>
            </div>
            <br>
            <div class="details">
                <div class="detail cashier">Helped by:{{ $orderData->salesman->name }}</div>
                <div class="detail ordernumber">Order # {{ $orderData->order_number }}</div>
            </div>

            <br>

            <div style="border-bottom: 1px solid black;">
                <div class="transactionDetails" style="font-weight: bold; border-bottom:1px solid black;">
                    <table>
                        <thead class="border-black">
                            <tr>
                                <th>No.</th>
                                <th>NAME</th>
                                <th> QTY</th>
                                <th> PRICE</th>
                            </tr>
                        </thead>
                        <br>
                        <tbody>
                            @foreach ($products as $i => $item)
                                @php
                                    preg_match('/\d+(\.\d+)?/', $item->total_price, $matches);
                                    $numericPart = $matches[0];
                                    $subtotal += $numericPart;

                                @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td style="padding-left: 0.5vw;">{{ $item->product_quantity }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="survey">
                    <div class="surveyID">
                        Recipt # {{ $orderData->order_number }}
                    </div>

                </div>
                <br>

                <div class="paymentDetails">
                    <div class="detail">SUBTOTAL: {{ $subtotal }}</div>
                </div>
                {{-- <br> --}}
                {{-- <div class="paymentDetails">
                    <div class="detail">HI 4.0% TAX: 3.02</div>
                </div> --}}
                <br>
                <div class="paymentDetails bold">
                    <div class="detail">TOTAL: {{ $subtotal }}</div>
                </div>
                <br>
                <div class="paymentDetails">
                    <div class="detail">CASH: {{ $orderData->received_cash }}</div>
                </div>
                <br>
                <div class="paymentDetails">
                    <div class="detail">CHANGE: {{ $orderData->return_change }}</div>
                </div>

                {{-- <div class="returnPolicy bold">
                        Returns with receipt, subject to CVS Return Policy, thru 08/30/2024
                        Refund amount is based on the price after all coupons and discounts.
                    </div> --}}

                <div class="feedback">
                    <div class="break">
                        *****************************
                    </div>
                    <p class="center">
                        We would love to hear your feedback on your recent experience with us. This survey will only
                        take 1 minute to complete.
                    </p>
                    <h3 class="clickBait">Share Your Feedback</h3>
                    <h4 class="web">www.Crusthouse.com</h4>
                    <p class="center">
                        Enjoy your Meal.!
                    </p>
                    <div class="break">
                        *****************************
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>





{{-- 
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

    $this->createCustomerPDF( $order->id, $newOrderNumber);
    $this->createKitchenPDF( $order->id, $newOrderNumber);

    return redirect()->back();
}
public function createCustomerPDF($order_id, $orderNumber){
    $products = OrderItem::where('order_id', $order_id)->get();
    $order = Order::with('salesman')->where('id', $order_id)->first();
    $customerRecipt = view('reciept', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    $dompdf1 = new Dompdf();
    $dompdf1->loadHtml($customerRecipt);
    $height = $dompdf1->getCanvas()->get_height();
    $dompdf1->setPaper([0, 0, 300, $height], 'portrait');
    $dompdf1->render();

    $dompdf1->stream($orderNumber . '.pdf');
}
public function createKitchenPDF($order_id, $orderNumber){
    $products = OrderItem::where('order_id', $order_id)->get();
    $order = Order::with('salesman')->where('id', $order_id)->first();
    $KitchenRecipt = view('KitchenRecipt', ['products' => $products, 'saleman' => $order->salesman->name, 'ordernumber' => $order->order_number])->render();
    $dompdf2 = new Dompdf();
    $dompdf2->loadHtml($KitchenRecipt);
    $height = $dompdf2->getCanvas()->get_height();
    $dompdf2->setPaper([0, 0, 300, $height], 'portrait');
    $dompdf2->render();

    $dompdf2->stream($orderNumber . '.pdf');

} --}}
