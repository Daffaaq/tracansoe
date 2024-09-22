@extends('Layouts_new.index')

<style>
    .alert {
        position: relative;
    }

    .btn-close {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
    }
</style>

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Daftar Transaksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Transaksi</li>
        </ol>
    </nav>
@endsection

@section('content')
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header text-black">
                        <h4 class="card-title">Tambah Transaksi</h4>
                    </div>
                    @if (session('error'))
                        <div class="alert alert-light-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <!-- Form Transaksi -->
                        <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Nama Customer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_customer" class="form-label">Nama Customer</label>
                                        <input type="text" id="nama_customer" class="form-control" name="nama_customer"
                                            value="{{ old('nama_customer') }}" placeholder="Nama Customer">
                                    </div>
                                </div>

                                <!-- Email Customer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_customer" class="form-label">Email</label>
                                        <input type="email" id="email_customer" class="form-control" name="email_customer"
                                            value="{{ old('email_customer') }}" placeholder="Email Customer">
                                    </div>
                                </div>

                                <!-- No Telepon -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notelp_customer" class="form-label">No. Telepon</label>
                                        <input type="text" id="notelp_customer" class="form-control"
                                            name="notelp_customer" value="{{ old('notelp_customer') }}"
                                            placeholder="No. Telepon Customer">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat_customer" class="form-label">Alamat</label>
                                        <input type="text" id="alamat_customer" class="form-control"
                                            name="alamat_customer" value="{{ old('alamat_customer') }}"
                                            placeholder="Alamat Customer">
                                    </div>
                                </div>

                                <!-- Status Pembayaran -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status Pembayaran</label>
                                        <select id="status" class="form-select" name="status">
                                            <option value="" {{ old('status') == '' ? 'selected' : '' }}>Pilih
                                            </option>
                                            <option value="downpayment"
                                                {{ old('status') == 'downpayment' ? 'selected' : '' }}>Downpayment</option>
                                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Downpayment Amount -->
                                <div class="col-md-6" id="downpayment-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="downpayment_amount" class="form-label">Jumlah DP</label>
                                        <input type="text" id="downpayment_amount" class="form-control numeric-only"
                                            name="downpayment_amount" value="{{ old('downpayment_amount') }}"
                                            placeholder="Jumlah DP" inputmode="numeric">
                                    </div>
                                </div>

                                <!-- Remaining Payment -->
                                <div class="col-md-6" id="remaining-payment-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="remaining_payment" class="form-label">Sisa Pembayaran</label>
                                        <input type="text" id="remaining_payment" class="form-control"
                                            name="remaining_payment" placeholder="Sisa Pembayaran" readonly>
                                    </div>
                                </div>

                                <!-- Pilih Kategori dan Jumlah -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category_hargas" class="form-label">Kategori & Harga</label>
                                        <div class="border p-3 rounded">
                                            @foreach ($categories as $category)
                                                @if (!$category->parent_id)
                                                    <!-- Kategori Induk -->
                                                    <h5 class="mt-3">{{ $category->nama_kategori }}</h5>
                                                    @foreach ($categories as $subCategory)
                                                        @if ($subCategory->parent_id == $category->id)
                                                            <!-- Sub-kategori di bawah kategori induk -->
                                                            <div class="form-check d-flex align-items-center mb-2">
                                                                <input class="form-check-input me-2 category-checkbox"
                                                                    type="checkbox"
                                                                    data-price="{{ $subCategory->price }}"
                                                                    name="category_hargas[{{ $subCategory->id }}][id]"
                                                                    value="{{ $subCategory->id }}"
                                                                    id="category_{{ $subCategory->id }}">
                                                                <label class="form-check-label me-auto"
                                                                    for="category_{{ $subCategory->id }}">
                                                                    {{ $subCategory->nama_kategori }} -
                                                                    Rp{{ $subCategory->price }}
                                                                </label>
                                                                <input type="number"
                                                                    name="category_hargas[{{ $subCategory->id }}][qty]"
                                                                    class="category-qty form-control w-25"
                                                                    placeholder="Qty" data-id="{{ $subCategory->id }}"
                                                                    style="max-width: 80px;">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>


                                <!-- Pilih Plus Services -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="plus_services" class="form-label">Layanan Tambahan</label>
                                        <div class="border p-3 rounded">
                                            @foreach ($plus_services as $service)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input me-2 plus-service-checkbox"
                                                        type="checkbox" data-price="{{ $service->price }}"
                                                        name="plus_services[]" value="{{ $service->id }}"
                                                        id="plus_service_{{ $service->id }}">
                                                    <label class="form-check-label"
                                                        for="plus_service_{{ $service->id }}">{{ $service->name }} -
                                                        Rp{{ $service->price }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Harga -->
                                <div class="row">
                                    <!-- Total Harga -->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="total_harga" class="col-lg-3 col-form-label">Total Harga</label>
                                            <div class="col-lg-9">
                                                <input type="text" id="total_harga" class="form-control"
                                                    name="total_harga" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input untuk Kode Promosi -->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="promosi_kode" class="col-lg-3 col-form-label">Kode Promosi</label>
                                            <div class="col-lg-9 d-flex">
                                                <input type="text" id="promosi_kode" class="form-control me-2"
                                                    name="promosi_kode" placeholder="Masukkan Kode Promosi">
                                                <button type="button" id="apply_promo_btn"
                                                    class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama Promosi -->
                                <div class="col-md-6" id="promosi-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_promosi" class="form-label">Nama Promosi</label>
                                        <input type="text" id="nama_promosi" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Diskon (Jika ada) -->
                                <div class="col-md-6" id="discount-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="discount" class="form-label">Diskon</label>
                                        <input type="text" id="discount" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Submit & Reset Buttons -->
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('transaksi.index') }}"
                                        class="btn btn-secondary me-2 rounded-pill">Batal</a>
                                    <button type="submit" class="btn btn-success me-2 rounded-pill">Submit</button>
                                    <button type="reset" class="btn btn-warning rounded-pill">Reset</button>
                                </div>
                            </div>
                        </form>
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
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var numericInputs = document.querySelectorAll('.numeric-only');

            numericInputs.forEach(function(input) {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Hanya menerima angka
                });
            });

            const promosiKodeInput = document.getElementById('promosi_kode');
            const applyPromoBtn = document.getElementById('apply_promo_btn');
            const namaPromosiInput = document.getElementById('nama_promosi');
            const discountInput = document.getElementById('discount');
            const promosiSection = document.getElementById('promosi-section');
            const discountSection = document.getElementById('discount-section');
            const statusSelect = document.getElementById('status');
            const downpaymentSection = document.getElementById('downpayment-section');
            const remainingPaymentSection = document.getElementById('remaining-payment-section');
            const downpaymentAmountInput = document.getElementById('downpayment_amount');
            const remainingPaymentInput = document.getElementById('remaining_payment');
            const totalHargaInput = document.getElementById('total_harga');

            let totalHarga = 0;

            applyPromoBtn.addEventListener('click', function() {
                const kodePromosi = promosiKodeInput.value;

                if (kodePromosi) {
                    // Kirim AJAX request untuk validasi kode promo
                    fetch(`/dashboard/validate-promosi?kode=${kodePromosi}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', // Penting untuk mengindikasikan request AJAX
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Tampilkan SweetAlert jika kode promosi valid
                                Swal.fire({
                                    title: 'Kode Promosi Valid!',
                                    text: `Nama Promosi: ${data.nama_promosi}\nDiskon: ${(data.discount * 100)}%`,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                // Tampilkan nama promosi dan diskon yang diambil dari server
                                namaPromosiInput.value = data.nama_promosi;
                                discountInput.value = (data.discount * 100) + '%';

                                // Tampilkan section nama promosi dan diskon
                                promosiSection.style.display = 'block';
                                discountSection.style.display = 'block';

                                // Recalculate total with discount
                                calculateTotalWithDiscount(data.discount);

                            } else {
                                // Tampilkan SweetAlert jika kode promosi tidak valid
                                Swal.fire({
                                    title: 'Kode Promosi Tidak Valid',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Coba Lagi'
                                });

                                // Jika kode tidak valid, sembunyikan bagian promosi
                                promosiSection.style.display = 'none';
                                discountSection.style.display = 'none';
                                calculateTotalWithDiscount(0); // Tanpa diskon

                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Tampilkan SweetAlert jika ada error pada request
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                } else {
                    // Jika input kosong, sembunyikan section promosi
                    promosiSection.style.display = 'none';
                    discountSection.style.display = 'none';
                    calculateTotalWithDiscount(0); // Tanpa diskon
                    // Tampilkan SweetAlert jika input kosong
                    Swal.fire({
                        title: 'Kode Promosi Kosong',
                        text: 'Silakan masukkan kode promosi terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });

            function calculateTotalWithDiscount(discount) {
                let totalHarga = 0;

                // Hitung harga kategori yang dipilih
                document.querySelectorAll('.category-checkbox:checked').forEach(function(category) {
                    const qtyInput = document.querySelector(`input[data-id="${category.value}"]`);
                    const qty = parseInt(qtyInput.value) || 1;
                    totalHarga += parseFloat(category.getAttribute('data-price')) * qty;
                });

                // Hitung harga layanan tambahan
                document.querySelectorAll('.plus-service-checkbox:checked').forEach(function(service) {
                    totalHarga += parseFloat(service.getAttribute('data-price'));
                });

                // Terapkan diskon
                totalHarga = totalHarga - (totalHarga * discount);

                // Tampilkan hasil ke input total harga
                totalHargaInput.value = totalHarga; // Tampilkan dua angka desimal

                // Jika status pembayaran adalah downpayment, hitung sisa pembayaran
                if (statusSelect.value === 'downpayment') {
                    const downpayment = parseFloat(downpaymentAmountInput.value || 0);
                    remainingPaymentInput.value = formatPrice(totalHarga - downpayment);
                }
            }

            function calculateTotal() {
                let totalHarga = 0;

                // Hitung harga kategori
                document.querySelectorAll('.category-checkbox:checked').forEach(function(category) {
                    const qtyInput = document.querySelector(`input[data-id="${category.value}"]`);
                    const qty = parseInt(qtyInput.value) || 1; // Jika qty kosong, default ke 1
                    totalHarga += parseFloat(category.getAttribute('data-price')) * qty;
                });

                // Hitung harga layanan tambahan
                document.querySelectorAll('.plus-service-checkbox:checked').forEach(function(service) {
                    totalHarga += parseFloat(service.getAttribute('data-price'));
                });

                // Terapkan diskon jika ada
                const discount = parseFloat(discountInput.value.replace('%', '')) / 100 || 0;
                totalHarga = totalHarga - (totalHarga * discount);

                totalHargaInput.value = totalHarga;

                // Hitung remaining payment jika downpayment
                if (statusSelect.value === 'downpayment') {
                    const downpayment = parseFloat(downpaymentAmountInput.value || 0);
                    remainingPaymentInput.value = formatPrice(totalHarga - downpayment);
                }
            }


            function formatPrice(value) {
                // Jika harga bulat, tampilkan tanpa desimal
                return (value % 1 === 0) ? value : value.toFixed(2);
            }

            function toggleDownpaymentSection() {
                if (statusSelect.value === 'downpayment') {
                    downpaymentSection.style.display = 'block';
                    remainingPaymentSection.style.display = 'block';
                } else {
                    downpaymentSection.style.display = 'none';
                    remainingPaymentSection.style.display = 'none';
                }
            }

            // Event listener untuk kategori checkbox dan qty
            document.querySelectorAll('.category-checkbox').forEach(function(checkbox) {
                const qtyInput = document.querySelector(`input[data-id="${checkbox.value}"]`);

                // Awalnya, qty input harus dinonaktifkan
                qtyInput.disabled = !checkbox.checked;

                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        qtyInput.value = 1; // Set qty ke 1 ketika dicentang
                        qtyInput.disabled = false; // Pastikan qty bisa diubah setelah dicentang
                    } else {
                        qtyInput.value = ''; // Kosongkan qty ketika tidak dicentang
                        qtyInput.disabled =
                            true; // Nonaktifkan input qty jika checkbox tidak dicentang
                    }
                    calculateTotal(); // Hitung total setelah perubahan
                });
            });

            // Event listener untuk perubahan qty
            document.querySelectorAll('.category-qty').forEach(function(qtyInput) {
                qtyInput.addEventListener('input', calculateTotal);
            });

            // Event listener untuk layanan tambahan
            document.querySelectorAll('.plus-service-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', calculateTotal);
            });

            // Hitung ulang remaining payment saat downpayment amount berubah
            downpaymentAmountInput.addEventListener('input', calculateTotal);

            // Tampilkan atau sembunyikan input DP dan sisa pembayaran berdasarkan status
            statusSelect.addEventListener('change', function() {
                toggleDownpaymentSection();
                calculateTotal();
            });

            // Inisialisasi pada load halaman
            toggleDownpaymentSection();
            calculateTotal();
        });
    </script>
@endsection
