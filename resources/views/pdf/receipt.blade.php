<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acknowledgement Receipt</title>

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
        <td width="50%">

            <table>

                <tr><td>Date:</td><td><span>{{ $data['datePrinted'] }}</span></td></tr>
                <tr><td>Reference No.:</td><td><span>{{ $data['tranReference'] }}</span></td></tr>
                <tr><td>Supplier:</td><td><span>{{ $data['supplier'] }}</span></td></tr>

            </table>

        </td>
        <td>&nbsp;  </td>

    </tr>
</table>






<br/>
<br/>
<br/>

<table width="100%">
    <tr>
        <td colspan="3">
            We acknowledge the receipt of the following items:
        </td>
    </tr>
    <thead style="background-color: lightgray;">

    <tr>
        <th width="7%" align="center">#</th>
        <th width="80%">Description</th>
        <th width="25%" align="center">Qty</th>

    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    @foreach($data['items'] as $item)

        <tr>
            <td align="center">{{ $i }}</td>
            <td>{{ $item->itemname }}</td>
            <td align="center">{{ $item->qty }}</td>


        </tr>

        <?php $i++; ?>
    @endforeach
    <tr>
        <td colspan="5" align="center"> <i style="color: #a7a4a4; letter-spacing: 2px;"> </i> </td>
    </tr>

    </tbody>

    <tfoot>

{{--    <tr>--}}
{{--        <td colspan="3"></td>--}}
{{--        <td align="right">Subtotal</td>--}}
{{--        <td align="right">{{ number_format($subTotal, 2) }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td colspan="3"></td>--}}
{{--        <td align="right">Vat(12%)</td>--}}
{{--        <td align="right">{{ number_format($vat, 2) }}</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td colspan="3"></td>--}}
{{--        <td align="right">Total</td>--}}
{{--        <td align="right" class="gray">{{ number_format($ttlAmount, 2) }}</td>--}}
{{--    </tr>--}}
    </tfoot>
</table>

<br><br><br>
<table width="50%" style="color: #a7a4a4">
    <tr>
        <td colspan="2"><i>Received by: </i></td>
    </tr>
    <tr>

        <td><span style="padding-left: 10px;">
                <i>
                    {{ $data['receiveBy'] }}
                </i>
            </span>
        </td>
    </tr>


</table>

{{--<div class="signatoryGuest">--}}
{{--    Guest Signature--}}
{{--</div>--}}


<div class="signatoryNotes">
    We have carefully inspected and verified the items to ensure they match the order. All items have been received in good condition and meet the required quality standards.
    If you have any questions or concerns regarding this receipt or the received items, please do not hesitate to contact us. Your satisfaction is our priority.
    Thank you for your business.

</div>


{{--<div class="fixed-footer">--}}
{{--    "We value your business and hope to continue serving you in the future."--}}
{{--</div>--}}

</body>


</html>
