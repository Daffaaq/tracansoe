<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Cuci Sepatu Modern</title>
    <link rel="stylesheet" href="{{ asset('/LandingPage/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('/LandingPage/css/blog.css') }}"> <!-- Link ke blog.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#">CuciSepatu</a>
            </div>
            <button class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </button>
            <ul id="nav-links" class="nav-links">
                <li><a href="{{ route('landingPage') }}">Home</a></li>
                <li><a href="{{ route('landingPage') }}">About</a></li>
                <li><a href="{{ route('landingPage') }}">Services</a></li>
                <li><a href="{{ route('landingPage') }}">Blog</a></li>
                <li><a href="{{ route('landingPage') }}">Tracking</a></li>
                <li><a href="{{ route('landingPage') }}">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Blog List Section -->
    <section id="blog-list" class="blog-list-section">
        <div class="container">
            <h2 class="section-title">Semua Blog Kami</h2>
            <p class="section-description">Temukan artikel terbaru tentang tips merawat sepatu, gaya hidup, dan lainnya
                di sini.</p>

            <!-- Filter Section -->
            <div class="filter-section">
                <form action="{{ route('blog-landingPage') }}" method="GET">
                    <label for="filter-date" class="filter-label">Filter by Tanggal:</label>
                    <div class="filter-wrapper">
                        <input type="date" name="filter_date" id="filter-date" class="filter-input"
                            value="{{ request()->filter_date }}">
                        <button class="filter-btn" type="submit">Filter</button>
                    </div>
                </form>
            </div>

            <div class="blog-content-wrapper">
                <!-- Blog Grid -->
                <div class="blog-grid">
                    @if ($blogs->count())
                        @foreach ($blogs as $blog)
                            <div class="blog-card" data-aos="fade-up">
                                <div class="blog-image">
                                    <img src="{{ $blog->image_url ?? 'https://via.placeholder.com/400x250' }}"
                                        alt="{{ $blog->title }}">
                                </div>
                                <div class="blog-content">
                                    <span
                                        class="blog-category">{{ $blog->category->name_category_blog ?? 'Kategori Tidak Tersedia' }}</span>
                                    <span class="blog-date">
                                        Dipublikasikan:
                                        {{ $blog->date_publish ? \Carbon\Carbon::parse($blog->date_publish)->format('d M Y') : 'Belum Dipublikasikan' }}
                                    </span>
                                    <h3>{{ $blog->title }}</h3>
                                    <p>{{ Str::limit($blog->content, 100) }}</p>
                                    <a href="{{ route('listBlog-detail', $blog->slug) }}" class="read-more-btn">Baca
                                        Selengkapnya</a>
                                </div>
                            </div>
                        @endforeach
                        @php
                            $emptySlots = 6 - ($blogs->count() % 6);
                        @endphp

                        @if ($blogs->count() % 6 != 0)
                            <!-- Cek apakah jumlah kartu tidak kelipatan 6 -->
                            @for ($i = 0; $i < $emptySlots; $i++)
                                <div class="blog-card placeholder"></div> <!-- Placeholder Kartu -->
                            @endfor
                        @endif
                    @else
                        <p>Tidak ada blog yang ditemukan.</p>
                    @endif
                </div>


                <!-- Sidebar for Categories and Popular Posts -->
                <div class="sidebar">
                    <!-- Categories Section -->
                    <!-- Categories Section -->
                    <div class="categories-section">
                        <h3>Kategori</h3>
                        <ul class="category-list">
                            @foreach ($categories as $category)
                                <li><a
                                        href="{{ route('blog-landingPage', ['category' => $category->id]) }}">{{ $category->name_category_blog }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    <!-- Popular Posts Section -->
                    <div class="popular-posts-section">
                        <h3>Popular Posts</h3>
                        @foreach ($popularPosts as $post)
                            <div class="popular-post-card">
                                <div class="popular-post-image">
                                    <img src="{{ $post->image_url ?? 'https://via.placeholder.com/100x100' }}"
                                        alt="{{ $post->title }}">
                                </div>
                                <div class="popular-post-content">
                                    <span
                                        class="popular-post-category">{{ $post->category->name_category_blog ?? 'Kategori Tidak Tersedia' }}</span>
                                    <!-- Tanggal Popular Post -->
                                    <span class="popular-post-date">
                                        {{ $post->date_publish ? \Carbon\Carbon::parse($post->date_publish)->format('d M Y') : 'Belum Dipublikasikan' }}
                                    </span>
                                    <h4>{{ $post->title }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-links">
                {{ $blogs->appends(request()->except(['page', '_token']))->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

    {{-- <section id="blog-list" class="blog-list-section">
        <div class="container">
            <h2 class="section-title">Semua Blog Kami</h2>
            <p class="section-description">Temukan artikel terbaru tentang tips merawat sepatu, gaya hidup, dan lainnya
                di sini.</p>

            <div class="filter-section">
                <label for="filter-date" class="filter-label">Filter by Tanggal:</label>
                <div class="filter-wrapper">
                    <input type="date" id="filter-date" class="filter-input">
                    <button class="filter-btn">Filter</button>
                </div>
            </div>

            <div class="blog-content-wrapper">
                <div class="blog-grid">
                    <!-- Blog Card Example -->
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Judul Blog">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Kategori</span>
                            <span class="blog-date">Dipublikasikan: 24 September 2023</span>
                            <h3>Judul Blog</h3>
                            <p>Deskripsi singkat tentang isi blog ini. Ini adalah ringkasan menarik yang membuat pembaca
                                ingin tahu lebih lanjut.</p>
                            <a href="#" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Duplicate above block for more blog cards -->
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Judul Blog">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Kategori</span>
                            <span class="blog-date">Dipublikasikan: 24 September 2023</span>
                            <h3>Judul Blog</h3>
                            <p>Deskripsi singkat tentang isi blog ini. Ini adalah ringkasan menarik yang membuat pembaca
                                ingin tahu lebih lanjut.</p>
                            <a href="{{route('listBlog-detail')}}" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Judul Blog">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Kategori</span>
                            <span class="blog-date">Dipublikasikan: 24 September 2023</span>
                            <h3>Judul Blog</h3>
                            <p>Deskripsi singkat tentang isi blog ini. Ini adalah ringkasan menarik yang membuat pembaca
                                ingin tahu lebih lanjut.</p>
                            <a href="#" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Judul Blog">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Kategori</span>
                            <span class="blog-date">Dipublikasikan: 24 September 2023</span>
                            <h3>Judul Blog</h3>
                            <p>Deskripsi singkat tentang isi blog ini. Ini adalah ringkasan menarik yang membuat pembaca
                                ingin tahu lebih lanjut.</p>
                            <a href="#" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Judul Blog">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Kategori</span>
                            <span class="blog-date">Dipublikasikan: 24 September 2023</span>
                            <h3>Judul Blog</h3>
                            <p>Deskripsi singkat tentang isi blog ini. Ini adalah ringkasan menarik yang membuat pembaca
                                ingin tahu lebih lanjut.</p>
                            <a href="#" class="read-more-btn">Baca Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Add more blog cards as needed -->
                </div>
                <!-- Sidebar for Categories and Popular Posts -->
                <div class="sidebar">
                    <!-- Categories Section -->
                    <div class="categories-section">
                        <h3>Kategori</h3>
                        <ul class="category-list">
                            <li><a href="#">Lifestyle</a></li>
                            <li><a href="#">Tips Merawat Sepatu</a></li>
                            <li><a href="#">Fashion</a></li>
                            <li><a href="#">Gaya Hidup Sehat</a></li>
                        </ul>
                    </div>

                    <!-- Popular Posts Section -->
                    <div class="popular-posts-section">
                        <h3>Popular Posts</h3>
                        <div class="popular-post-card">
                            <div class="popular-post-image">
                                <img src="https://via.placeholder.com/100x100" alt="Popular Post 1">
                            </div>
                            <div class="popular-post-content">
                                <span class="popular-post-category">Life Tips</span>
                                <span class="popular-post-date">Mar 24, 2018</span>
                                <h4>Laziness Does Not Exist in Life</h4>
                            </div>
                        </div>
                        <div class="popular-post-card">
                            <div class="popular-post-image">
                                <img src="https://via.placeholder.com/100x100" alt="Popular Post 2">
                            </div>
                            <div class="popular-post-content">
                                <span class="popular-post-category">Technology</span>
                                <span class="popular-post-date">April 24, 2024</span>
                                <h4>The Figma Plugins I Actually Use</h4>
                            </div>
                        </div>
                        <div class="popular-post-card">
                            <div class="popular-post-image">
                                <img src="https://via.placeholder.com/100x100" alt="Popular Post 3">
                            </div>
                            <div class="popular-post-content">
                                <span class="popular-post-category">Design</span>
                                <span class="popular-post-date">Apr 15, 2024</span>
                                <h4>Every Photographer Needs Photoshop</h4>
                            </div>
                        </div>
                        <div class="popular-post-card">
                            <div class="popular-post-image">
                                <img src="https://via.placeholder.com/100x100" alt="Popular Post 4">
                            </div>
                            <div class="popular-post-content">
                                <span class="popular-post-category">Technology</span>
                                <span class="popular-post-date">Apr 5, 2024</span>
                                <h4>I Start My YouTube Channel!</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagination">
                <a href="#" class="pagination-link">1</a>
                <a href="#" class="pagination-link">2</a>
                <a href="#" class="pagination-link">3</a>
                <a href="#" class="pagination-link">Selanjutnya</a>
            </div>
        </div>
    </section> --}}

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <h3 class="footer-logo">CuciSepatu</h3>
                <p class="footer-description">Jasa Cuci Sepatu Modern & Profesional</p>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 achedev. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
