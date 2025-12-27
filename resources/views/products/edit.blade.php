@extends('layouts.app')
@section('title', 'Edit Product')
@section('content')
    <div class="container">
        <h5 class="text-dark mb-3">Edit Product</h5>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header py-2">
                    <p class="text-primary m-0 fw-bold">Product Info</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Categories</label>
                            <select name="category_id"
                                class="form-control form-control-sm @error('category_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Product Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Price</label>
                            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g,'$1');">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Size</label>

                            <div class="form-control p-2 @error('size') is-invalid @enderror">
                                <div class="row">

                                    @php
                                        $sizesList = ['S', 'M', 'L', 'XL', 'XXL']; // FIXED CASE
                                        $selectedSizes = is_array($product->size)
                                            ? $product->size
                                            : json_decode($product->size, true);

                                        $selectedSizes = $selectedSizes ?? []; // NULL SAFE
                                    @endphp

                                    @foreach ($sizesList as $size)
                                        <div class="col-6 col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="size_{{ $size }}" name="size[]"
                                                    value="{{ $size }}"
                                                    {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="size_{{ $size }}">
                                                    {{ $size }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            @error('size')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Image</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @if ($product->image)
                                <img src="{{ $product->image_url }}" width="120" class="mt-2 rounded">
                            @endif
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6 col-xl-4">
                            <label class="form-label text-dark">Description</label>
                            <textarea type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                value="{{ old('description', $product->description) }}">{{ $product->description }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm px-4">
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
