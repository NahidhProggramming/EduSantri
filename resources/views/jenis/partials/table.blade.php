@forelse($jenisList as $jp)
    <tr class="text-center">
        <td>
            <div class="d-flex flex-column flex-md-row justify-content-center gap-1">
                <button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#modalEditJenis{{ $jp->id_jenis }}"><i class="ti ti-edit"></i></button>
                <form action="{{ route('jenis.destroy', $jp->id_jenis) }}" method="POST"
                    onsubmit="return confirm('Hapus data?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm rounded-pill"><i class="ti ti-trash"></i></button>
                </form>
            </div>
        </td>
        <td>{{ $jp->nama_jenis }}</td>
        <td>{{ $jp->tingkat }}</td>
        <td>{{ $jp->poin }}</td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Tidak ada data.</td>
    </tr>
@endforelse
