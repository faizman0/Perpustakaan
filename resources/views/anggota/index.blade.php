@extends('layouts.main')

@section('title', 'Data Anggota')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

  

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Anggota Perpustakaan</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabelAnggota" class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Anggota</th>
                            <th width="25%">Nama</th>
                            <th width="15%">Tipe</th>
                            <th width="20%">NIS/NIP</th>
                            <th width="10%">Jenis Kelamin</th>
                            <th width="10%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anggotas as $anggota)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $anggota->kode_anggota }}</span>
                            </td>
                            <td>{{ $anggota->nama }}</td>
                            <td>
                                @if($anggota->tipe == 'Siswa')
                                    <span class="badge badge-success">Siswa</span>
                                @else
                                    <span class="badge badge-info">Guru</span>
                                @endif
                            </td>
                            <td>
                                @if($anggota->siswa)
                                    {{ $anggota->siswa->nis }}
                                @elseif($anggota->guru)
                                    {{ $anggota->guru->nip }}
                                @endif
                            </td>
                            <td>
                                @if($anggota->siswa)
                                    {{ $anggota->siswa->jenis_kelamin }}
                                @elseif($anggota->guru)
                                    {{ $anggota->guru->jenis_kelamin }}
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $anggota->status == 'aktif' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ ucfirst($anggota->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.anggota.show', $anggota->id) : route('petugas.anggota.show', $anggota->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                    <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
$(function () {
    $('.datatable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });


    // Attach search function to button click
    $('#searchButton').on('click', searchAnggota);

    // Auto close alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endpush 