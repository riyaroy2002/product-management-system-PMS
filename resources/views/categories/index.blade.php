@extends('layouts.app')
@section('title', 'Categories List')

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-12 col-xl-4">
                <h5 class="text-dark">Manage Categories</h5>
            </div>

            <div class="col-xl-4 offset-xl-4 align-self-center">
                <div class="d-flex justify-content-end gap-2">

                    @can('create', App\Models\Category::class)
                        <a class="btn btn-primary btn-sm" href="{{ route('category.create') }}">
                            <i class="fas fa-plus-circle"></i>&nbsp; Add Category
                        </a>
                    @endcan

                    @can('trashCategories', App\Models\Category::class)
                        <a class="btn btn-danger btn-sm" href="{{ route('category.trash-categories') }}">
                            <i class="fas fa-trash"></i>&nbsp; Trashed Category
                        </a>
                    @endcan

                </div>
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
                <p class="text-primary m-0 fw-bold">Category Info</p>
            </div>

            <div class="card-body">

                <div class="table-responsive text-nowrap mt-2">
                    <table class="table table-hover table-sm my-0">
                        <thead>
                            <tr>
                                <th class="text-dark">Sl. No.</th>
                                <th class="text-dark">Name</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="text-center text-dark">Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $index => $category)
                                <tr>
                                    <td>
                                        {{ ($categories->currentPage() - 1) * $categories->perPage() + ($index + 1) }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $category->name }}
                                    </td>

                                    @if (Auth::user()->role === 'admin')
                                        <td class="text-center">


                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>

                                                <div class="dropdown-menu">
                                                    @can('update', $category)
                                                        <a class="dropdown-item"
                                                            href="{{ route('category.edit', $category->id) }}">
                                                            <i class="far fa-edit"></i>&nbsp; Edit
                                                        </a>
                                                    @endcan

                                                    @can('delete', $category)
                                                        <a class="dropdown-item text-danger" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#delCat{{ $category->id }}">
                                                            <i class="fas fa-trash"></i>&nbsp; Delete
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>

                                            <div class="modal fade" id="delCat{{ $category->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-md">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Category</h5>
                                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Are you sure you want to delete
                                                            <strong>{{ $category->name }}</strong>?
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button class="btn btn-dark" data-bs-dismiss="modal">
                                                                Cancel
                                                            </button>

                                                            <form action="{{ route('category.delete', $category->id) }}"
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
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">
                                        No categories found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        {{ $categories->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
