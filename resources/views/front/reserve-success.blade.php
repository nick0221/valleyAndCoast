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
                        <h2>Reservation Successfully Submitted!</h2>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="contact-form">
                        Thank you for choosing Valley & Coast Hotel for your upcoming reservation. We're delighted to have you with us!
                        <br>
                        <br>
                        Should you need any further assistance, don't hesitate to contact us at 0921-348-9722 / 0997-552-2319 / yvonne_yves@yahoo.com.
                        <br>
                        We look forward to providing you with a memorable experience!
                        <br>
                        <br>

                        Warm regards,<br>
                        Valley & Coast Hotel
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
