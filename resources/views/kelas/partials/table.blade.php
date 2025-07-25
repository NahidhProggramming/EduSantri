@forelse ($kelass as $kelas)
    <tr class="text-center">
        {{-- <td>{{ $loop->iteration + $kelass->firstItem() - 1 }}</td> --}}
        <td>
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                <button class="btn btn-warning btn-sm rounded-pill d-flex align-items-center gap-1" data-bs-toggle="modal"
                    data-bs-target="#modalEditKelas{{ $kelas->id_kelas }}">
                    <i class="ti ti-edit"></i>
                </button>
                <form action="{{ route('kelas.destroy', $kelas->id_kelas) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </div>
        </td>
        <td>{{ $kelas->nama_kelas }}</td>
        <td>{{ $kelas->tingkat }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Tidak ada data.</td>
    </tr>
@endforelse
