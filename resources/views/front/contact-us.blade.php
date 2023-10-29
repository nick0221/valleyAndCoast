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


    <div class="find-us">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Our Location on Maps</h2>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- How to change your own map point
                        1. Go to Google Maps
                        2. Click on your location point
                        3. Click "Share" and choose "Embed map" tab
                        4. Copy only URL and paste it within the src="" field below
                    -->
                    <div id="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1895.9170968072672!2d121.67334698835474!3d18.125446506107302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3385fefd8b13a81d%3A0x9a13af501187f40a!2sValley%20and%20Coast%20Hotel%20%26%20Restaurant!5e0!3m2!1sen!2sph!4v1697598077389!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="left-content">
                        <h4>About our office</h4>
                        <p>
                            Address: #6 Intersection Magapit, Lal-lo, Cagayan <br>
                            Contact: 0921-348-9722 / 0997-552-2319 <br>
                            Email: yvonne_yves@yahoo.com <br>
                        </p>
                        <ul class="social-icons">
                            <li><a target="_blank" href="https://www.facebook.com/pages/Valley-and-Coast-Hotel-Magapit/1944862055739783"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>

                        </ul>
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
                        <h2>Send us a Message <small class="text-muted">for inquiries</small></h2>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="contact-form">
                        <form id="contact" action="{{ route('send.inquiries') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <fieldset>
                                        <input name="name" type="text" class="form-control" id="name" placeholder="Full Name" required="">
                                    </fieldset>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <fieldset>
                                        <input name="email" type="email" class="form-control" id="email" placeholder="E-Mail Address" required="">
                                    </fieldset>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <fieldset>
                                        <input name="subject" type="text" class="form-control" id="subject" placeholder="Subject" required="">
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <textarea name="message" rows="6" class="form-control" id="message" placeholder="Your Message" required=""></textarea>
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <button type="submit" id="form-submit" class="filled-button">Send Message</button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
{{--                <div class="col-md-4">--}}
{{--                     --}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
