<tbody>
    @forelse ($akademiks as $akademik)
        <tr class="text-center">
            <td>
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                    <button type="button"
                        class="btn btn-warning btn-sm rounded-pill d-flex align-items-center gap-1 btn-edit-akademik"
                        data-id="{{ $akademik->id_tahun_akademik }}" data-semester="{{ $akademik->semester }}"
                        data-keterangan="{{ $akademik->semester_aktif }}">
                        <i class="ti ti-edit"></i>
                    </button>

                    <form action="{{ route('akademik.destroy', $akademik->id_tahun_akademik) }}" method="POST"
                        style="display: inline;">
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
            <td>
                @if ($akademik->semester_aktif === 'Aktif')
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Tidak Aktif</span>
                @endif
            </td>
            {{-- <td>{{ $loop->iteration }}</td> --}}
            <td>{{ $akademik->tahun_akademik }}</td>
            <td>{{ $akademik->semester }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada data.</td>
        </tr>
    @endforelse
</tbody>
