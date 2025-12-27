@extends('layouts.app')
@section('title', 'Trashed Categories')

@section('content')
    <div class="container">

        <div class="row mb-2">
            <div class="col-12 col-xl-4">
                <h5 class="text-dark">Trashed Categories</h5>
            </div>

            <div class="col-12 col-xl-2 offset-xl-6 align-self-center">
                <a class="btn btn-secondary btn-sm w-100" href="{{ route('categories.index') }}">
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
                <p class="text-danger m-0 fw-bold">Trashed Category Info</p>
            </div>

            <div class="card-body">

                <div class="table-responsive text-nowrap mt-2">
                    <table class="table table-hover table-sm my-0">
                        <thead>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Name</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="text-center">Action</th>
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
                                                    data-bs-toggle="dropdown" data-bs-display="static">
                                                    <i class="fas fa-cog"></i>
                                                </button>

                                                <div class="dropdown-menu">
                                                    @can('restore', $category)
                                                        <form action="{{ route('category.restore', $category->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button class="dropdown-item text-dark">
                                                                <i class="fas fa-undo"></i>&nbsp; Restore
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    @can('forceDelete', $category)
                                                        <a class="dropdown-item text-danger" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#delCat{{ $category->id }}">
                                                            <i class="fas fa-trash"></i>&nbsp; Delete permanently
                                                        </a>
                                                    @endcan

                                                </div>
                                            </div>


                                            <div class="modal fade" id="delCat{{ $category->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-md">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Category permanently</h5>
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

                                                            <form action="{{ route('category.force-delete', $category->id) }}"
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
