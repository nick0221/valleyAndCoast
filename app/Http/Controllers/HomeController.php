<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Amenities;
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






}
