@extends('layouts.app')
@section('title', 'Products List')

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-12 col-xl-4">
                <h5 class="text-dark">Manage Products</h5>
            </div>

            <div class="col-12 col-xl-2 offset-xl-6 align-self-center">
                <a class="btn btn-secondary btn-sm w-100" href="{{ route('products.index') }}">
                    <i class="fas fa-arrow-left"></i>&nbsp; Back
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow">
            <div class="card-header py-2">
                <p class="text-primary m-0 fw-bold">Product Info</p>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('product.search') }}">
                    @csrf
                    <div class="row g-2">

                        <div class="col-xl-2">
                            <select name="category_id" class="form-control form-control-sm">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-2">
                            <input type="text" class="form-control form-control-sm" name="name"
                                placeholder="Product name" value="{{ old('name') }}">
                        </div>

                        <div class="col-xl-2">
                            <input type="text" class="form-control form-control-sm" name="price_from"
                                placeholder="Min Price" value="{{ old('price_from') }}">
                        </div>

                        <div class="col-xl-2">
                            <input type="text" class="form-control form-control-sm" name="price_to"
                                placeholder="Max Price" value="{{ old('price_to') }}">
                        </div>

                        <div class="col-xl-2">
                            <select name="size" class="form-control form-control-sm">
                                <option value="">-- Size --</option>
                                @foreach (['S', 'M', 'L', 'XL', 'XXL'] as $s)
                                    <option value="{{ $s }}" {{ old('size') == $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-1">
                            <button class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <div class="col-xl-1">
                            <a href="{{ route('products.index') }}" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive text-nowrap mt-3">
                    <table class="table table-hover table-sm my-0">
                        <thead>
                            <tr>
                                <th>Sl.No.</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Size</th>
                                <th>Image</th>
                                <th class="text-center">Description</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $index => $product)
                                <tr>
                                    <td>
                                        {{ ($products->currentPage() - 1) * $products->perPage() + ($index + 1) }}
                                    </td>

                                    <td>{{ optional($product->category)->name }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>₹{{ number_format($product->price, 2) }}</td>

                                    <td>
                                        @forelse ($product->size as $size)
                                            <span class="badge bg-primary">{{ strtoupper($size) }}</span>
                                        @empty
                                            <span class="badge bg-secondary">N/A</span>
                                        @endforelse
                                    </td>

                                    <td>
                                        <img src="{{ $product->image_url }}" width="70" height="70"
                                            class="rounded border" style="object-fit:cover;">
                                    </td>

                                    <td class="text-center">
                                        {{ Str::limit($product->description, 40) }}
                                    </td>


                                    <td>

                                            <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </span>

                                    </td>


                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                    @can('restore', $product)
                                                        <form action="{{ route('product.restore', $product->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button class="dropdown-item text-dark">
                                                                <i class="fas fa-undo"></i>&nbsp; Restore
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    @can('forceDelete', $product)
                                                        <a class="dropdown-item text-danger" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#delPrd{{ $product->id }}">
                                                            <i class="fas fa-trash"></i>&nbsp; Delete permanently
                                                        </a>
                                                    @endcan

                                                </div>
                                        </div>

                                        <div class="modal fade" id="delPrd{{ $product->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-md">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Product</h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        Are you sure you want to delete
                                                        <strong>{{ $product->name }}</strong>?
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-dark" data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>

                                                        <form action="{{ route('product.force-delete', $product->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button class="btn btn-danger" type="submit">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">
                                        No trashed products found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 ms-auto text-end">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
