<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cuci Sepatu Modern</title>
    <link rel="stylesheet" href="{{ asset('/LandingPage/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="{{ route('landingPage') }}">CuciSepatu</a>
            </div>
            <button class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </button>
            <ul id="nav-links" class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#blog">Blog</a></li>
                <li><a href="#tracking">Tracking</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Cuci Sepatu Modern, Bersih dan Profesional</h1>
            <p>Percayakan kebersihan sepatu Anda kepada ahli kami. Layanan cuci sepatu cepat dan terpercaya.</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('/LandingPage/image/Hero-Section.jpg') }}" alt="Sepatu Bersih">
        </div>
    </section>

    <!-- Promo Section -->
    <section id="promo" class="promo-section">
        <div class="container">
            <h2>Promo Terbaru</h2>

            @if ($activePromo)
                <!-- Active Promo Banner -->
                <div class="promo-banner">
                    <div class="promo-content">
                        <h3>{{ $activePromo->nama_promosi }}</h3>
                        <p>{{ $activePromo->description }}</p>
                        <p><strong>{{ $activePromo->discount * 100 }}%</strong></p>
                        <p>Berlaku hingga: {{ date('d F Y', strtotime($activePromo->end_date)) }}</p>

                        <!-- Promo Code Box -->
                        <div class="promo-code-box">
                            <p>Gunakan Kode:</p>
                            <div class="promo-code">{{ $activePromo->kode }}</div>
                        </div>
                    </div>

                    <div class="promo-image">
                        <img src="{{ asset($activePromo->image) }}" alt="Promo Diskon">
                    </div>

                    <!-- Terms and Conditions Section (Initially Hidden) -->
                    <div class="promo-terms">
                        <h4>Syarat dan Ketentuan</h4> &nbsp;
                        <p> {{ $activePromo->terms_conditions }}</p>
                    </div>
                </div>
            @else
                <div class="no-active-promo">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Tidak ada Promo Yang Aktif Hari Ini</p>
                </div>
            @endif

            <!-- Other Promos -->
            <div class="promo-cards">
                <!-- Upcoming Promos -->
                @foreach ($upcomingPromos as $promo)
                    <div class="promo-card upcoming">
                        <div class="promo-card-content">
                            <h4>{{ $promo->nama_promosi }}</h4>
                            <p>Diskon: Segera Diumumkan!</p>
                            <p>Kode: Segera Diumumkan!</p>
                            <p>Mulai: {{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                @endforeach

                <!-- Expired Promos -->
                @foreach ($expiredPromos as $promo)
                    <div class="promo-card expired">
                        <div class="promo-card-content">
                            <h4>{{ $promo->nama_promosi }}</h4>
                            <p>Diskon: {{ $promo->discount * 100 }}%</p>
                            <p>Kode: {{ $promo->kode }}</p>
                            <p>Kedaluwarsa: {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2>About Us</h2>
            <p>Cuci Sepatu adalah layanan cuci sepatu modern yang mengutamakan kualitas dan kepuasan pelanggan. Kami
                menggunakan teknologi dan produk pembersih terbaik untuk memastikan sepatu Anda bersih, wangi, dan
                terlindungi. Dengan tim profesional, kami siap memberikan hasil terbaik untuk sepatu kesayangan Anda.
            </p>
            <p>Jangan biarkan sepatu kotor merusak gaya Anda. Percayakan kebersihan sepatu Anda kepada kami dan rasakan
                perbedaannya!</p>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <h2>Layanan Kami</h2>
            <p class="description-first">Kami menawarkan layanan perawatan dan pembersihan sepatu terbaik untuk menjaga
                penampilan dan kualitas sepatu Anda. Setiap layanan dirancang untuk memenuhi kebutuhan spesifik Anda dan
                memastikan kepuasan maksimal.</p>
            @foreach ($categories as $category)
                <!-- Kategori Induk -->
                <div class="service-category">
                    <h3>{{ $category->nama_kategori }}</h3>
                    <p>{{ $category->description }}</p>
                    <div class="subcategory-container">
                        @foreach ($category->subKriteria as $subcategory)
                            <div class="subcategory-item">
                                <strong>{{ $subcategory->nama_kategori }}</strong>
                                <p>{{ $subcategory->description }}</p>
                                <span class="price">Rp {{ number_format($subcategory->price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Plus Service Section -->
    <section id="plus-services" class="plus-services-section">
        <div class="container">
            <h2>Plus Layanan</h2>

            <p>Kami menyediakan berbagai layanan tambahan untuk memastikan sepatu Anda mendapatkan perawatan yang
                maksimal. Pilih dari layanan-layanan di bawah ini untuk menambah kenyamanan dan kepuasan Anda dalam
                menggunakan jasa kami.</p>
            <div class="plus-service-container">
                @foreach ($plusService as $service)
                    <div class="plus-service-item">
                        <strong>{{ $service->name }}</strong>
                        <span class="price">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Blog Section -->
    <section id="blog" class="blog-section">
        <div class="container">
            <h2>Blog Terbaru</h2>
            <p class="blog-description">Temukan tips dan informasi menarik tentang perawatan sepatu dan gaya hidup
                modern.</p>
            {{-- @dd(count($blog)); --}}
            @if (count($blog) > 3)
                <div class="blog-grid">
                    {{-- @dd($blog); --}}
                    @foreach ($blog as $post)
                        @php
                            // Cek apakah image_url mengandung 'http://' atau 'https://'
                            $isExternal = Str::startsWith($post->image_url, ['http://', 'https://']);
                        @endphp

                        <div class="blog-card" data-aos="fade-up">
                            <div class="blog-image">
                                {{-- Jika blog memiliki gambar, tampilkan gambar, jika tidak tampilkan placeholder --}}
                                @if ($post->image_url)
                                    @if ($isExternal)
                                        {{-- Jika URL berasal dari Faker atau sumber eksternal --}}
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                                    @else
                                        {{-- Jika gambar berasal dari storage lokal --}}
                                        <img src="{{ asset('storage/' . $post->image_url) }}"
                                            alt="{{ $post->title }}">
                                    @endif
                                @else
                                    <img src="https://via.placeholder.com/400x250" alt="{{ $post->title }}">
                                @endif
                            </div>
                            <div class="blog-content">
                                <span
                                    class="blog-category">{{ $post->category->name_category_blog ?? 'Kategori Tidak Tersedia' }}</span>
                                <span class="blog-date">
                                    Dipublikasikan:
                                    {{ $post->date_publish ? date('d F Y, H:i', strtotime($post->date_publish . ' ' . $post->time_publish)) : 'Belum Dipublikasikan' }}
                                </span>

                                <!-- Nama Penulis dalam Badge -->
                                <span class="badge blog-author">
                                    {{ $post->user->name ?? 'Admin' }}
                                </span>
                                <h3>{{ $post->title }}</h3>
                                <p>{{ Str::limit($post->content, 100) }}</p>
                                <a href="{{ route('listBlog-detail', $post->slug) }}" class="read-more">Baca
                                    Selengkapnya</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="more-blog">
                    <a href="{{ route('blog-landingPage') }}" class="more-blog-btn">
                        Lihat Semua Blog
                        <i class="fas fa-arrow-right"></i> <!-- Tambahkan ikon di sini -->
                    </a>
                    <!-- Sesuaikan URL dengan routing blog index -->
                </div>
            @else
                <div class="blog-grid">
                    <!-- Blog Card 1 -->
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Cara Merawat Sepatu Putih">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Perawatan Sepatu</span>
                            <!-- Tambahkan tanggal dan waktu publish di sini -->
                            <span class="blog-date">Dipublikasikan: 24 September 2023, 10:00</span>
                            <h3>Cara Merawat Sepatu Putih</h3>
                            <p>Pelajari cara menjaga sepatu putih Anda tetap bersih dan cerah dengan tips sederhana ini.
                            </p>
                            <a href="#" class="read-more">Baca Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Blog Card 2 -->
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Memilih Sepatu untuk Sehari-hari">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Perawatan Sepatu</span>
                            <!-- Tambahkan tanggal dan waktu publish di sini -->
                            <span class="blog-date">Dipublikasikan: 24 September 2023, 10:00</span>
                            <h3>Memilih Sepatu untuk Sehari-hari</h3>
                            <p>Pilih sepatu yang nyaman dan stylish untuk aktivitas sehari-hari Anda dengan tips
                                berikut.
                            </p>
                            <a href="#" class="read-more">Baca Selengkapnya</a>
                        </div>
                    </div>

                    <!-- Blog Card 3 -->
                    <div class="blog-card" data-aos="fade-up">
                        <div class="blog-image">
                            <img src="https://via.placeholder.com/400x250" alt="Merawat Sepatu Kulit">
                        </div>
                        <div class="blog-content">
                            <span class="blog-category">Perawatan Sepatu</span>
                            <!-- Tambahkan tanggal dan waktu publish di sini -->
                            <span class="blog-date">Dipublikasikan: 24 September 2023, 10:00</span>
                            <h3>Merawat Sepatu Kulit</h3>
                            <p>Ikuti panduan ini untuk merawat sepatu kulit Anda agar tetap awet dan terlihat bagus.</p>
                            <a href="#" class="read-more">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="more-blog">
                    <a href="{{ route('blog-landingPage') }}" class="more-blog-btn">
                        Lihat Semua Blog
                        <i class="fas fa-arrow-right"></i> <!-- Tambahkan ikon di sini -->
                    </a>
                    <!-- Sesuaikan URL dengan routing blog index -->
                </div>
            @endif

        </div>
    </section>


    <!-- Tracking Timeline Section (Modern & Fresh) -->
    <section id="tracking" class="tracking-section">
        <div class="container">
            <h2>Lacak Sepatu Anda</h2>
            <p>Masukkan kode pesanan Anda di bawah ini untuk melacak status layanan sepatu Anda:</p>
            <div class="tracking-form">
                <form id="trackingForm">
                    <div class="form-group">
                        <input type="text" id="trackingCode" name="trackingCode"
                            placeholder="Masukkan Kode Pesanan Anda">
                    </div>
                    <button type="submit" class="cta-button">Lacak Pesanan</button>
                </form>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="alert alert-danger" style="display: none;">
                Kode pesanan tidak ditemukan. Pastikan Anda memasukkan kode yang benar.
            </div>

            <div id="trackingCodeInfo"
                style="display: none; font-size: 18px; font-weight: bold; margin-bottom: 20px;">
                Kode Tracking: <span id="trackingCodeDisplay"></span>
            </div>

            <!-- Timeline Section (Initially Hidden) -->
            <div id="timelineSection" class="timeline-modern" style="display: none;">
                <!-- Timeline content will be injected here via AJAX -->
                <div id="timelineContent">
                    <!-- Status will be appended here -->
                </div>
                <!-- Button to close the timeline -->
                <button id="closeTimeline" class="cta-button" style="margin-top: 20px;">Tutup Timeline</button>
            </div>

        </div>
    </section>



    <!-- How to Transact Section -->
    <section id="how-to-transact" class="how-to-transact-section">
        <div class="container">
            <h2>Cara Transaksi di CuciSepatu</h2>
            <p>Kami menawarkan proses transaksi yang mudah dan cepat untuk semua layanan cuci sepatu, dari pembersihan
                hingga restorasi. Ikuti langkah-langkah berikut untuk mendapatkan layanan kami:</p>
            <div class="transaction-steps">
                <div class="step">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Kunjungi CuciSepatu</h3>
                    <p>Datang ke <strong>Jl. IR. SUTAMI, ATAMBUA</strong> dan sampaikan layanan yang Anda
                        butuhkan.</p>
                </div>
                <div class="step">
                    <i class="fas fa-list-alt"></i>
                    <h3>Pilih Layanan</h3>
                    <p>Diskusikan layanan yang Anda butuhkan, seperti <strong>cuci sepatu</strong>,
                        <strong>repaint</strong>, atau <strong>deep cleaning</strong>.
                    </p>
                </div>
                <div class="step">
                    <i class="fas fa-tags"></i>
                    <h3>Gunakan Promo (Jika Ada)</h3>
                    <p>Jika Anda memiliki kode promo, jangan lupa untuk menyebutkannya kepada karyawan kami di kasir.
                    </p>
                </div>
                <div class="step">
                    <i class="fas fa-receipt"></i>
                    <h3>Lakukan Pembayaran</h3>
                    <p>Selesaikan pembayaran Anda, dan kami akan segera memproses layanan yang Anda pilih.</p>
                </div>
            </div>
            <p class="note"><strong>Catatan:</strong> Pastikan Anda mengecek promo terbaru sebelum berkunjung ke toko
                kami untuk menikmati diskon spesial!</p>
        </div>
    </section>

    <!-- Membership Campaign Section -->
    <section id="membership" class="membership-section" style="padding: 40px 0;">
        <div class="container">
            <h2>Daftar Membership & Dapatkan Tambahan Diskon!</h2>
            <p>Bergabunglah dengan program membership kami untuk mendapatkan berbagai keuntungan eksklusif, termasuk
                tambahan diskon untuk setiap layanan cuci sepatu!</p>

            <!-- Rincian Membership Benefit & Harga -->
            <div class="membership-details">
                <div class="membership-plan">
                    <h3>Standard Membership</h3>
                    <p>Harga: <strong>Rp 100.000/3 bulan</strong></p>
                    <ul>
                        <li>Diskon 5% untuk layanan cuci sepatu</li>
                        <li>Prioritas layanan reguler</li>
                        <li>Diskon langsung tanpa minimal pembayaran</li>
                    </ul>
                </div>

                <div class="membership-plan">
                    <h3>Gold Membership</h3>
                    <p>Harga: <strong>Rp 250.000/6 bulan</strong></p>
                    <ul>
                        <li>Diskon 10% untuk layanan cuci sepatu</li>
                        <li>Prioritas layanan cepat</li>
                        <li>Diskon langsung tanpa minimal pembayaran</li>
                    </ul>
                </div>

                <div class="membership-plan">
                    <h3>Premium Membership</h3>
                    <p>Harga: <strong>Rp 500.000/12 bulan</strong></p>
                    <ul>
                        <li>Diskon 15% untuk layanan cuci sepatu</li>
                        <li>Prioritas layanan tercepat</li>
                        <li>Diskon langsung tanpa minimal pembayaran</li>
                    </ul>
                </div>
            </div>

            <div class="membership-call-to-action" style="text-align: center; margin-top: 30px;">
                <p>Jangan lewatkan kesempatan ini! Daftar sekarang dan nikmati keuntungan eksklusif hanya untuk member.
                </p>
                <button id="membershipButton" class="cta-button"
                    style="background-color: #007BFF; color: white; border: none; padding: 10px 20px; cursor: pointer;">
                    Daftar Membership Sekarang
                </button>
                <button id="extendButton" class="cta-button"
                    style="background-color: #002a56; color: white; border: none; padding: 10px 20px; cursor: pointer;">
                    Perpanjang Membership Sekarang
                </button>
            </div>

            <!-- Form Daftar Membership (Tersembunyi) -->
            <div id="membershipForm" style="display: none; margin-top: 30px;">
                <h3>Formulir Pendaftaran Membership</h3>
                <form id="membershipFormSubmit" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="nama_membership">Nama Lengkap</label>
                        <input type="text" id="nama_membership" name="nama_membership" class="form-control"
                            placeholder="Masukkan Nama Lengkap Anda">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="email_membership">Email</label>
                        <input type="email" id="email_membership" name="email_membership" class="form-control"
                            placeholder="Masukkan Email Anda">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="phone_membership">Nomor Telepon</label>
                        <input type="text" id="phone_membership" name="phone_membership" class="form-control"
                            placeholder="Masukkan Nomor Telepon Anda">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="alamat_membership">Alamat</label>
                        <input type="text" id="alamat_membership" name="alamat_membership" class="form-control"
                            placeholder="Masukkan Alamat Anda">
                    </div>

                    <!-- Radio Buttons untuk Kelas Membership -->
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Kelas Membership</label>
                        <div class="membership-options">
                            <label>
                                <input type="radio" name="kelas_membership" value="standard">
                                <span>Standard - Rp 100.000/3 bulan</span>
                            </label>
                            <label>
                                <input type="radio" name="kelas_membership" value="gold">
                                <span>Gold - Rp 250.000/6 bulan</span>
                            </label>
                            <label>
                                <input type="radio" name="kelas_membership" value="premium">
                                <span>Premium - Rp 500.000/12 bulan</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="buktiPembayaran">Upload Bukti Pembayaran</label>
                        <input type="file" id="buktiPembayaran" name="buktiPembayaran" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="totalPembayaran">Total Pembayaran</label>
                        <input type="number" id="totalPembayaran" name="totalPembayaran" class="form-control"
                            placeholder="Masukkan Total Pembayaran">
                    </div>
                    <button type="submit" class="cta-button"
                        style="background-color: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer;">Daftar
                        Sekarang</button>
                </form>
            </div>
            <!-- Form Perpanjangan Membership (Tersembunyi) -->
            <div id="extendMembershipForm" style="display: none; margin-top: 30px;">
                <h3>Formulir Perpanjangan Membership</h3>
                <form id="extendMembershipFormSubmit" enctype="multipart/form-data" class="membership-form">
                    @csrf
                    <div class="form-group">
                        <label for="kode">Kode Membership</label>
                        <input type="text" id="kode" name="kode" class="form-control"
                            placeholder="Masukkan Kode Membership" required>
                    </div>
                    <div class="form-group">
                        <label for="buktiPembayaran">Upload Bukti Pembayaran</label>
                        <input type="file" id="buktiPembayaran" name="buktiPembayaran" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Kelas Membership</label>
                        <div class="membership-options">
                            <label>
                                <input type="radio" name="kelas_membership" value="standard" required>
                                <span>Standard - Rp 100.000/3 bulan</span>
                            </label>
                            <label>
                                <input type="radio" name="kelas_membership" value="gold" required>
                                <span>Gold - Rp 250.000/6 bulan</span>
                            </label>
                            <label>
                                <input type="radio" name="kelas_membership" value="premium" required>
                                <span>Premium - Rp 500.000/12 bulan</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="totalPembayaran">Total Pembayaran</label>
                        <input type="number" id="totalPembayaran" name="totalPembayaran" class="form-control"
                            placeholder="Masukkan Total Pembayaran" required>
                    </div>
                    <button type="submit" class="cta-button">Perpanjang Membership</button>
                </form>
            </div>


        </div>
    </section>


    <!-- Doorprize Section (Elegant, Modern, Luxurious with Scroll Animations) -->
    <section id="doorprize-items" class="doorprize-section">
        <div class="container">
            <!-- Date Section -->
            @if ($datadoorprize->isNotEmpty())
                <div class="date-range" data-aos="fade-up">
                    <p class="date-text">
                        Doorprize Berlangsung:
                        <span
                            class="start-date">{{ \Carbon\Carbon::parse($datadoorprize->first()->tanggal_awal)->translatedFormat('d F Y') }}</span>
                        -
                        <span
                            class="end-date">{{ \Carbon\Carbon::parse($datadoorprize->first()->tanggal_akhir)->translatedFormat('d F Y') }}</span>

                    </p>
                </div>

                <h2 class="section-title" data-aos="fade-up">Doorprize</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Berikut adalah daftar hadiah doorprize menarik yang bisa kamu menangkan!
                </p>

                <div class="doorprize-grid">
                    @foreach ($datadoorprize as $doorprize)
                        <!-- Prize -->
                        <div class="prize-card" data-aos="fade-up" data-aos-delay="{{ 200 + $loop->index * 100 }}">
                            <img src="img/default-prize-image.jpg" alt="{{ $doorprize->nama_hadiah }}"
                                class="prize-image">
                            <div class="prize-body">
                                <p class="prize-name">{{ $doorprize->nama_hadiah }}</p>
                                <p class="prize-description">{{ $doorprize->deskripsi }}</p>
                                @if ($doorprize->jumlah > 0)
                                    <p class="prize-quantity">Jumlah: {{ $doorprize->jumlah }} buah</p>
                                @else
                                    <p class="prize-quantity">Hadiah Telah Dimenangkan</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-doorprize">Saat ini belum ada doorprize yang tersedia. Silakan cek kembali nanti.</p>
            @endif
        </div>
    </section>





    <!-- Doorprize Winner Section (Elegant, Modern, Luxurious with Scroll Animations) -->
    <section id="doorprize-winners" class="doorprize-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Pemenang Doorprize</h2>
            @if ($winners->isEmpty())
                <!-- No Winners Available -->
                <div class="no-winners" data-aos="fade-up" data-aos-delay="200">
                    <p>Belum ada pemenang yang diumumkan saat ini. Silakan cek kembali nanti untuk melihat siapa yang
                        beruntung!</p>
                </div>
            @else
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Selamat kepada para pemenang! Berikut adalah nomor tracking dan hadiah yang mereka terima.
                </p>
                <div class="winner-grid">
                    @foreach ($winners as $winner)
                        <!-- Winner Card -->
                        <div class="winner-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="cover left-cover"></div>
                            <div class="cover right-cover"></div>
                            <div class="winner-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="winner-body">
                                <p class="winner-name">{{ $winner->transaksi->nama_customer }}</p>
                                <p class="winner-prize">{{ $winner->hadiah->nama_hadiah }}</p>
                            </div>
                            <div class="winner-footer">
                                <p class="winner-tracking">Tracking No: {{ $winner->transaksi->tracking_number }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="congrats-note" data-aos="fade-up" data-aos-delay="500">
                    <p>Selamat kepada para pemenang! Hadiah dapat diambil dengan menunjukkan nota yang sesuai dengan
                        nomor
                        tracking yang tertera. Jangan lewatkan doorprize menarik berikutnya dari kami.</p>
                </div>
            @endif

        </div>
    </section>




    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <p>Untuk memberikan saran atau kritik mengenai layanan kami, silakan isi form di samping atau kunjungi
                lokasi kami yang tertera pada peta di bawah ini.</p>

            <div class="contact-wrapper">
                <!-- Form Kontak -->
                <div class="contact-form">
                    <form action="/advice" method="POST" id="adviceForm">
                        @csrf <!-- Tambahkan CSRF Token -->
                        <input type="hidden" id="adviceRoute" value="{{ route('advice') }}">
                        <div class="form-group" id="name-group">
                            <label for="nama">Nama Anda</label>
                            <input type="text" id="nama" name="nama" placeholder="Nama Anda"
                                autocomplete="off">
                        </div>
                        <div class="form-group" id="email-group">
                            <label for="email">Email Anda</label>
                            <input type="email" id="email" name="email" placeholder="Email Anda"
                                autocomplete="off">
                        </div>
                        <div class="form-group" id="advice-group">
                            <label for="advice">Saran/Kritik</label>
                            <textarea id="advice" name="advice" rows="5" placeholder="Tulis saran atau kritik Anda di sini"
                                autocomplete="off"></textarea>
                        </div>
                        <button type="submit" class="cta-button">Kirim Saran/Kritik</button>
                    </form>
                </div>


                <!-- Informasi Kontak -->
                <div class="contact-info">
                    <div id="map" class="maplp" style="height: 200px;"></div>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. IR. SUTAMI, ATAMBUA</p>
                    <p><i class="fas fa-phone-alt"></i> +62 812 3456 7890</p>
                    <p><i class="fas fa-envelope"></i> info@cucisepatu.com</p>
                </div>
            </div>
        </div>
    </section>


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
    <button id="backToTop"><i class="fas fa-arrow-up"></i></button>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="{{ asset('/LandingPage/js/tracking.js') }}"></script>
    <script src="{{ asset('/LandingPage/js/advice.js') }}"></script>
    <script src="{{ asset('/LandingPage/js/map.js') }}"></script>
    <script src="{{ asset('/LandingPage/js/scroll-to-top.js') }}"></script>
    <script src="{{ asset('/LandingPage/js/aos.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Confetti script running'); // Tambahkan ini untuk cek
            const confettiContainer = document.getElementById('confetti');
            console.log(confettiContainer);
            for (let i = 0; i < 100; i++) {
                const confettiPiece = document.createElement('div');
                console.log(confettiPiece);
                confettiPiece.classList.add('confetti-piece');
                confettiPiece.style.left = Math.random() * 100 + 'vw'; // Random horizontal position
                confettiPiece.style.animationDelay = Math.random() * 5 + 's'; // Random delay
                confettiContainer.appendChild(confettiPiece);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('terms-toggle').addEventListener('click', function() {
                document.querySelector('.promo-content').style.display = 'none';
                document.querySelector('.promo-image').style.display = 'none';
                document.getElementById('promo-terms').style.display = 'block';
            });

            document.getElementById('promo-back').addEventListener('click', function() {
                document.querySelector('.promo-content').style.display = 'block';
                document.querySelector('.promo-image').style.display = 'block';
                document.getElementById('promo-terms').style.display = 'none';
            });
        });
    </script>
    <script>
        document.getElementById('membershipButton').addEventListener('click', function() {
            var membershipForm = document.getElementById('membershipForm');
            var extendMembershipForm = document.getElementById('extendMembershipForm');
            var button = document.getElementById('membershipButton');

            // Sembunyikan form perpanjangan jika terbuka
            if (extendMembershipForm.style.display === "block") {
                extendMembershipForm.style.display = "none"; // Sembunyikan form perpanjangan
                document.getElementById('extendButton').textContent =
                    "Perpanjang Membership Sekarang"; // Reset teks tombol perpanjangan
            }

            // Tampilkan atau sembunyikan form pendaftaran
            if (membershipForm.style.display === "none" || membershipForm.style.display === "") {
                membershipForm.style.display = "block"; // Tampilkan form pendaftaran
                button.textContent = "Tutup Form Membership"; // Ubah teks tombol
            } else {
                membershipForm.style.display = "none"; // Sembunyikan form pendaftaran
                button.textContent = "Daftar Membership Sekarang"; // Ubah teks tombol kembali
            }
        });

        document.getElementById('extendButton').addEventListener('click', function() {
            var membershipForm = document.getElementById('membershipForm');
            var extendMembershipForm = document.getElementById('extendMembershipForm');
            var button = document.getElementById('extendButton');

            // Sembunyikan form pendaftaran jika terbuka
            if (membershipForm.style.display === "block") {
                membershipForm.style.display = "none"; // Sembunyikan form pendaftaran
                document.getElementById('membershipButton').textContent =
                    "Daftar Membership Sekarang"; // Reset teks tombol pendaftaran
            }

            // Tampilkan atau sembunyikan form perpanjangan
            if (extendMembershipForm.style.display === "none" || extendMembershipForm.style.display === "") {
                extendMembershipForm.style.display = "block"; // Tampilkan form perpanjangan
                button.textContent = "Tutup Form Perpanjangan"; // Ubah teks tombol
            } else {
                extendMembershipForm.style.display = "none"; // Sembunyikan form perpanjangan
                button.textContent = "Perpanjang Membership Sekarang"; // Ubah teks tombol kembali
            }
        });
    </script>

    <script>
        // Handle form submission via Ajax dengan konfirmasi dan validasi
        document.getElementById('membershipFormSubmit').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit form default

            // Ambil data form
            let formData = new FormData(this);
            let totalPembayaran = parseFloat(formData.get(
                'totalPembayaran')); // Ambil dan ubah totalPembayaran menjadi angka
            let kelasMembership = formData.get('kelas_membership'); // Ambil kelas membership yang dipilih

            // Validasi form: Pastikan semua field diisi
            if (!formData.get('nama_membership') || !formData.get('email_membership') || !formData.get(
                    'phone_membership') ||
                !formData.get('alamat_membership') || !kelasMembership || !formData.get('buktiPembayaran') ||
                !formData.get('totalPembayaran')) {

                // Jika ada field yang kosong, tampilkan SweetAlert error
                Swal.fire({
                    title: 'Error!',
                    text: 'Semua field harus diisi!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return; // Stop jika ada field kosong
            }

            // Validasi total pembayaran berdasarkan kelas membership yang dipilih
            let hargaYangDiharapkan = 0;

            if (kelasMembership === 'standard') {
                hargaYangDiharapkan = 100000; // Harga untuk Standard
            } else if (kelasMembership === 'gold') {
                hargaYangDiharapkan = 250000; // Harga untuk Gold
            } else if (kelasMembership === 'premium') {
                hargaYangDiharapkan = 500000; // Harga untuk Premium
            }

            // Cek apakah total pembayaran sesuai dengan kelas membership yang dipilih
            if (totalPembayaran !== hargaYangDiharapkan) {
                Swal.fire({
                    title: 'Error!',
                    text: `Total pembayaran harus sesuai dengan kelas yang dipilih. Untuk ${kelasMembership}, totalnya harus Rp ${hargaYangDiharapkan}.`,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return; // Stop jika total pembayaran tidak sesuai
            }

            // Tampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan mendaftarkan membership dengan data ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, daftar sekarang!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengonfirmasi, kirim form menggunakan fetch
                    fetch("{{ route('membership.register') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Tampilkan SweetAlert sukses
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                document.getElementById('membershipFormSubmit').reset();
                            } else {
                                // Tampilkan SweetAlert error jika ada masalah dari server
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                                document.getElementById('membershipFormSubmit').reset();
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengirimkan form.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            document.getElementById('membershipFormSubmit').reset();
                        });
                }
            });
        });
    </script>


    <script>
        document.getElementById('extendMembershipFormSubmit').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit form default

            // Ambil data form
            let formData = new FormData(this);
            let kodeMembership = formData.get('kode'); // Ambil kode membership
            let kelasMembership = formData.get('kelas_membership'); // Ambil kelas membership yang dipilih

            // Validasi form: Pastikan semua field diisi
            if (!kodeMembership || !formData.get('buktiPembayaran') || !kelasMembership) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Semua field harus diisi!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return; // Stop jika ada field kosong
            }

            // Tampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan memperpanjang membership dengan kode ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, perpanjang sekarang!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengonfirmasi, kirim form menggunakan fetch
                    fetch("{{ route('memberships.extend') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                document.getElementById('extendMembershipFormSubmit')
                                    .reset(); // Reset form setelah sukses
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat mengirimkan form.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });
    </script>

    {{-- <script>
        AOS.init({
            duration: 800, // Animation duration
            easing: 'ease-in-out', // Animation easing
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            // Hapus nilai input saat halaman dimuat atau di-refresh
            $('#adviceForm')[0].reset();

            // Tangkap event submit form
            $('#adviceForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah form dari submit biasa

                // Ambil data dari form
                var formData = {
                    nama: $('#nama').val(), // Sesuaikan nama field dengan id "nama"
                    email: $('#email').val(),
                    advice: $('#advice').val(),
                };

                // Kirim form menggunakan AJAX
                $.ajax({
                    url: "{{ route('advice') }}", // URL rute yang dituju
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Tambahkan CSRF token ke header
                    },
                    data: formData,
                    success: function(response) {
                        // Menampilkan pesan sukses dengan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        });

                        // Reset form setelah sukses
                        $('#adviceForm')[0].reset();
                    },
                    error: function(xhr) {
                        // Tampilkan pesan error dari validasi server
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;

                            // Hapus pesan error sebelumnya
                            $('.help-block').remove();
                            $('.has-error').removeClass('has-error');

                            // Tampilkan error jika ada
                            if (errors.nama) { // Ubah 'name' menjadi 'nama'
                                $("#name-group").addClass("has-error");
                                $("#name-group").append('<div class="help-block">' + errors
                                    .nama + "</div>");
                            }
                            if (errors.email) {
                                $("#email-group").addClass("has-error");
                                $("#email-group").append('<div class="help-block">' + errors
                                    .email + "</div>");
                            }
                            if (errors.advice) {
                                $("#advice-group").addClass("has-error");
                                $("#advice-group").append('<div class="help-block">' + errors
                                    .advice + "</div>");
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Silakan coba lagi.',
                                showConfirmButton: true
                            });
                        }
                    }
                });
            });

            $('#nama, #email, #advice').on('input', function() {
                var fieldGroup = $(this).closest(
                    '.form-group'); // Temukan parent .form-group dari elemen yang sedang diinput
                fieldGroup.removeClass('has-error'); // Hapus class error
                fieldGroup.find('.help-block').remove(); // Hapus pesan error
            });
        });
    </script> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const navLinks = document.getElementById('nav-links');

            hamburger.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
        });
    </script>
    {{-- <script>
        // Ketika pengguna menggulir ke bawah 100px dari bagian atas dokumen, tampilkan tombol
        window.onscroll = function() {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                document.getElementById("backToTop").style.display = "block";
            } else {
                document.getElementById("backToTop").style.display = "none";
            }
        }

        // Ketika pengguna mengklik tombol, gulir kembali ke bagian atas halaman
        document.getElementById("backToTop").addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // Animasi smooth saat kembali ke atas
            });
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            $('#trackingForm').on('submit', function(e) {
                e.preventDefault(); // Prevent form submission
                const trackingCode1 = $('#trackingCode').val();
                // Tampilkan konfirmasi SweetAlert
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin melacak pesanan dengan kode ${trackingCode1}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lacak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengonfirmasi, lakukan pengiriman AJAX

                        // Reset and hide error message, tracking code, and timeline
                        $('#errorMessage').hide();
                        $('#timelineContent').empty(); // Clear previous timeline content
                        $('#trackingCodeInfo').hide(); // Hide tracking code info

                        // Get the tracking code
                        const trackingCode = $('#trackingCode').val();

                        // Perform AJAX request to the server
                        $.ajax({
                            url: '/track-order',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                trackingCode: trackingCode,
                                _token: '{{ csrf_token() }}' // Laravel CSRF token
                            },
                            success: function(response) {
                                // Clear input after success
                                $('#trackingCode').val(''); // Clear input field

                                // Show the tracking code at the top of the timeline
                                $('#trackingCodeDisplay').text(
                                    trackingCode); // Set the tracking code text
                                $('#trackingCodeInfo')
                                    .fadeIn(); // Show the tracking code info

                                // On success, build the timeline dynamically
                                if (response.statuses && response.statuses.length > 0) {
                                    response.statuses.forEach(function(status) {
                                        $('#timelineContent').append(`
                                        <div class="timeline-item">
                                            <div class="timeline-icon">
                                                <i class="${getIconForStatus(status.status_name)}"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Status: ${status.status_name}</h4>
                                                <p>${status.description}</p>
                                                <span><i class="fas fa-calendar-alt"></i> ${status.date} <i class="fas fa-clock"></i> ${status.time}</span>
                                            </div>
                                        </div>
                                    `);
                                    });
                                    // Show the timeline section
                                    $('#timelineSection').fadeIn();
                                }
                            },
                            error: function(xhr) {
                                // Jika terjadi error, backend akan mengirimkan pesan error
                                if (xhr.status === 404) {
                                    const response = xhr.responseJSON;
                                    $('#errorMessage').html(response
                                        .error); // Tampilkan pesan error dari backend
                                } else {
                                    // Handle error lain jika ada
                                    $('#errorMessage').html(
                                        'Terjadi kesalahan. Silakan coba lagi nanti.'
                                    );
                                }
                                // On error, show the error message
                                $('#trackingCode').val('');
                                $('#errorMessage').fadeIn();

                                setTimeout(() => {
                                    $('#errorMessage').fadeOut();
                                }, 5000);
                            }
                        });
                    }
                });
            });

            // Event handler for the "Tutup Timeline" button
            $('#closeTimeline').on('click', function() {
                $('#timelineSection').fadeOut(); // Hide the timeline when the button is clicked
                $('#trackingCodeInfo').fadeOut(); // Hide the tracking code info as well
            });

            // Function to get appropriate icons based on the status
            function getIconForStatus(statusName) {
                if (statusName === 'Pesanan Diterima') {
                    return 'fas fa-check-circle';
                } else if (statusName === 'Pengerjaan Sedang Berlangsung') {
                    return 'fas fa-spinner';
                } else if (statusName === 'Pesanan Selesai') {
                    return 'fas fa-check-double';
                } else {
                    return 'fas fa-info-circle'; // Default icon
                }
            }
        });
    </script> --}}


    {{-- <script>
        // Initialize the Leaflet map
        var map = L.map('map').setView([-9.0837414, 124.89648], 13); // Coordinates of Atambua

        // Add tile layer from OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker for the store location in Atambua
        L.marker([-9.108398, 124.892494]).addTo(map)
            .bindPopup('Cuci Sepatu Modern')
            .openPopup();
    </script> --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah perubahan URL
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth' // Menambahkan animasi scroll
                    });
                }
            });
        });
    </script>

</body>

</html>
