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

    .receipt {
        width: 300px;
        background: #fff;
        margin: 0 auto;
        box-shadow: 5px 5px 19px #ccc;
        padding: 10px;
    }

    .logo {
        text-align: center;
        padding: 5px;
    }

    .transactionDetails {
        margin: 0 10px 0;
        font-size: 15px;
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
</style>


<body>
    @php
        date_default_timezone_set('Asia/Karachi');
        $orderData = $orderData;
        $date_time = $orderData->created_at;
        $date = date('F d, Y', strtotime($date_time));
        $time = date('g:i A', strtotime($date_time));

    @endphp
    <div id="showScroll" class="container">
        <div class="receipt">
            <h1 class="logo">Crust House</h1>

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
                        <thead>
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
                                    <td style="padding-left: 0.5vw;">{{ $item->product_quantity }}</td>
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
            </div>
        </div>
    </div>

</body>

</html>
