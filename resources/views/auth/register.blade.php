@extends('layouts.auth')
@section('title', 'Register')
@section('auth')
    <div class="container-fluid bg-gradient-primary d-flex justify-content-center align-items-center"
        style="min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg o-hidden border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="text-dark mb-2"><strong>Sign Up</strong></h3>

                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form class="user" method="POST" action="{{ route('post.register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-md @error('name') is-invalid @enderror"
                                    placeholder="Enter name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control form-control-md @error('email') is-invalid @enderror"
                                    placeholder="Enter email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Enter confirm password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-sm w-100 p-2 rounded-pill" type="submit">Register</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="small text-decoration-none">Login </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
