<?php

namespace App\Http\Controllers;


use App\Models\Accommodation;
use App\Models\AdditionalCharges;
use App\Models\Customer;
use App\Models\PaymentDetails;
use App\Models\Reservation;
use App\Models\ReservationAccommodation;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    public function generatePDF($reference_id)
    {
        // Use $reference_id to fetch data or generate content for the PDF
        // For example, you can retrieve data from a database using the reference_id

//        $reservation = DB::table('reservations')->where('id', $reference_id);
//        $accommodation = DB::table('reservation_accommodations')->where('reservation_id', $reservation->);

        $reservation = Reservation::find($reference_id);
        $accommodationRes = ReservationAccommodation::where('reservation_id', $reference_id)->first();
        $accommodation = Accommodation::where('accommodations.id', $accommodationRes->accommodation_id)->leftJoin('bed_types', 'bed_types.id', '=', 'accommodations.bed_type_id')->first();
        $paymentDetails = PaymentDetails::where('reservation_id', $reference_id)->first();
        $addCharges = AdditionalCharges::where('reservation_id', $reference_id)->get();
        $customer = Customer::where('customers.id', $reservation->customer_id)->first();

        $checkIn = $reservation->checkIn;
        $checkOut = $reservation->checkOut;
        $diffInDays = $checkIn->diffInDays($checkOut)+1;
        $calDays = ($diffInDays <= 1) ? "{$diffInDays}D" : "{$diffInDays}D  ".($diffInDays-1)."N";


//
//        dd( $addCharges );
//
//
//
        $image = base64_encode(file_get_contents(public_path('/images/vc-logo.png')));

        $data = [
            'invoice_number' => 'RI-'.strtotime($reservation->created_at),
            'invoice_date' => now()->format('M d, Y - h:i A'),
            'customer_name' => $customer->fullname,
            'customer_email' => $customer->email,
            'customer_contact' => $customer->contact,
            'customer_addr' => $customer->address,
            'bookingReference' => $reservation->tranReference,
            'checkInDate' => Carbon::parse($reservation->checkIn)->format('M d, Y'),
            'checkIn' => Carbon::parse($reservation->checkIn)->toDateString(). ' - '.$reservation->checkInTime,
            'checkOutDate' => Carbon::parse($reservation->checkOut)->format('M d, Y'),
            'checkOut' => Carbon::parse($reservation->checkOut)->toDateString(). ' - '.$reservation->checkOutTime,
            'roomNumber' => $accommodation->roomNumber,
            'roomType' => $accommodation->title,
            'rate' => $accommodationRes->accommodationPrice,
            'ttlChargeAccom' => $accommodationRes->totalAmtDue,
            'hasBreakfast' => ($accommodationRes->withBreakfast) ? "Yes" : "No",
            'additionalCharges' => $addCharges,
            'paidThru' => $paymentDetails->payMethod,
            'calDays' => $calDays,
            'imgLogo' => $image

        ];




        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);


        // Create a new instance of Dompdf
        $dompdf = new Dompdf($options);

        // Load HTML content (you can generate this dynamically)
        $html  = view('pdf.invoice', compact('data'))->render();


        $dompdf->loadHtml($html);

        // Set paper size (optional)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF (you can save it to a file if needed)

        $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));


    }


}
