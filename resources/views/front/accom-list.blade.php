<!-- resources/views/welcome.blade.php -->
@extends('front.layouts.app')

@section('title', 'Accommodations')

@section('content')

    <!-- Page Content -->
    <div class="page-heading about-heading header-text" style="background-image: url({{ asset('images/heading-6-1920x500.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h4>Valley & Coast Hotel</h4>
                        <h2>Accommodations</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="products">
        <div class="container">
            <div class="row">
               @foreach($details as $item)
                    <div class="col-md-4">
                        <div class="product-item">
                            <a href="{{ route('view.accommodation', $item->accomId) }}">
                                <img src="{{ (empty($item->image)) ? asset('images/placeholder.jpg'): asset('storage/'.$item->image) }}" alt="" class="img-fluid wc-image">

                            </a>
                            <div class="down-content">
                                <a href="{{ route('view.accommodation', $item->accomId) }}"><h4>{{ $item->roomNumber }}</h4></a>

                                <h6> Rate: &#x20B1; {{ number_format($item->pricePerNight) }} <small class="small">per night</small></h6>


                                <p>
                                <h6>Description</h6>
                                <p style="text-align: justify;" >
                                    {{ $item->description }}
                                </p>

                                </p>
                                <div class="text-right d-flex align-content-around">
                                     <a href="{{ route('view.accommodation', $item->accomId) }}" class="btn btn-link btn-block">View more details</a>
                                     <button class="btn btn-danger btn-block">Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
               @endforeach



            </div>
            <div class="col-md-12 ">
                <ul class="pages">
                    {{-- Previous Page Link --}}
                    @if ($details->onFirstPage())
                        <li class="disabled"><span><i class="fa fa-angle-double-left"></i></span></li>
                    @else
                        <li><a href="{{ $details->previousPageUrl() }}" rel="prev"><i class="fa fa-angle-double-left"></i></a></li>
                    @endif

                    {{-- Numbered Pages --}}
                    @for ($i = 1; $i <= $details->lastPage(); $i++)
                        @if ($i == $details->currentPage())
                            <li class="active  p-3 text-danger"><span>{{ $i }}</span></li>
                        @else
                            <li class=""><a href="{{ $details->url($i) }}"><span>{{ $i }}</span></a></li>
                        @endif
                    @endfor

                    {{-- Next Page Link --}}
                    @if ($details->hasMorePages())
                        <li><a href="{{ $details->nextPageUrl() }}" rel="next"> <i class="fa fa-angle-double-right"></i> </a></li>
                    @else
                        <li class="disabled"><span>&raquo;</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>












@endsection
