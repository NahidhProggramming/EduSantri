@extends('layouts.app')

@section('title', 'Detail Data Santri')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-light">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Detail Data Santri</h5>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('santri.index') }}" class="btn btn-danger rounded" data-bs-toggle="tooltip"
                        title="Kembali ke Daftar Santri">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>


                {{-- Informasi Diri --}}
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Informasi Diri</span>
                        <button id="toggleInformasiDiri" class="btn btn-outline-secondary btn-sm">
                            <i id="iconInformasiDiri" class="ti ti-eye"></i>
                        </button>
                    </div>
                    <div class="card-body row">
                        <div class="text-center mb-3">
                            @if ($santri->foto)
                                <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto Santri"
                                    class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                            @else
                                <img src="{{ asset('images/profile/user-1.jpg') }}" alt="Foto Santri"
                                    class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                            @endif
                        </div>
                        <table class="table table-striped table-hover shadow-sm">
                            <tr>
                                <th>NIS</th>
                                <th>:</th>
                                <td class="hidden-data-diri">{{ $santri->nis }}</td>
                            </tr>
                            <tr>
                                <th>NISN</th>
                                <th>:</th>
                                <td class="hidden-data-diri">{{ $santri->nisn }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <th>:</th>
                                <td class="hidden-data-diri">{{ $santri->nama_santri }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <th>:</th>
                                <td class="hidden-data-diri">
                                    {{ $santri->tempat_lahir }},
                                    {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->translatedFormat('d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <th>:</th>
                                <td class="hidden-data-diri">{{ $santri->jenis_kelamin }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Orang Tua --}}
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Data Orang Tua</span>
                        <button id="toggleOrtu" class="btn btn-outline-secondary btn-sm">
                            <i id="iconOrtu" class="ti ti-eye"></i>
                        </button>
                    </div>
                    <div class="card-body row">
                        <table class="table table-striped table-hover shadow-sm">
                            <tr>
                                <th>Ayah</th>
                                <th>:</th>
                                <td class="hidden-data-ortu">{{ $santri->ayah }}</td>
                            </tr>
                            <tr>
                                <th>Ibu</th>
                                <th>:</th>
                                <td class="hidden-data-ortu">{{ $santri->ibu }}</td>
                            </tr>
                            <tr>
                                <th>No WhatsApp</th>
                                <th>:</th>
                                <td class="hidden-data-ortu">{{ $santri->no_hp }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function hideData(selector) {
                document.querySelectorAll(selector).forEach(el => {
                    el.dataset.realValue = el.textContent;
                    el.textContent = "******";
                    el.dataset.hidden = "true";
                });
            }

            function toggleData(buttonId, dataSelector, iconId) {
                const button = document.getElementById(buttonId);
                if (!button) return;

                button.addEventListener('click', function() {
                    const fields = document.querySelectorAll(dataSelector);
                    const icon = document.getElementById(iconId);
                    if (!fields.length || !icon) return;

                    const isHidden = fields[0].dataset.hidden === "true";
                    fields.forEach(el => {
                        if (isHidden) {
                            el.textContent = el.dataset.realValue;
                        } else {
                            el.dataset.realValue = el.textContent;
                            el.textContent = "******";
                        }
                        el.dataset.hidden = (!isHidden).toString();
                    });

                    icon.classList.toggle('ti-eye');
                    icon.classList.toggle('ti-eye-off');
                });
            }

            // Inisialisasi sembunyikan data
            hideData('.hidden-data-diri');
            hideData('.hidden-data-ortu');
            hideData('.hidden-data-asrama');

            // Toggle untuk tiap bagian
            toggleData('toggleInformasiDiri', '.hidden-data-diri', 'iconInformasiDiri');
            toggleData('toggleOrtu', '.hidden-data-ortu', 'iconOrtu');
            toggleData('toggleAsrama', '.hidden-data-asrama', 'iconAsrama');
        });
    </script>
@endsection
