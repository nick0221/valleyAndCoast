<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Amenities;
use App\Models\CustomerReservation;
use App\Models\Inquiry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public static function index(){

        $accommodation = Accommodation::leftJoin('bed_types', 'bed_types.id', 'accommodations.bed_type_id')
            ->selectRaw('
                bed_types.title AS bedTypeTitle,
                accommodations.id AS accomId,
                image, pricePerNight, description, roomNumber

              ')
            ->orderByDesc('accommodations.created_at')
            ->get();

        //dd($accommodation);

        return view('front.home', compact('accommodation'));

    }


    public static function view(Request $request){

        $details = Accommodation::where('accommodations.id', '=', $request->id)
            ->leftJoin('bed_types', 'bed_types.id', 'accommodations.bed_type_id')
            ->selectRaw('
                bed_types.title AS bedTypeTitle,
                accommodations.id AS accomId,
                image, pricePerNight, description, roomNumber, amenities, maxOccupancy, roomSize, isAirconditioned

              ')
            ->first();

        $countAmenities = (empty($details->amenities)) ? 0 : $details->amenities;


        if ($countAmenities > 0){
            $amenities = Amenities::whereIn('id', )->get();
        }else{
            $amenities = [];
        }



        return view('front.accom-details', compact('details', 'amenities'));

    }



    public static function accommodationList()
    {
        $details = Accommodation::selectRaw('
                bed_types.title AS bedTypeTitle,
                accommodations.id AS accomId,
                image, pricePerNight, description, roomNumber, amenities, maxOccupancy, roomSize, isAirconditioned

              ')
            ->leftJoin('bed_types', 'bed_types.id', 'accommodations.bed_type_id')
            ->orderByDesc('accommodations.created_at')
            ->paginate(6);
        //dd($details);

        return view('front.accom-list', compact('details'));


    }




    public static function inquiries(Request $request)
    {

        $inquiry = new  Inquiry();
        $inquiry->name = $request->name;
        $inquiry->email = $request->email;
        $inquiry->subject = $request->subject;
        $inquiry->msg = $request->message;
        $inquiry->save();

        return redirect(route('thanks.message'));

    }



    public static function reserve(Request $request)
    {
        $accomId = $request->accom_id;

        $details = Accommodation::where('accommodations.id', '=', $accomId)
            ->leftJoin('bed_types', 'bed_types.id', 'accommodations.bed_type_id')
            ->selectRaw('
                bed_types.title AS bedTypeTitle,
                accommodations.id AS accomId,
                image, pricePerNight, description, roomNumber, amenities, maxOccupancy, roomSize, isAirconditioned

              ')
            ->first();

        return view('front.reserve', compact('details'));

    }




    public static function customerReserve(Request $request)
    {
        $reserve = new CustomerReservation();
        $reserve->accommodation_id = $request->accommodation_id;
        $reserve->customerName = $request->name;
        $reserve->check_in = $request->checkin;
        $reserve->check_out = $request->checkout;
        $reserve->contact = $request->contact;
        $reserve->email = $request->email;
        $reserve->save();

        return redirect(route('reserve.success'));


    }

















}
