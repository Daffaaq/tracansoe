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
                <a href="#">CuciSepatu</a>
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
                </div>
            @else
                <div class="no-active-promo">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Tidak ada Promos Yang Aktif Hari Ini</p>
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
                            <p>Kode: {{ $promo->kode }}</p>
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
            <h2>Lacak Pesanan Anda</h2>
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
