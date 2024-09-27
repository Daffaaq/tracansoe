@extends('Layouts_new.index')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Daftar Blog</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Blog</h6>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="height: 50px" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="height: 50px" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('blog.create') }}" class="btn btn-primary" style="margin-right: 5px;">Tambah Blog</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="blogTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Blog</th>
                            <th>Slug Blog</th>
                            <th>Status Publikasi</th>
                            <th>Tanggal Publikasi</th>
                            <th>Waktu Publikasi</th>
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
                    Apakah Anda yakin ingin menghapus Blog ini?
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
            var dataMaster = $('#blogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('blog.list') }}',
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'status_publish',
                        name: 'status_publish'
                    },
                    {
                        data: 'date_publish',
                        name: 'date_publish',
                        render: function(data) {
                            if (data == null) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'time_publish',
                        name: 'time_publish',
                        render: function(data) {
                            if (data == null) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let actionButtons = `<a href="/dashboard/blog/edit/${data}" class="btn icon btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="/dashboard/blog/show/${data}" class="btn icon btn-sm btn-info">
                <i class="bi bi-eye"></i>
            </a>
                      <button class="btn icon btn-sm btn-danger" onclick="confirmDelete('${data}')">
                        <i class="bi bi-trash"></i>
                      </button>`;

                            return actionButtons;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });

            $('#closeModalHeader, #closeModalFooter').on('click', function() {
                $('#deleteConfirmationModal').modal('hide');
            });
        });

        function confirmDelete(uuid) {
            $('#deleteForm').attr('action', `/dashboard/blog/delete/${uuid}`);
            $('#deleteConfirmationModal').modal('show');
        }
    </script>
@endsection
