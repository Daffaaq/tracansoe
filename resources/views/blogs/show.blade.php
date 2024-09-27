@extends('Layouts_new.index')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Daftar Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Blog</li>
        </ol>
    </nav>
@endsection

@section('content')
    <section id="detail-blog">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="height: 50px" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Blog</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <strong>Judul Blog:</strong>
                            </div>
                            <div class="col-lg-9">
                                {{ $blog->title }}
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Kategori Blog:</strong>
                            </div>
                            <div class="col-lg-9">
                                @foreach ($categoryBlog as $category)
                                    @if ($category->id == $blog->category_blog_id)
                                        {{ $category->name_category_blog }}
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Konten:</strong>
                            </div>
                            <div class="col-lg-9">
                                {{ $blog->content }}
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Deskripsi:</strong>
                            </div>
                            <div class="col-lg-9">
                                {!! $blog->description !!}
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Status Publish:</strong>
                            </div>
                            <div class="col-lg-9">
                                @if ($blog->status_publish == 'published')
                                    <span class="badge bg-success">Published</span>
                                @elseif ($blog->status_publish == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif ($blog->status_publish == 'deleted')
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-warning">Unknown</span>
                                @endif
                            </div>
                        </div>


                        @php
                            // Cek apakah image_url mengandung 'http://' atau 'https://'
                            $isExternal = Str::startsWith($blog->image_url, ['http://', 'https://']);
                        @endphp

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Gambar:</strong>
                            </div>
                            <div class="col-lg-9">
                                @if ($blog->image_url)
                                    @if ($isExternal)
                                        {{-- Jika URL berasal dari Faker atau sumber eksternal --}}
                                        <img src="{{ $blog->image_url }}" alt="Gambar Blog" class="img-fluid"
                                            width="200">
                                    @else
                                        {{-- Jika gambar berasal dari storage lokal --}}
                                        <img src="{{ asset('storage/' . $blog->image_url) }}" alt="Gambar Blog"
                                            class="img-fluid" width="200">
                                    @endif
                                @else
                                    <p>Tidak ada gambar.</p>
                                @endif
                            </div>
                        </div>


                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Tanggal Publish:</strong>
                            </div>
                            <div class="col-lg-9">
                                {{ $blog->date_publish ?? '-' }}
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <strong>Waktu Publish:</strong>
                            </div>
                            <div class="col-lg-9">
                                {{ $blog->time_publish ?? '-' }}
                            </div>
                        </div>

                        {{-- Tombol Publish, Draft, dan Delete Status --}}
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-start gap-3">
                                {{-- Tombol Publish --}}
                                @if ($blog->status_publish == 'draft' || $blog->status_publish == 'deleted')
                                    <form action="{{ route('blog.publish', $blog->uuid) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success rounded-pill" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Publish">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Draft --}}
                                @if ($blog->status_publish == 'published' || $blog->status_publish == 'deleted')
                                    <form action="{{ route('blog.draft', $blog->uuid) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-secondary rounded-pill"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Set to Draft">
                                            <i class="bi bi-file-earmark"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Delete (Soft Delete - Update Status) --}}
                                @if ($blog->status_publish == 'draft' || $blog->status_publish == 'published')
                                    <form action="{{ route('blog.delete', $blog->uuid) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger rounded-pill" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Soft Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol untuk Hapus Permanen --}}
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end gap-3">
                                {{-- Tombol Kembali --}}
                                <a href="{{ route('blog.index') }}" class="btn btn-primary rounded-pill"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Kembali">
                                    <i class="bi bi-arrow-left"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('blog.edit', $blog->uuid) }}" class="btn btn-warning rounded-pill"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Tombol Hapus Permanen (Destroy) --}}
                                <form action="{{ route('blog.destroy', $blog->uuid) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus blog ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Hapus Permanen">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>
    </section>
@endsection
