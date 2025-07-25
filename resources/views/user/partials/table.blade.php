    @forelse ($users as $index => $user)
        <tr class="text-center">
            {{-- <td>{{ $index + $users->firstItem() }}</td> --}}
            <td class="text-center">
                <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                    <button class="btn btn-sm btn-warning btn-edit-user" data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}" data-username="{{ $user->username }}"
                        data-email="{{ $user->email }}" data-role="{{ $user->getRoleNames()->first() }}">
                        <i class="ti ti-edit"></i>
                    </button>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                            <i class="ti ti-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
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
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data user.</td>
        </tr>
    @endforelse
