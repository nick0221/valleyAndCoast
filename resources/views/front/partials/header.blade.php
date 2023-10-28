<!-- resources/views/partials/header.blade.php -->

<!-- Header -->
<header class="">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('homepage') }}"><h2 class="navbar-collapse">Valley & <em>Coast</em> &nbsp;</h2></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ (request()->route()->getName() === 'homepage')? 'active': null }}">
                        <a class="nav-link"  href="{{ route('homepage') }}" >Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>


                    <li class="nav-item"><a class="nav-link " href="{{ route('view.list.accommodation') }}">Accommodations</a></li>

{{--                    <li class="nav-item dropdown">--}}
{{--                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">More</a>--}}

{{--                        <div class="dropdown-menu">--}}
{{--                            <a class="dropdown-item" href="#">About Us</a>--}}
{{--                            <a class="dropdown-item" href="#">Terms</a>--}}
{{--                        </div>--}}
{{--                    </li>--}}

                    <li class="nav-item {{ (request()->route()->getName() === 'contactUs')? 'active': null }}"><a class="nav-link " href="{{ route('contactUs') }}">Contact Us</a></li>


                </ul>
            </div>
        </div>
    </nav>
</header>
