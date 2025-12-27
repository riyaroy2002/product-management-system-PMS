@extends('layouts.app')
@section('title', 'Users List')
@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-12 col-xl-4">
                <h5 class="text-dark">Manage Users</h5>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header py-2">
                <p class="text-primary m-0 fw-bold">Users Info</p>
            </div>
            <div class="card-body">

                <div class="table-responsive text-nowrap table mt-2" id="dataTable-1" role="grid"
                    aria-describedby="dataTable_info">
                    <table class="table table-hover table-sm my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-dark">Sl.no.</th>
                                <th class="text-dark">Name</th>
                                <th class="text-dark">Email</th>
                                <th class="text-dark">Role</th>
                                <th class="text-dark">Verified</th>
                                <th class="text-dark">Blocked</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($users) > 0)
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + ($index + 1) }}</td>

                                        <td class="fw-semibold">
                                            {{ $user->name }}
                                        </td>

                                        <td class="fw-semibold">
                                            {{ $user->email }}
                                        </td>

                                        <td class="fw-semibold">
                                            {{ $user->role }}
                                        </td>

                                        <td class="fw-semibold">
                                            @if ($user->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-danger">Not Verified</span>
                                            @endif
                                        </td>

                                        <td class="fw-semibold">
                                            <button type="button" class="border-0 bg-transparent p-0"
                                                title="Click to toggle status" data-bs-toggle="modal"
                                                data-bs-target="#delUsr{{ $user->id }}">
                                                <span class="badge {{ $user->is_block ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $user->is_block ? 'Blocked' : 'Unblocked' }}
                                                </span>
                                            </button>

                                            <div class="modal fade" id="delUsr{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Block / Unblock User</h5>
                                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Are you sure you want to
                                                            {{ $user->is_block ? 'unblock' : 'block' }} this user?
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button class="btn btn-dark" data-bs-dismiss="modal">
                                                                Cancel
                                                            </button>

                                                            <form method="POST"
                                                                action="{{ route('users.toggle-block', $user->id) }}">
                                                                @csrf
                                                                <button class="btn btn-danger" type="submit">
                                                                    Yes
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" class="text-muted text-center py-4">
                                        No users found
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
