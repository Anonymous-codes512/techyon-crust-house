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
        color: #1f1f1f;
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
        /* min-height: 40vh; */
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
        display: flex;
        justify-content: space-between;
        margin: 0 10px 0;
        font-size: 15px;

    }

    .transactionDetails .id {
        text-transform: uppercase;
        margin-bottom: 0;
        width: 10%;
    }

    .transactionDetails .detail {
        text-transform: uppercase;
        /* font-size: 12px; */
        margin-bottom: 0;
        font-weight: bold;
        width: 80%;
    }

    .transactionDetails .quantity {
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        margin: 0 10px;
        width: 10%;

    }

    .transactionDetails .price {
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        margin: 0 10px;
        width: 30%;

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
    }

    .paymentDetails {
        display: flex;
        justify-content: space-between;
        margin: 0 auto;
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
        font-weight: 600;
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

    .couponContainer .couponBottom {
        font-size: 13px;
        text-align: center;
    }
</style>

<body>

    <div id="showScroll" class="container">
        <div class="receipt">
            <h1 class="logo">Crust House</h1>
            <div class="address">
                <div class="address">
                    Crust House Bar-Code: 808 STORE: 332
                </div>
                <div class="details">
                    <div class="detail">Dine In/Take Away</div>
                    <div class="detail">Table no:12</div>
                </div>

                <div class="details">
                    <div style="display:inline; text-align:left;" class="cashier">Helped by:{{ $saleman }}</div>
                    <div style="display:inline; text-align:right;" class="detail">Order # {{ $ordernumber }}</div>
                </div>

                <div style="border-bottom: 1px solid black;">
                    <div class="transactionDetails" style="font-weight: bold; border-bottom:1px solid black;">
                        <table>
                            <thead class="border-black">
                                <tr>
                                    <th>No.</th>
                                    <th>NAME</th>
                                    <th>QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->product_quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="survey">
                    <div class="surveyID">
                        Recipt # {{ $ordernumber }}
                    </div>
                </div>
                <div class="returnPolicy">
                    <div class="detail">MAY 31, 2024</div>
                    <div class="detail">6:48 PM</div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
