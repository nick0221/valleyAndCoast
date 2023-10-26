<?php

namespace App\Http\Controllers;

use App\Models\MassReceive;
use App\Models\ReceivedStock;
use App\Models\Supplier;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class AcknowledgmentReceipt extends Controller
{
    public function generatePDF($reference_id)
    {


        $receiveTran = MassReceive::find($reference_id)
            ->leftJoin('staff', 'staff.id', 'mass_receives.receivedBy')
            ->first();

        $supplier = Supplier::find($receiveTran->supplier_id)->first();
        $items = ReceivedStock::where('mass_receive_id', $reference_id)
                ->leftJoin('inventories', 'inventories.id', 'received_stocks.inventory_id')
                ->get();


        $image = base64_encode(file_get_contents(public_path('/images/vc-logo.png')));

        $data = [
            'tranReference' =>  $receiveTran->tranReference,
            'receiveBy' =>  strtoupper($receiveTran->fullname),
            'items' => $items,
            'imgLogo' => $image,
            'supplier' => strtoupper($supplier->suppName),

            'datePrinted' => now()->format('M d, Y'),

        ];




        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);


        // Create a new instance of Dompdf
        $dompdf = new Dompdf($options);

        // Load HTML content (you can generate this dynamically)
        $html  = view('pdf.receipt', compact('data'))->render();


        $dompdf->loadHtml($html);

        // Set paper size (optional)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF (you can save it to a file if needed)

        $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));


    }
}
