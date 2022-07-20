<!doctype html>
<html lang="en">
  <head>
    <title>Send Email in Laravel 8 Using SendGrid | Programming Fields</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            {{-- <div class="col-xl-6 col-lg-6 col-sm-12 ">
                <h3>You have requested a password reset</h3>
                <p> Hey, </p>
                <p> Welcome to Programming Fields </p>
                <p> This is a basic demo for sending email in Laravel 8 using SendGrid. </p>
                <p> I hope you will enjoy this post. </p>
                <br/>
                <br/>
                <p> Best Regards</p>
                <p> Team, Programming Fields </p>
            </div> --}}
            <div class="col-lg-6 col-md-6 col-12 m-auto text-center">
              <div class="card mt-5 text-center">
                <img class="card-img-top ml-auto mx-auto" src="{{ asset('img/tc_logo.png') }}" style="width: 20rem;" alt="Card image cap">
                <div class="card-body">
                  <h5 class="card-title">You have requested a password reset</h5>
                  <p class="card-text">Your password reset code is down below</p>
                  <p class="card-text">If you did not request a password reset, please ignore this email.</p>
                  <h1 class="font-weight-bold">{{ $code }}</h1>
                </div>
              </div>
            </div>
        </div>
    </div>
  </body>
</html>
