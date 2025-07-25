@extends('layouts.app')

@section('title', 'Jenis Pelanggaran')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-light">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Jenis Pelanggaran</h5>

            {{-- tombol + search --}}
            <div class="d-flex justify-content-between mb-4">
                <button class="btn btn-success rounded-pill mb-3"
                        data-bs-toggle="modal" data-bs-target="#modalTambahJenis">
                    <i class="ti ti-plus"></i> Tambah Jenis
                </button>

                <form action="{{ route('jenis.index') }}" method="GET" class="d-flex align-items-center">
                    <input type="text" name="search"
                           class="form-control rounded-pill px-4 py-2"
                           style="width:200px"
                           placeholder="Cari jenis..." value="{{ request('search') }}">
                </form>
            </div>

            {{-- alert --}}
            @foreach (['success','error'] as $key)
                @if(session($key))
                    <div class="alert alert-{{ $key=='success'?'success':'danger' }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            {{-- table --}}
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Aksi</th>
                        <th>Nama Jenis</th>
                        <th>Tingkat</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody id="jenis-table-body">
                    @include('jenis.partials.table', ['jenisList'=>$jenisList])
                </tbody>
            </table>

            <div id="pagination-links">
                @if($jenisList->hasPages())
                    {!! $jenisList->links('pagination::bootstrap-5') !!}
                @endif
            </div>
        </div>
    </div>

    {{-- =========== MODAL TAMBAH =========== --}}
    <div class="modal fade" id="modalTambahJenis" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('jenis.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Tambah Jenis Pelanggaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis</label>
                            <input type="text" name="nama_jenis" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">-- pilih --</option>
                                <option>Ringan</option><option>Sedang</option><option>Berat</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Poin</label>
                            <input type="number" name="poin" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan</button></div>
                </div>
            </form>
        </div>
    </div>

    {{-- =========== MODAL EDIT =========== --}}
    @foreach($jenisList as $j)
    <div class="modal fade" id="modalEditJenis{{ $j->id_jenis }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('jenis.update',$j->id_jenis) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Edit Jenis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Nama</label>
                            <input type="text" name="nama_jenis" class="form-control" value="{{ $j->nama_jenis }}" required>
                        </div>
                        <div class="mb-3"><label class="form-label">Tingkat</label>
                            <select name="tingkat" class="form-select">
                                @foreach(['Ringan','Sedang','Berat'] as $t)
                                    <option value="{{ $t }}" @selected($j->tingkat==$t)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label">Poin</label>
                            <input type="number" name="poin" class="form-control" value="{{ $j->poin }}" required>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-warning">Update</button></div>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{-- AJAX pagination + search (persis kelas) --}}
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const search=document.querySelector('input[name="search"]');
  const tbody=document.getElementById('jenis-table-body');
  const pagin=document.getElementById('pagination-links');
  let timer;
  search.addEventListener('input',()=>{clearTimeout(timer);
    timer=setTimeout(()=>fetchData(`?search=${encodeURIComponent(search.value)}`),300)});
  pagin.addEventListener('click',e=>{
    if(e.target.tagName==='A'){e.preventDefault();fetchData(e.target.href);}
  });
  function fetchData(url){
    fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}})
      .then(r=>r.json())
      .then(d=>{tbody.innerHTML=d.table;pagin.innerHTML=d.pagination;})
      .catch(err=>console.error(err));
  }
});
</script>
@endsection
