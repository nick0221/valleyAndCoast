<html>
<head>
    <title>Invoice</title>
    <style>
        body, *{
            font-family: "Calibri Light", sans-serif;
        }

        h1 {
            padding: 0;
            margin: 0;
        }
        h1 span {
            font: 1rem bold sans-serif;
            padding: 0;
            margin: 0;


        }
        .table-bordered {
            border: 1px solid #ddd !important;
        }

        table caption {
            padding: .5em 0;
        }

        @media screen and (max-width: 767px) {
            table caption {
                display: none;
            }
        }

        .p {
            text-align: center;
            padding-top: 140px;

        }
        .details{
            font-size: 14px;

        }
        .small{
            font-size: 11px;
            font-style: italic;

        }


        hr{
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        }
    </style>
</head>
<body>
    <h1>
        Valley & Coast Hotel
        <br>

    </h1>
    <span> Contact: </span> <br>
    <span> Address: </span>


    <hr>
    <h2>Invoice</h2>
<p class="details">
    Invoice number: {{ $data['invoice_number'] }} <br>
    Booking Reference: {{ $data['bookingReference'] }} <br>
    Checkin: {{ $data['checkIn'] }}  <br>
    Check Out:  {{ $data['checkOut'] }} <br>
    Length of Stay:  {{ $data['calDays'] }} <br>
    Customer: {{ $data['customer_name'] }} <br>

</p>

<p>
    <h3>Room Accommodation</h3>
    <table  width="100%" cellpadding="3" cellspacing="3">
        <thead>
            <tr>
                <th>Room</th>
                <th>Bed Type</th>
                <th>Rate</th>
                <th>With Breakfast</th>
            </tr>
        </thead>

        <tbody>
            <tr class="table-bordered">
                <td class="table-bordered"> {{ $data['roomNumber'] }} </td>
                <td class="table-bordered" align="center"> {{ $data['roomType'] }} </td>
                <td class="table-bordered" align="right"> {{ number_format($data['rate'], 2) }} </td>
                <td class="table-bordered" align="center"> {{ $data['hasBreakfast'] }} </td>
            </tr>
        </tbody>


    </table>
        <p>Paid Via: {{ $data['paidThru'] }}</p>
        <p>
             Additional Charge:
        <ul>
            <?php
            $sumAddCharge[] = 0

            ?>
            @foreach($data['additionalCharges'] as $charges)
                <li>{{ $charges->chargeFor }}  - {{ $charges->chargePrice}}</li>


                <?php $sumAddCharge[]  = $charges->chargePrice; ?>
            @endforeach

            @if(array_sum($sumAddCharge) == 0)
                <span class="small">-No additional charges-</span>
            @endif

        </ul>
            <hr>
            <p>Total Additional Charge: {{ (number_format(array_sum($sumAddCharge)) > 0) ? number_format(array_sum($sumAddCharge)) : "" }}</p>
        </p>

</p>
    <table width="100%">
        <tr>
            <td align="right"><h3>Total Amt Due: {{ number_format(array_sum($sumAddCharge)+$data['ttlChargeAccom']) }}</h3></td>
        </tr>
    </table>

</body>
</html>
