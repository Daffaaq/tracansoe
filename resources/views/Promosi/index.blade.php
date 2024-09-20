@extends('Layouts_new.index')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Daftar Promosi</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Promosi</h6>
        </div>
        @if (session('error'))
            <div class="alert alert-light-danger alert-dismissible fade show" style="height: 50px" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-light-success alert-dismissible fade show" style="height: 50px" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            @if (auth()->user()->role == 'superadmin')
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ url('/dashboard/promosi/create') }}" class="btn btn-primary"
                        style="margin-right: 5px;">Tambah
                        Promosi</a>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="periodeTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Promosi</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Kode Promosi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" id="closeModalHeader" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Promosi ini?
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalFooter" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var dataMaster = $('#periodeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('promosi.list') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_promosi',
                        name: 'nama_promosi'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data) {
                            var date = new Date(data);
                            return date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data) {
                            var date = new Date(data);
                            return date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                        }
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let actionButtons = `<a href="/dashboard/promosi/show/${data}" class="btn icon btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                             </a>`;

                            // Cek apakah user memiliki role superadmin
                            @if (auth()->user()->role == 'superadmin')
                                actionButtons += `<a href="/dashboard/promosi/edit/${data}" class="btn icon btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                              </a>
                              <button class="btn icon btn-sm btn-danger" onclick="confirmDelete('${data}')">
                                <i class="bi bi-trash"></i>
                              </button>`;
                            @endif

                            return actionButtons;
                        }
                    }

                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            console.log("DataTable loaded");

            $('#closeModalHeader, #closeModalFooter').on('click', function() {
                console.log('close');
                $('#deleteConfirmationModal').modal('hide');
            });

            console.log("data masuk");
        });

        function confirmDelete(uuid) {
            $('#deleteForm').attr('action', `/dashboard/promosi/delete/${uuid}`);
            $('#deleteConfirmationModal').modal('show');
        }
    </script>
@endsection
