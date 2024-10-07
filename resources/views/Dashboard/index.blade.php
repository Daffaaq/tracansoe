@extends('Layouts_new.index')

@section('content')
    <style>
        /* #wheelContainer {
                                                                    display: inline-block;
                                                                    position: relative;
                                                                    width: 300px;
                                                                    height: 300px;
                                                                } */

        #wheelContainer {
            display: flex;
            /* Gunakan flexbox untuk menempatkan konten di tengah */
            justify-content: center;
            /* Pastikan roda berada di tengah secara horizontal */
            align-items: center;
            /* Pastikan roda berada di tengah secara vertikal */
            width: 100%;
            height: 300px;
            position: relative;
        }


        #wheel {
            width: 300px;
            height: 300px;
            display: inline-block;
            position: relative;
            transform-origin: center center;
            /* Pastikan roda berputar dari titik tengah */
            margin: 0 auto;
            /* Agar roda tetap berada di tengah */
            /* Pastikan roda diatur sebagai elemen relatif */
        }

        #pointer {
            position: absolute;
            top: -10px;
            /* Menyesuaikan agar pointer tetap berada di atas roda */
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            /* Mengecilkan lebar sisi kiri */
            border-right: 10px solid transparent;
            /* Mengecilkan lebar sisi kanan */
            border-top: 25px solid red;
            /* Mengecilkan tinggi pointer */
        }
    </style>

    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldChart"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Transaksi</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalTransaksi }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldDiscount"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah Kode Promosi Digunakan</h6>
                                        <h6 class="font-extrabold mb-0">{{ $jumlahKodePromosiDigunakan }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon orange mb-2">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Pembayaran Tertunggak</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalOutstanding) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldBuy"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalPaid) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Transaksi </h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-profile-visit"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Undian Doorprize</h4>
                            </div>

                            <div class="card-body">
                                <button id="doorprizeButton" class="btn btn-primary mb-4">Mulai Undian Doorprize</button>

                                <!-- Roda keberuntungan akan ditampilkan di sini -->
                                <div id="wheelContainer" style="margin-top: 20px; text-align:center; position:relative;">
                                    <div id="wheel"></div>
                                    <!-- Jarum penunjuk -->
                                    <div id="pointer">
                                    </div>
                                </div>

                                <h5 style="margin-top: 40px;">Daftar Pemenang Undian:</h5>
                                <div id="winnerList" class="mt-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Customer</th>
                                                <th>Email</th>
                                                <th>Tracking Number</th>
                                                <th>Hadiah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Loop melalui semua pemenang yang diambil dari database -->
                                            @if (isset($winners) && count($winners) > 0)
                                                @foreach ($winners as $winner)
                                                    <tr>
                                                        <td>{{ $winner->transaksi->nama_customer }}</td>
                                                        <td>{{ $winner->transaksi->email_customer }}</td>
                                                        <td>{{ $winner->transaksi->tracking_number }}</td>
                                                        <td>{{ $winner->hadiah->nama_hadiah }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">Belum ada pemenang.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <h5 style="margin-top: 40px;">Daftar Hadiah dan Sisa:</h5>
                                <div id="hadiahList" class="mt-3">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Hadiah</th>
                                                <th>Sisa Hadiah</th>
                                            </tr>
                                        </thead>
                                        <tbody id="hadiahListBody">
                                            <!-- Daftar hadiah akan dimuat di sini secara dinamis -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Profile Visit</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="d-flex align-items-center">
                                            <svg class="bi text-primary" width="32" height="32" fill="blue"
                                                style="width: 10px">
                                                <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                            </svg>
                                            <h5 class="mb-0 ms-3">Europe</h5>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <h5 class="mb-0 text-end">862</h5>
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-europe"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7">
                                        <div class="d-flex align-items-center">
                                            <svg class="bi text-success" width="32" height="32" fill="blue"
                                                style="width: 10px">
                                                <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                            </svg>
                                            <h5 class="mb-0 ms-3">America</h5>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <h5 class="mb-0 text-end">375</h5>
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-america"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7">
                                        <div class="d-flex align-items-center">
                                            <svg class="bi text-danger" width="32" height="32" fill="blue"
                                                style="width: 10px">
                                                <use xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                            </svg>
                                            <h5 class="mb-0 ms-3">Indonesia</h5>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <h5 class="mb-0 text-end">1025</h5>
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-indonesia"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Saran/Kritik Terbaru</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Saran/kritik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($advice as $comment)
                                                <tr>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md">
                                                                <!-- Placeholder image if needed -->
                                                                <img src="{{ asset('assets/compiled/jpg/8.jpg') }}"
                                                                    alt="people 1" />
                                                            </div>
                                                            <p class="font-bold ms-3 mb-0">{{ $comment->nama }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class="mb-0">{{ $comment->advice }}</p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center">No advice available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Face 1" />
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                <h6 class="text-muted mb-0">{{ Auth::user()->role }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header">
                        <h4>Recent Messages</h4>
                    </div>
                    <div class="card-content pb-4">
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="./assets/compiled/jpg/4.jpg" />
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">Hank Schrader</h5>
                                <h6 class="text-muted mb-0">@johnducky</h6>
                            </div>
                        </div>
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="./assets/compiled/jpg/5.jpg" />
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">Dean Winchester</h5>
                                <h6 class="text-muted mb-0">@imdean</h6>
                            </div>
                        </div>
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="./assets/compiled/jpg/1.jpg" />
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">John Dodol</h5>
                                <h6 class="text-muted mb-0">@dodoljohn</h6>
                            </div>
                        </div>
                        <div class="px-4">
                            <button class="btn btn-block btn-xl btn-outline-primary font-bold mt-3">
                                Start Conversation
                            </button>
                        </div>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Promo</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-visitors-profile"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="easing.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/spin-wheel@5.0.2/dist/spin-wheel-iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        var wheel;
        var selectedHadiahId; // Variabel untuk menyimpan hadiah_id yang dipilih

        document.addEventListener('DOMContentLoaded', function() {
            // Ketika halaman selesai dimuat, ambil data hadiah
            fetch('{{ route('get-hadiah-data') }}')
                .then(response => response.json())
                .then(data => {
                    const wheelContainer = document.querySelector(
                        '#wheelContainer'); // Container for the wheel and pointer
                    const container = document.querySelector('#wheel'); // Elemen untuk roda
                    const pointer = document.querySelector('#pointer'); // Pointer element

                    if (data.success) {
                        // Cek jika data hadiah kosong
                        if (data.hadiah.length === 0) {
                            container.style.display = 'none'; // Sembunyikan roda jika tidak ada hadiah
                            pointer.style.display = 'none'; // Sembunyikan pointer jika tidak ada hadiah
                            return; // Hentikan eksekusi jika tidak ada hadiah
                        }



                        // Inisialisasi roda undian dengan data hadiah
                        const hadiahItems = data.hadiah.map(hadiah => ({
                            label: hadiah.nama_hadiah
                        }));

                        // Pilih hadiah secara acak dari daftar hadiah yang tersedia
                        const randomIndex = Math.floor(Math.random() * data.hadiah.length);
                        selectedHadiahId = data.hadiah[randomIndex].id; // Mengambil hadiah secara acak

                        // Konfigurasi roda dengan hadiah yang diterima, termasuk rotationSpeed dan spins yang diterima dari backend
                        const props = {
                            items: hadiahItems,
                            rotationSpeed: 5000, // Set kecepatan rotasi
                            animation: {
                                duration: 20000, // Durasi total 20 detik
                                spins: 10, // Set jumlah putaran
                                easing: function(t) {
                                    return 1 - Math.pow(1 - t,
                                        4); // Custom easing function untuk memperlambat di akhir
                                }
                            }
                        };

                        console.log('Hadiah Items:', hadiahItems);
                        console.log('Props:', props);

                        // Cek apakah objek Wheel tersedia di dalam spinWheel
                        if (spinWheel && typeof spinWheel.Wheel === 'function') {
                            wheel = new spinWheel.Wheel(container, props); // Menggunakan spinWheel.Wheel
                            console.log(wheel); // Debug untuk melihat objek roda
                            container.style.display = 'block'; // Tampilkan roda jika ada hadiah
                            pointer.style.display = 'block'; // Tampilkan pointer jika ada hadiah
                        } else {
                            console.error("Wheel is not available in spinWheel");
                        }
                    } else {
                        container.style.display = 'none'; // Sembunyikan roda jika tidak ada hadiah
                        pointer.style.display = 'none'; // Sembunyikan pointer jika tidak ada hadiah
                    }
                })
                .catch(error => console.error('Error fetching hadiah data:', error));

            fetch('{{ route('hadiah-data') }}') // Ini adalah fetch untuk getHadiah
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayHadiahList(data
                            .hadiahData); // Panggil fungsi untuk menampilkan daftar hadiah di tabel
                    } else {
                        console.error('Gagal mengambil data hadiah');
                    }
                })
                .catch(error => console.error('Error fetching hadiah list:', error));
        });

        function displayHadiahList(hadiahList) {
            const hadiahListBody = document.querySelector('#hadiahListBody');
            if (hadiahListBody) {
                hadiahListBody.innerHTML = ''; // Kosongkan tabel sebelum diisi ulang
                hadiahList.forEach(hadiahData => {
                    hadiahListBody.innerHTML += `
                    <tr>
                        <td>${hadiahData.nama_hadiah}</td>
                        <td>${hadiahData.jumlah}</td>
                    </tr>
                `;
                });
            }
        }

        function updateWheelData() {
            // Fetch updated hadiah data
            fetch('{{ route('get-hadiah-data') }}')
                .then(response => response.json())
                .then(data => {
                    const container = document.querySelector('#wheel'); // Elemen untuk roda
                    const pointer = document.querySelector('#pointer'); // Pointer element

                    if (data.success) {
                        // Cek jika data hadiah kosong
                        if (data.hadiah.length === 0) {
                            container.style.display = 'none'; // Sembunyikan roda jika tidak ada hadiah
                            pointer.style.display = 'none'; // Sembunyikan pointer jika tidak ada hadiah
                            // Hapus roda jika sudah ada
                            if (wheel) {
                                container.innerHTML = ''; // Clear out old wheel elements
                                wheel = null; // Set wheel to null to indicate it doesn't exist
                            }
                            return; // Hentikan eksekusi jika tidak ada hadiah
                        }



                        // Rebuild the wheel with the new hadiah data
                        const hadiahItems = data.hadiah.map(hadiah => ({
                            label: hadiah.nama_hadiah
                        }));

                        // Update selectedHadiahId to use the first item or custom logic
                        const randomIndex = Math.floor(Math.random() * data.hadiah.length);
                        selectedHadiahId = data.hadiah[randomIndex].id; // Mengambil hadiah secara acak

                        const props = {
                            items: hadiahItems,
                            rotationSpeed: 5000, // Set kecepatan rotasi
                            animation: {
                                duration: 20000, // Durasi total 20 detik
                                spins: 10, // Set jumlah putaran
                                easing: function(t) {
                                    return 1 - Math.pow(1 - t,
                                        4); // Custom easing function untuk memperlambat di akhir
                                }
                            }
                        };
                        console.log('Updated Hadiah Items:', hadiahItems);
                        console.log('Updated Props:', props);

                        // If the wheel already exists, remove or destroy it and recreate it with new props
                        if (wheel) {
                            // Clean up the wheel element manually
                            container.innerHTML = ''; // Clear out old wheel elements

                            // Reinitialize the wheel with the updated hadiah data
                            if (spinWheel && typeof spinWheel.Wheel === 'function') {
                                wheel = new spinWheel.Wheel(container, props);
                                console.log('Wheel Reinitialized:', wheel); // Debug to check new wheel
                                container.style.display = 'block'; // Tampilkan roda jika ada hadiah
                                pointer.style.display = 'block'; // Tampilkan pointer jika ada hadiah
                            } else {
                                console.error('Wheel is not available in spinWheel');
                            }
                        }
                    } else {
                        container.style.display = 'none'; // Sembunyikan roda jika tidak ada hadiah
                        pointer.style.display = 'none'; // Sembunyikan pointer jika tidak ada hadiah
                    }
                })
                .catch(error => console.error('Error fetching updated hadiah data:', error));

            fetch('{{ route('hadiah-data') }}') // Ini adalah fetch untuk getHadiah
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayHadiahList(data
                            .hadiahData); // Panggil fungsi untuk menampilkan daftar hadiah di tabel
                    } else {
                        console.error('Gagal mengambil data hadiah');
                    }
                })
                .catch(error => console.error('Error fetching hadiah list:', error));
        }


        document.getElementById('doorprizeButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat membatalkan setelah memulai undian!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, mulai undian!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('doorprizeButton').disabled = true;
                    document.getElementById('doorprizeButton').innerText = "Mengundi...";

                    fetch('{{ route('pick-doorprize-winner') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                hadiah_id: selectedHadiahId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const winningItemIndex = data.winningItemIndex;
                                const totalItems = wheel ? wheel.items.length : 0;

                                if (totalItems === 0) {
                                    Swal.fire(
                                        'Perhatian!',
                                        data.message,
                                        'warning'
                                    );
                                    document.getElementById('doorprizeButton').disabled = false;
                                    document.getElementById('doorprizeButton').innerText =
                                        "Mulai Undian Doorprize";
                                    return;
                                }

                                if (winningItemIndex >= 0 && winningItemIndex < totalItems) {

                                    if (wheel && typeof wheel.spinToItem === 'function') {
                                        wheel.spinToItem(winningItemIndex, 10000, true, 6, 1);

                                        setTimeout(() => {
                                            updateWheelData();

                                            document.getElementById('doorprizeButton')
                                                .disabled = false;
                                            document.getElementById('doorprizeButton')
                                                .innerText = "Mulai Undian Doorprize";

                                            const winner = data.winners[0];
                                            Swal.fire(
                                                'Selamat!',
                                                `${winner.nama_customer} dengan tracking number ${winner.tracking_number} memenangkan ${winner.hadiah}`,
                                                'success'
                                            );

                                            let winnerList = document.querySelector(
                                                '#winnerList tbody');
                                            if (!winnerList) {
                                                console.error(
                                                    'Element #winnerList tbody tidak ditemukan.'
                                                );
                                                return;
                                            }

                                            const noWinnerRow = document.querySelector(
                                                '#winnerList tbody tr td[colspan="4"]');
                                            if (noWinnerRow) {
                                                noWinnerRow.parentNode.remove();
                                            }

                                            // Tambahkan pemenang baru ke tabel tanpa menghapus pemenang sebelumnya
                                            data.winners.forEach(winner => {
                                                winnerList.innerHTML += `
                                            <tr>
                                                <td>${winner.nama_customer}</td>
                                                <td>${winner.email_customer}</td>
                                                <td>${winner.tracking_number}</td>
                                                <td>${winner.hadiah}</td>
                                            </tr>
                                        `;
                                            });

                                        }, 10000); // 4000 ms, sesuai durasi putaran
                                    } else {
                                        console.error(
                                            'Wheel object or method spinToItem not available');
                                    }
                                } else {
                                    console.error('Invalid winningItemIndex:', winningItemIndex);
                                    alert('Terjadi kesalahan: indeks item pemenang tidak valid.');
                                }
                            } else {
                                Swal.fire(
                                    'Perhatian!',
                                    data.message,
                                    'warning'
                                );
                                document.getElementById('doorprizeButton').disabled = false;
                                document.getElementById('doorprizeButton').innerText =
                                    "Mulai Undian Doorprize";
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Perhatian!',
                                'Terjadi kesalahan saat mengundi doorprize.',
                                'warning'
                            );
                            document.getElementById('doorprizeButton').disabled = false;
                            document.getElementById('doorprizeButton').innerText =
                                "Mulai Undian Doorprize";
                        });
                }
            });
        });
    </script>
    <script>
        // Pastikan tidak ada syntax error dan posisi variabel sudah benar
        // Function to format numbers to Rupiah
        function formatRupiah(amount) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            });
            return formatter.format(amount);
        }

        var totalPendapatanPerBulan = {!! json_encode($totalPendapatanPerBulan) !!};

        // Format each value to Rupiah
        var totalPendapatanFormatted = totalPendapatanPerBulan.map(formatRupiah);

        // Now you can use totalPendapatanFormatted for your chart or display
        // console.log(totalPendapatanFormatted);
        // console.log(totalPendapatanPerBulan); // Cek apakah data sudah benar

        var totalPromosi = {!! json_encode($promosiData) !!};
        console.log(totalPromosi);
    </script>
@endsection
