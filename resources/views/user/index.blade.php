@extends('layouts.app')

@section('title', 'Halaman Data User')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Daftar Data User</h5>

                <!-- Filter Role & Search -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                    <div class="d-flex gap-2">
                        <!-- Filter Role -->
                        <form action="{{ route('users.index') }}" method="GET" class="d-flex align-items-center">
                            <select name="role" class="form-select rounded-pill" onchange="this.form.submit()">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="wali_santri" {{ request('role') == 'wali_santri' ? 'selected' : '' }}>Wali Santri</option>
                                <option value="wali_asuh" {{ request('role') == 'wali_asuh' ? 'selected' : '' }}>Wali Asuh</option>
                            </select>
                        </form>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('users.create') }}" class="btn btn-success rounded-pill px-4 py-2">
                            <i class="ti ti-plus me-2"></i>Tambah User
                        </a>
                    </div>

                    <!-- Search -->
                    <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                        <input type="hidden" name="role" value="{{ request('role') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-start-pill"
                                placeholder="Cari Nama User..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-success rounded-end-pill">Cari</button>
                        </div>
                    </form>
                </div>


                <!-- Tabel User -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr class="text-center">
                                    <td>{{ $index + $users->firstItem() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if ($user->hasRole('admin'))
                                            {{ $user->email }}
                                        @else
                                            {{ $user->username }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->roles)
                                            {{ str_replace('_', ' ', ucwords($user->roles->first()->name ?? '-', '_')) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm rounded-pill d-flex align-items-center gap-1">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                                                    onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                    <i class="ti ti-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
