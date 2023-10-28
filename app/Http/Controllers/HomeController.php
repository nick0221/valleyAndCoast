<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Amenities;
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

        $amenities = Amenities::whereIn('id', $details->amenities)->get();



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
//        dd($details);

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


}
