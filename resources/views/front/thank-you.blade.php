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
                        <h2>Contact Us</h2>
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
                        <h2>Thank you for getting in touch!</h2>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="contact-form">
                        We appreciate you contacting us. One of our colleagues will get back to you shortly.
                        Have a great day!
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
