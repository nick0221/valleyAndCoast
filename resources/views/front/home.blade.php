<!-- resources/views/welcome.blade.php -->
@extends('front.layouts.app')

@section('title', 'Valley and Coast Hotel & Restaurant')

@section('content')
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="banner header-text">
        <div class="owl-banner owl-carousel">
            <div class="banner-item-01">
                <div class="text-content">
                    <h4>Find your accommodation today!</h4>
                    <h2>Lorem ipsum dolor sit amet</h2>
                    <button class="btn btn-danger btn-lg">Book now</button>
                </div>
            </div>
            <div class="banner-item-02">
                <div class="text-content">
                    <h4>Find your accommodation today!</h4>
                    <h2>Laboriosam reprehenderit ducimus</h2>
                    <button class="btn btn-danger btn-lg">Book now</button>
                </div>
            </div>
            <div class="banner-item-03">
                <div class="text-content">
                    <h4>Find your accommodation today!</h4>
                    <h2>Quaerat suscipit unde minus dicta</h2>
                    <button class="btn btn-danger btn-lg">Book now</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Ends Here -->
    <div class="latest-products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Featured Accommodation</h2>
                        <a href="#">view more <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                @php($i = 1)
                @foreach($accommodation as $items)
                <div class="col-md-4">
                    <div class="product-item">
                        <a href="{{ route('view.accommodation', $items->accomId) }}"><img src="{{ (empty($items->image)) ? asset('images/placeholder.jpg'): asset('storage/'.$items->image) }}" alt="Hotel Image"></a>
                        <div class="down-content">
                            <a href="{{ route('view.accommodation', $items->accomId) }}"><h4>{{ $items->roomNumber }}</h4></a>

                            <h6> &#x20B1; {{ number_format($items->pricePerNight) }} <small> Rate per night</small></h6>

                               <p>
                                    <h6>Description</h6>
                                    <p style="text-align: justify;" >
                                        {{ $items->description }}
                                    </p>

                               </p>

                            <small>
                                <strong title="Click for more details"><a href="{{ route('view.accommodation', $items->accomId) }}" class="btn btn-outline-primary btn-sm">View more details</a> </strong>
                            </small>
                        </div>
                    </div>
                </div>

                        @break($i === 3)
                    @php($i++)
                @endforeach




            </div>
        </div>
    </div>



    <div class="happy-clients">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Client Reviews</h2>

                        <a target="_blank" href="https://www.google.com/travel/search?q=Valley%20and%20Coast%20Hotel%20%26%20Restaurant&g2lb=2502548%2C2503771%2C2503781%2C4258168%2C4270442%2C4284970%2C4291517%2C4597339%2C4757164%2C4814050%2C4874190%2C4893075%2C4924070%2C4965990%2C4990494%2C10207532%2C72248050%2C72248052%2C72280812%2C72298667%2C72302247%2C72317059%2C72370226%2C72379816%2C72388607%2C72388608%2C72390424%2C72393630%2C72398503&hl=en-PH&gl=ph&cs=1&ssta=1&ts=CAESABpHCikSJzIlMHgzMzg1ZmVmZDhiMTNhODFkOjB4OWExM2FmNTAxMTg3ZjQwYRIaEhQKBwjnDxAKGBwSBwjnDxAKGB0YATICEAA&qs=CAEyFENnc0lpdWlmaklIcTY0bWFBUkFCOAJCCQkK9IcRUK8Tmg&ap=ugEHcmV2aWV3cw&ictx=1&ved=0CAAQ5JsGahcKEwigysLT2P6BAxUAAAAAHQAAAAAQBQ">read more <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                <iframe src='https://widgets.sociablekit.com/google-reviews/iframe/211449' frameborder='0' width='100%' height='400'></iframe>

            </div>
        </div>
    </div>


    <div class="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner-content">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Get in Touch</h4>
                                <p>Have questions, feedback, or just want to say hello? Feel free to reach out to us! We're always eager to hear from you. Use the provided contact information or fill out the form, and we'll get back to you promptly. Your input matters to us.</p>
                                <p>
                                <hr>
                                    Or you may visit our social media platform for more updates.
                                    <ul class="social-icons mt-2">
                                        <li><a target="_blank" class="rounded" href="https://www.facebook.com/pages/Valley-and-Coast-Hotel-Magapit/1944862055739783"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#" class="rounded"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#" class="rounded"><i class="fa fa-instagram"></i></a></li>

                                    </ul>
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-6 text-right">
                                <a href="{{ route('contactUs') }}" class="filled-button">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
