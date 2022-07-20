<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('/resources/css/app.css') }}" rel="stylesheet">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-light mt-3 mb-3">
                <a href="/">
                    <img src="{{ asset('img/MomsInLA.png') }}" class="img-fluid my-auto" width="120rem"
                        alt="">
                </a>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active my-auto ml-4">
                            <a class="nav-link" href="/about">About Us <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active my-auto ml-4">
                            <a class="nav-link" href="/past">Past Events <span class="sr-only">(current)</span></a>
                        </li>
                </div>
            </nav>
        </div>
        <div class="col-12" style="border-bottom:1.5px solid rgba(0, 0, 0, 0.2)">

        </div>
        {{-- Content --}}
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>

    </div>
    {{-- Footer --}}
    <footer class="col-12 text-center p-4" style="background-color: #EDF2F3">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <a style="color: black; text-decoration: none" href="/about">About Us</a>
                </div>
                <div class="col-3">
                    <a style="color: black; text-decoration: none" href="/about">Contact Us</a>
                </div>
                <div class="col-3">
                    <a style="color: black; text-decoration: none" href="/about">Sponsorship</a>
                </div>
                <div class="col-3">
                    <a style="color: black; text-decoration: none" href="/about">Donation</a>
                </div>
                <div class="col-12 mt-4">
                    <small style="color: #7b95b4">
                        All rights reserved. &copy;{{ date('Y') }} MOMSINLA. Read our Privacy Policy and
                        Tearms of Use anytime <a href="">here</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

</html>
