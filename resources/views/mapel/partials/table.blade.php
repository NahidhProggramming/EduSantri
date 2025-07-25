@if ($mapels->count())
    @foreach ($mapels as $mapel)
        <tr class="text-center">
            {{-- <td>{{ $loop->iteration + $mapels->firstItem() - 1 }}</td> --}}
            <td>
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-warning btn-sm rounded-pill d-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#modalEditMapel{{ $mapel->id_mapel }}">
                        <i class="ti ti-edit"></i>
                    </button>

                    <form action="{{ route('mapel.destroy', $mapel->id_mapel) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="ti ti-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
            <td>{{ $mapel->nama_mapel }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="3" class="text-center">Tidak ada data.</td>
    </tr>
@endif
