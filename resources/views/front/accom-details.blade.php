<!-- resources/views/welcome.blade.php -->
@extends('front.layouts.app')

@section('title', 'Accommodation Details')

@section('content')

    <!-- Page Content -->
    <div class="page-heading about-heading header-text" style="background-image: url({{ asset('images/heading-6-1920x500.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h2>Room Accommodation Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="products">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <img src="{{ (empty($details->image)) ? asset('images/placeholder.jpg'): asset('storage/'.$details->image) }}" alt="" class="img-fluid wc-image">

                    </div>
                    <br>

                </div>

                <div class="col-md-6">

                        <p class="lead">
                            <h1>
                                {{ strtoupper($details->roomNumber) }}
                            </h1>
                        </p>


                        <div class="section">
                            <div class="container">

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Bed Type: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-7 col-sm-9">
                                                <p>
                                                    {{ $details->bedTypeTitle }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Rate: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-sm-9">
                                                <p>
                                                    &#x20B1; {{ number_format($details->pricePerNight) }} <small class="text-muted"> per night</small>
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Max Occupancy: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-sm-9">
                                                <p>
                                                   {{ (empty($details->maxOccupancy)) ? '-' : $details->maxOccupancy }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Bed Count: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-sm-9">
                                                <p>
                                                    {{ (empty($details->bedCount)) ? '-' : $details->bedCount }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Room Size: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-sm-9">
                                                <p>
                                                    {{ (empty($details->roomSize)) ? '-' : $details->roomSize }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-3">
                                                <p class="pjVpProductPolicyTitle">
                                                    <strong>Air-conditioned: </strong>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-sm-9">
                                                <p>
                                                    {{ ($details->isAirconditioned == 1) ? 'Yes' : 'No' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="list-group-item">
                                        <div class="row">

                                            <div class="col-md-12 col-sm-9 text-right">
                                                <form action="{{ route('reserve.accom', $details->accomId) }}" method="post">
                                                    @csrf

                                                    <button class="btn btn-danger btn-lg" type="submit">Reserve</button>

                                                </form>
                                            </div>
                                        </div>
                                    </li>


                                </ul>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>


    <div class="section">
        <div class="container">
            <h5>Description</h5>
            <p>
                {{ $details->description }}
            </p>

            <h5>Amenities</h5>
            <p class="">
                <ul class="text-muted">

                    @if(count($amenities) > 0)
                        @foreach($amenities as $item)
                            <li> <i class="fa fa-check text-primary"></i> {{ $item->title }}</li>
                        @endforeach
                    @endif
                </ul>
            </p>



        </div>
    </div>


@endsection
