@extends('Layouts_new.index')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Daftar Kategori Blog</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori Blog</h6>
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
                <a href="{{ url('/dashboard/kategori-blog/create') }}" class="btn btn-primary"
                    style="margin-right: 5px;">Tambah Kategori Blog</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="categoryBlogTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori Blog</th>
                            <th>Slug Kategori Blog</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            var dataMaster = $('#categoryBlogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('kategori-blog.list') }}',
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
                        data: 'name_category_blog',
                        name: 'name_category_blog'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'uuid',
                        name: 'uuid',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                                <a href="/dashboard/kategori-blog/edit/${data}" class="btn icon btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn icon btn-sm btn-danger" onclick="confirmDelete('${data}')">
                                    <i class="bi bi-trash"></i>
                                </button>`;
                        }
                    }
                ],
                autoWidth: false,
                drawCallback: function(settings) {
                    $('a').tooltip();
                }
            });
        });

        function confirmDelete(uuid) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kategori blog akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/dashboard/kategori-blog/delete/${uuid}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Dihapus!', response.message, 'success')
                                    .then(() => $('#categoryBlogTable').DataTable().ajax.reload());
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let message = 'Tidak dapat menghubungi server.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire('Gagal!', message, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
