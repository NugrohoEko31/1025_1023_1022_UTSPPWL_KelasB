@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-lg bg-white rounded" style="border-radius: 25px; overflow: hidden; padding-bottom: 20px;">
        <div class="card-header text-white px-3 py-2 d-flex justify-content-between align-items-center" 
            style="background-color:rgb(9, 12, 151); font-weight: bold; font-size: 20px; 
                border-radius: 2px 2px 0 0; margin: 0;">
            <span>Manage Users</span>
        </div>
        
        <div class="card-body" style="border-radius: 0 0 25px 25px;">
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered mb-0">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td scope="row" class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->getRoleNames() as $role)
                                        <span class="badge bg-primary">{{ $role }}</span>
                                    @empty
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        @if (!in_array('Super Admin', $user->getRoleNames()->toArray() ?? []))
                                            @can('edit-user')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                            @endcan
                                            @can('delete-user')
                                                @if (Auth::user()->id != $user->id)
                                                    <button type="submit" class="btn btn-sm text-white"
                                                        style="background-color: rgb(144, 5, 5); border: none;"
                                                        onclick="return confirm('Do you want to delete this user?');">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                @endif
                                            @endcan
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-danger">
                                    <strong>No User Found!</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    {{ $users->links() }}
                </div>
                @can('create-user')
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Add New User
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection