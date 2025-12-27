@extends('layouts.auth')
@section('title', 'Verify Email')

@section('auth')
    <div class="container-fluid bg-gradient-primary d-flex justify-content-center align-items-center"
        style="min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg o-hidden border-0">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h3 class="text-dark mb-2"><strong>Verify Your Email</strong></h3>
                            <p class="text-muted small">
                                We have sent a verification link to your email address.
                                Please check your inbox and verify your email to continue.
                            </p>
                        </div>


                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif


                        @if (session('error'))
                            <div class="alert alert-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endif


                        <div class="text-center mt-3">
                            <p class="small text-muted mb-2">
                                Didn’t receive the email?
                            </p>

                            <form method="POST" action="{{ route('resend-email-link') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('email') }}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    Resend Verification Email
                                </button>
                            </form>
                        </div>

                        <hr>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
