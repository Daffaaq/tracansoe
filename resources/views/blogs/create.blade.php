@extends('Layouts_new.index')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Daftar Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Blog</li>
        </ol>
    </nav>
@endsection

@section('content')
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Blog</h4>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-light-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row align-items-center">
                                <label class="col-lg-3 col-form-label" for="title">Judul Blog</label>
                                <div class="col-lg-9">
                                    <input type="text" id="title" class="form-control" name="title"
                                        value="{{ old('title') }}" placeholder="Judul Blog">
                                </div>
                            </div>

                            <div class="form-group row align-items-center mt-3">
                                <label class="col-lg-3 col-form-label" for="category_blog_id">Kategori Blog</label>
                                <div class="col-lg-9">
                                    <select id="category_blog_id" name="category_blog_id" class="form-control">
                                        @foreach ($categoryBlog as $category)
                                            <option value="{{ $category->id }}">{{ $category->name_category_blog }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row align-items-center mt-3">
                                <label class="col-lg-3 col-form-label" for="content">Konten</label>
                                <div class="col-lg-9">
                                    <input type="text" id="content" class="form-control" name="content"
                                        value="{{ old('content') }}" placeholder="Konten Blog">
                                </div>
                            </div>

                            <div class="form-group row align-items-center mt-3">
                                <label class="col-lg-3 col-form-label" for="description">Deskripsi</label>
                                <div class="col-lg-9">
                                    <textarea id="description" class="form-control summernote" name="description" placeholder="Deskripsi">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row align-items-center mt-3">
                                <label class="col-lg-3 col-form-label" for="image_url">Gambar</label>
                                <div class="col-lg-9">
                                    <input type="file" id="image_url" class="form-control" name="image_url">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="{{ route('blog.index') }}" class="btn btn-primary rounded-pill me-1 mb-1">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-success rounded-pill me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-warning rounded-pill me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200, // Set the height of the editor
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['color', ['color']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            });
        });
    </script>
@endsection
