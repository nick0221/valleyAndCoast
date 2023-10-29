<!-- resources/views/welcome.blade.php -->
@extends('front.layouts.app')

@section('title', 'Contact us')

@section('content')

    <!-- Page Content -->
    <div class="page-heading contact-heading header-text" style="background-image: url({{ asset('template/assets/images/heading-4-1920x500.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h4>Valley & Coast Hotel</h4>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="send-message">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Reservation Form</h2>
                    </div>
                </div>
                <div class="col-md-8 ">
                    <div class="contact-form">
                        <form action="{{ route('reserve.submit') }}" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="accommodation_id" value="{{ ($details->accomId) }}">
                                <div class="col-lg-12 col-md-12 col-sm-12 mb-3 ">
                                    <div class="media border rounded p-2">
                                        <img class="align-self-start mr-3 img-fluid wc-image img-thumbnail " width="25%" src="{{ (empty($details->image)) ? asset('images/placeholder.jpg'): asset('storage/'.$details->image) }}" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5 class="mt-0">{{ strtoupper($details->roomNumber) }}</h5>
                                            <h6 class="mt-1 font-weight-light">Bed Type: {{ $details->bedTypeTitle }}</h6>
                                            <p class="mt-2 ">Description: <br> <div class="text-muted font-weight-light small text-truncate col-md-8">{{ $details->description }}</div></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <fieldset>
                                        <label for="name">Fullname<span class="text-danger">*</span></label>
                                        <input name="name" type="text" class="form-control" id="name" required="">
                                    </fieldset>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <fieldset>
                                        <label for="checkin">Checkin<span class="text-danger">*</span></label>
                                        <input name="checkin" type="date" min="{{ now()->toDateString('Y-m-d')}}"  class="form-control" id="checkin" required="">
                                    </fieldset>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <fieldset>
                                        <label for="checkout">Checkout<span class="text-danger">*</span></label>
                                        <input name="checkout" type="date" min="{{ now()->toDateString('Y-m-d')}}" class="form-control" id="checkout" required="">
                                    </fieldset>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <fieldset>
                                        <label for="contact">Contact<span class="text-danger">*</span></label>
                                        <input name="contact" type="text" class="form-control" id="contact" required="">
                                    </fieldset>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <fieldset>
                                        <label for="email">Email</label>
                                        <input name="email" type="text" class="form-control" id="email" required="">
                                    </fieldset>
                                </div>

                                <div class="col-lg-12 text-right">
                                    <fieldset>
                                        <button type="submit" id="form-submit" class="filled-button">Submit</button>
                                    </fieldset>
                                </div>


                                <div class="col-lg-12 text-left small text-muted font-italic font-weight-light">
                                    <hr>
                                    <span class="text-danger">*</span>
                                        Please note that our room availability is subject to change and can fluctuate based on various factors. While we do our best to honor all reservations, there may be instances where the requested room is not available at the time of processing

                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
