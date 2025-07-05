@forelse ($gurus as $guru)
    <tr class="text-center">
        <td>{{ $loop->iteration + $gurus->firstItem() - 1 }}</td>
        <td>{{ $guru->nip }}</td>
        <td>{{ $guru->nama_guru }}</td>
        <td>{{ $guru->jenis_kelamin }}</td>
        <td>{{ $guru->tanggal_lahir }}</td>
        <td>{{ $guru->alamat }}</td>
        <td>{{ $guru->no_whatsapp }}</td>
        <td class="text-center">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                <button type="button" class="btn btn-warning btn-sm rounded-pill btn-edit-guru"
                    data-id="{{ $guru->id_guru }}" data-nip="{{ $guru->nip }}" data-nama="{{ $guru->nama_guru }}"
                    data-jenkel="{{ $guru->jenis_kelamin }}" data-tgl="{{ $guru->tanggal_lahir }}"
                    data-alamat="{{ $guru->alamat }}" data-nohp="{{ $guru->no_whatsapp }}">
                    <i class="ti ti-edit"></i>
                </button>
                <form action="{{ route('guru.destroy', $guru->id_guru) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill d-flex align-items-center gap-1"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">Tidak ada data guru.</td>
    </tr>
@endforelse
