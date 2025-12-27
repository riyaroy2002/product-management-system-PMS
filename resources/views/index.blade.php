@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-4 col-xl-4 align-self-center">
                <h5 class="text-dark mb-0"><strong>Dashboard</strong></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6 col-xl-3 mb-4">
                <div class="card shadow border-start-warning py-2">
                    <div class="card-body">
                        <a href="{{ route('products.index') }}">
                            <p class="text-uppercase fw-bold text-primary text-sm-start">
                                Total Products
                                <i class="fas fa-box fa-2x float-end"></i>
                            </p>
                            <div class="row align-items-center no-gutters">
                                <div class="col-12">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $products }}</span></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @if (Auth::user()->role == 'admin')
                <div class="col-6 col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <a href="{{ route('users.index') }}">
                                <p class="text-uppercase fw-bold text-primary text-sm-start">
                                    Total Users <i class="fas fa-users fa-2x float-end"></i>
                                </p>
                                <div class="row align-items-center no-gutters">
                                    <div class="col-12">
                                        <div class="text-uppercase text-primary fw-bold text-xs mb-1"></div>
                                        <div class="text-dark fw-bold h5 mb-0"><span>{{ $users }}</span></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <a href="{{ route('users.index') }}">
                                <p class="text-uppercase fw-bold text-primary text-sm-start">
                                    Total Verified Users <i class="fas fa-users fa-2x float-end"></i>
                                </p>
                                <div class="row align-items-center no-gutters">
                                    <div class="col-12">
                                        <div class="text-uppercase text-primary fw-bold text-xs mb-1"></div>
                                        <div class="text-dark fw-bold h5 mb-0"><span>{{ $verified_users }}</span></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <a href="{{ route('users.index') }}">
                                <p class="text-uppercase fw-bold text-primary text-sm-start">
                                    Total Blocked Users <i class="fas fa-users fa-2x float-end"></i>
                                </p>
                                <div class="row align-items-center no-gutters">
                                    <div class="col-12">
                                        <div class="text-uppercase text-primary fw-bold text-xs mb-1"></div>
                                        <div class="text-dark fw-bold h5 mb-0"><span>{{ $blocked_users }}</span></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
