@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Manajemen User (Staff)</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Staff Baru</a>
    </div>
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'kasir' ? 'success' : 'info') }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </td>
                <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
        </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
