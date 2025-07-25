@forelse ($santris as $santri)
    <tr class="text-center">
        <td class="text-center">
            <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                <a href="{{ route('santri.show', $santri->nis) }}"
                    class="btn btn-info btn-sm rounded-pill d-flex align-items-center gap-1">
                    <i class="ti ti-eye"></i>
                </a>
                <button type="button"
                    class="btn btn-warning btn-sm rounded-pill d-flex align-items-center gap-1 btn-edit-santri"
                    data-nis="{{ $santri->nis }}" data-nisn="{{ $santri->nisn }}" data-nama="{{ $santri->nama_santri }}"
                    data-tempat="{{ $santri->tempat_lahir }}" data-tanggal="{{ $santri->tanggal_lahir }}"
                    data-jk="{{ $santri->jenis_kelamin }}" data-alamat="{{ $santri->alamat }}"
                    data-ayah="{{ $santri->ayah }}" data-ibu="{{ $santri->ibu }}" data-hp="{{ $santri->no_hp }}">
                    <i class="ti ti-edit"></i>
                </button>

                <form action="{{ route('santri.destroy', $santri->nis) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </div>
        </td>
         {{-- <td>{{ $startNumber + $loop->iteration }}</td> --}}
        <td>{{ $santri->nama_santri ?? '-' }}</td>
        <td>{{ $santri->jenis_kelamin ?? '-' }}</td>
        <td>{{ $santri->alamat ?? '-' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Tidak ada data.</td>
    </tr>
@endforelse
