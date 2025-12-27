@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')
    <div class="container">
        <h5 class="text-dark mb-3">Edit Category</h5>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('category.update', $category->id) }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header py-2">
                    <p class="text-primary m-0 fw-bold">Category Info</p>
                </div>
                <div class="card-body">
                    <div class="mb-3 col-md-10 col-xl-10">
                        <label class="form-label text-dark">Category Name</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter Category Name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-warning btn-sm px-4">
                            <i class="fas fa-check-circle"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
