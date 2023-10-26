<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }

        /* Define a fixed position for the footer */
        .fixed-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px; /* Adjust height as needed */
            background-color: #f0f0f0; /* Background color for the footer */
            border-top: 1px solid #ccc; /* Optional border at the top of the footer */
            text-align: center;
            font-size: 12px;
            color: grey;
            line-height: 22px; /* Vertical centering of text */
        }

        /* If you want to center the text within the footer */
        .fixed-footer p {
            margin: 0;
        }

        .signatoryGuest{
            position: fixed;
            bottom: 200px;

            left: 30px;
            height: 30px; /* Adjust height as needed */
            width: 20%;
            font-style: italic;

            border-top: 1px solid #ccc; /* Optional border at the top of the footer */
            text-align: center;
            font-size: 12px;
            color: grey;
            line-height: 22px; /* Vertical centering of text */
        }
        .signatoryNotes{
            position: fixed;
            bottom: 150px;
            font-style: italic;
            right: 30px;
            height: 30px; /* Adjust height as needed */
            text-align: justify;
            font-size: 10px;
            color: grey;
            line-height: 22px; /* Vertical centering of text */
        }

        /* Adjust margins for the content above the footer */
        .content {
            margin-bottom: 30px; /* Should match the height of the footer */
        }

        .titleHead{
            text-align: left;
            color: #707070;
        }
    </style>

</head>
<body>

<table width="100%">
    <tr>
        <td valign="top"><img src="data:image/png;base64,{{ $data['imgLogo'] }}" alt="" width="220"/></td>
        <td align="right">
            <h1  >Valley and Coast Hotel</h1>
            <pre>
                #6 Intersection Magapit, Lal-lo, Cagayan
                0921-348-9722 / 0997-552-2319
                yvonne_yves@yahoo.com
            </pre>
        </td>
    </tr>

</table>


<table  width="100%">
    <tr>
        <td>
            <h1 CLASS="titleHead">INVOICE</h1>
        </td>
    </tr>
    <tr>
        <td width="50%">

            <table>

                <tr>
                    <td colspan="2"><strong>Invoice to:</strong></td>

                </tr>
                <tr><td>Name:</td><td><span>{{ $data['customer_name'] }}</span></td></tr>
                <tr><td>Addr:</td><td><span>{{ $data['customer_addr'] }}</span></td></tr>
                <tr><td>Email:</td><td><span>{{ $data['customer_email'] }}</span></td></tr>
                <tr><td>Contact:</td><td><span>{{ $data['customer_contact'] }}</span></td></tr>

            </table>

        </td>
        <td>&nbsp;  </td>
        <td>
            <table  width="100%" >
                <tr><td align="">Date:</td><td><span >{{ $data['invoice_date'] }}</span></td></tr>
                <tr><td align="">Invoice No.:</td><td><span>{{ $data['invoice_number'] }}</span></td></tr>
                <tr><td align="">Checkin:</td><td>{{ $data['checkInDate'] }}</td></tr>
                <tr><td align="">Checkout: </td><td>{{ $data['checkOutDate'] }}</td></tr>
                <tr><td align="">Booking Ref#: </td><td>{{ $data['bookingReference'] }}</td></tr>
                <tr><td align="">Pay Method: </td><td>{{ $data['paidThru'] }}</td></tr>

            </table>
        </td>
    </tr>
</table>






<br/>
<br/>
<br/>

<table width="100%">
    <thead style="background-color: lightgray;">

    <tr>
        <th>Description</th>
        <th>Length of Stay</th>
        <th>Rate per night</th>
        <th>Addt'l Charge</th>
        <th>Total Charge</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Room Charge ({{ $data['roomType'] }})</td>
        <td align="center">{{ $data['calDays'] }}</td>
        <td align="right">{{ number_format($data['rate']) }}</td>
        <td align="right"> </td>
        <td align="right">{{ number_format($data['ttlChargeAccom']) }}</td>

    </tr>
    <?php $sumAddCharge[] = 0 ?>
    @foreach($data['additionalCharges'] as $charges)

        <tr>
            <td>Addt'l Charge for ({{ $charges->chargeFor }})</td>
            <td> </td>
            <td> </td>
            <td align="right"> {{ number_format($charges->chargePrice) }} </td>
            <td align="right"> {{ number_format($charges->chargePrice) }} </td>
        </tr>

            <?php $sumAddCharge[]  = $charges->chargePrice; ?>


    @endforeach
    <tr>
        <td colspan="5" align="center"> <i style="color: #a7a4a4; letter-spacing: 2px;"> </i> </td>
    </tr>

    </tbody>

    <tfoot>
    <?php
        $ttlAmount = (array_sum($sumAddCharge)+$data['ttlChargeAccom']);
        $vat = ((array_sum($sumAddCharge)+$data['ttlChargeAccom'])*0.12);
        $subTotal = ($ttlAmount-$vat);
    ?>
    <tr>
        <td colspan="3"></td>
        <td align="right">Subtotal</td>
        <td align="right">{{ number_format($subTotal, 2) }}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td align="right">Vat(12%)</td>
        <td align="right">{{ number_format($vat, 2) }}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td align="right">Total</td>
        <td align="right" class="gray">{{ number_format($ttlAmount, 2) }}</td>
    </tr>
    </tfoot>
</table>


<table width="50%" style="color: #a7a4a4">
    <tr>
        <td colspan="2"><i>Notes:</i></td>
    </tr>
    <tr>
        <td> </td>
        <td><span style="padding-left: 10px;">
                <i>
                    Hotel charge extra for services like parking & facilities.
                </i>
            </span>
        </td>
    </tr>


</table>

<div class="signatoryGuest">
    Guest Signature
</div>


<div class="signatoryNotes">
    I agree that my liability for this bill is not waived
    and agree to be held personally liable in the
    event that the indicated person, company or
    association fails to pay for any part or the full
    amount of the these charges.
</div>


<div class="fixed-footer">
    "We value your business and hope to continue serving you in the future."
</div>

</body>


</html>
