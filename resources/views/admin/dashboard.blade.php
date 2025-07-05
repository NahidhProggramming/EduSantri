@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Admin Dashboard</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Total Pengguna</h5>
            <h3>{{ $userCount }}</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Pengguna Terbaru</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
