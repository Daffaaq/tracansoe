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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Form Transaksi -->
                        <form method="POST" action="{{ route('transaksi.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Input untuk Kode Membership -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="membership_kode" class="form-label">Kode Membership</label>
                                        <div class="input-group">
                                            <input type="text" id="membership_kode" class="form-control"
                                                name="membership_kode" placeholder="Masukkan Kode Membership">
                                            <button type="button" id="validate_membership_btn"
                                                class="btn btn-primary ms-2">Validasi</button>
                                        </div>
                                        <small class="text-muted">Masukkan kode membership untuk validasi dan mendapatkan
                                            diskon.</small>
                                    </div>
                                </div>
                                <!-- Nama Customer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_customer" class="form-label">Nama Customer</label>
                                        <input type="text" id="nama_customer" class="form-control" name="nama_customer"
                                            placeholder="Nama Customer">
                                    </div>
                                </div>

                                <!-- Email Customer -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_customer" class="form-label">Email</label>
                                        <input type="email" id="email_customer" class="form-control" name="email_customer"
                                            placeholder="Email Customer">
                                    </div>
                                </div>

                                <!-- No Telepon -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notelp_customer" class="form-label">No. Telepon</label>
                                        <input type="text" id="notelp_customer" class="form-control"
                                            name="notelp_customer" placeholder="No. Telepon Customer">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat_customer" class="form-label">Alamat</label>
                                        <input type="text" id="alamat_customer" class="form-control"
                                            name="alamat_customer" placeholder="Alamat Customer">
                                    </div>
                                </div>

                                <!-- Status Pembayaran -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status Pembayaran</label>
                                        <select id="status" class="form-select" name="status">
                                            <option value="">Pilih
                                            </option>
                                            <option value="downpayment">Downpayment</option>
                                            <option value="paid">Paid
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Downpayment Amount -->
                                <div class="col-md-6" id="downpayment-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="downpayment_amount" class="form-label">Jumlah DP</label>
                                        <div class="input-group">
                                            <input type="text" id="downpayment_amount" class="form-control numeric-only"
                                                name="downpayment_amount" placeholder="Jumlah DP" inputmode="numeric"
                                                style="max-width: 300px;">
                                            <button type="button" id="confirm_dp_btn" class="btn btn-primary btn-sm ms-2"
                                                style="display: none;">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" id="edit_dp_btn" class="btn btn-secondary btn-sm ms-2"
                                                style="display: none;">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Jumlah Downpayment harus 40% dari total harga</small>
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
                                @foreach ($kategori_sepatu as $sepatu)
    <!-- Checkbox untuk Kategori Sepatu -->
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input sepatu-checkbox" type="checkbox" 
                       id="sepatu_checkbox_{{ $sepatu->id }}" 
                       data-sepatu-id="{{ $sepatu->id }}">
                <label class="form-check-label" for="sepatu_checkbox_{{ $sepatu->id }}">
                    {{ $sepatu->category_sepatu }}
                </label>
            </div>
        </div>
    </div>

    <!-- Bagian untuk Sub-kategori (Categories) yang Akan Muncul -->
    <div class="col-md-12 subkategori-sepatu-section" id="subkategori_{{ $sepatu->id }}" 
         style="display: none; background-color: white; padding: 10px; border-radius: 5px;">
        <h5>Pilih Sub-kategori untuk {{ $sepatu->nama_kategori }}</h5>
        <div class="border p-3 rounded">
            @foreach ($categories as $subCategory)
                @if ($subCategory->parent_id == $sepatu->id)
                    <div class="form-check d-flex align-items-center mb-2">
                        <input class="form-check-input me-2 category-checkbox" type="checkbox" 
                               name="category_hargas[{{ $subCategory->id }}][id]" 
                               value="{{ $subCategory->id }}" 
                               data-sepatu-id="{{ $sepatu->id }}"
                               id="category_{{ $subCategory->id }}">
                        <label class="form-check-label me-auto" for="category_{{ $subCategory->id }}">
                            {{ $subCategory->nama_kategori }} - Rp{{ $subCategory->price }}
                        </label>
                        <input type="number" name="category_hargas[{{ $subCategory->id }}][qty]" 
                               class="form-control w-25" placeholder="Qty" 
                               style="max-width: 80px;">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endforeach

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

                                <!-- Diskon Membership -->
                                <div class="col-md-6" id="memberships-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="kelas_membership" class="form-label">Kelas Membership</label>
                                        <input type="text" id="kelas_membership" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6" id="discountMemberships-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="discountMembership" class="form-label">Diskon Membership</label>
                                        <input type="text" id="discountMembership" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- Diskon Promosi -->
                                <div class="col-md-6" id="promosi-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_promosi" class="form-label">Nama Promosi</label>
                                        <input type="text" id="nama_promosi" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6" id="discount-section" style="display: none;">
                                    <div class="form-group">
                                        <label for="discount" class="form-label">Diskon Promosi</label>
                                        <input type="text" id="discount" class="form-control" readonly>
                                    </div>
                                </div>


                                <!-- Submit & Reset Buttons -->
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('transaksi.index') }}"
                                        class="btn btn-secondary me-2 rounded-pill">Batal</a>
                                    <button type="submit" class="btn btn-success me-2 rounded-pill">Submit</button>
                                    <button type="reset" class="btn btn-warning rounded-pill"
                                        onclick="resetForm()">Reset</button>
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
            const sepatuCheckboxes = document.querySelectorAll('.sepatu-checkbox');

    sepatuCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const sepatuId = checkbox.getAttribute('data-sepatu-id');
            const subKategoriSection = document.getElementById(subkategori_${sepatuId});

            if (!subKategoriSection) {
                console.error(Elemen dengan ID subkategori_${sepatuId} tidak ditemukan);
                return;
            }

            // Tampilkan atau sembunyikan sub-kategori berdasarkan checkbox sepatu
            if (checkbox.checked) {
                subKategoriSection.style.display = 'block';
            } else {
                subKategoriSection.style.display = 'none';

                // Jika checkbox utama di-uncheck, uncheck semua sub-kategori di bawahnya
                subKategoriSection.querySelectorAll('.category-checkbox').forEach(subCheckbox => {
                    subCheckbox.checked = false;
                });
            }
        });
    });
            function resetForm() {
                // Bersihkan semua input teks
                document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                document.querySelectorAll('input[type="email"]').forEach(input => input.value = '');
                document.querySelectorAll('input[type="number"]').forEach(input => input.value = '');

                // Reset dropdown
                document.getElementById('status').selectedIndex = 0;

                // Sembunyikan bagian promosi dan membership
                document.getElementById('promosi-section').style.display = 'none';
                document.getElementById('discount-section').style.display = 'none';
                document.getElementById('memberships-section').style.display = 'none';
                document.getElementById('discountMemberships-section').style.display = 'none';

                // Set nilai default harga total
                document.getElementById('total_harga').value = '';

                // Nonaktifkan downpayment section
                toggleDownpaymentSection();

                // Reset checkbox dan jumlah
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                    const qtyInput = document.querySelector(`input[data-id="${checkbox.value}"]`);
                    qtyInput.disabled = true;
                    qtyInput.value = '';
                });

                // Reset layanan tambahan
                document.querySelectorAll('.plus-service-checkbox').forEach(service => {
                    service.checked = false;
                });
            }
            var numericInputs = document.querySelectorAll('.numeric-only');
            var confirmDpBtn = document.getElementById('confirm_dp_btn');
            var editDpBtn = document.getElementById('edit_dp_btn');

            numericInputs.forEach(function(input) {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Hanya menerima angka
                });
            });

            const membershipKodeInput = document.getElementById('membership_kode');
            const validateMembershipBtn = document.getElementById('validate_membership_btn');
            const promosiKodeInput = document.getElementById('promosi_kode');
            const applyPromoBtn = document.getElementById('apply_promo_btn');
            const namaPromosiInput = document.getElementById('nama_promosi');
            const discountInput = document.getElementById('discount');
            const membershipsSection = document.getElementById('memberships-section');
            const discountMembershipsSection = document.getElementById('discountMemberships-section');
            const promosiSection = document.getElementById('promosi-section');
            const discountSection = document.getElementById('discount-section');
            const kelasMembershipInput = document.getElementById('kelas_membership');
            const discountMembershipInput = document.getElementById('discountMembership');
            const statusSelect = document.getElementById('status');
            const downpaymentSection = document.getElementById('downpayment-section');
            const remainingPaymentSection = document.getElementById('remaining-payment-section');
            const downpaymentAmountInput = document.getElementById('downpayment_amount');
            const remainingPaymentInput = document.getElementById('remaining_payment');
            const totalHargaInput = document.getElementById('total_harga');

            let totalHarga = 0;

            // Apply promo button
            applyPromoBtn.addEventListener('click', function() {
                const kodePromosi = promosiKodeInput.value;
                const kodeMembership = membershipKodeInput.value;

                if (kodePromosi) {
                    // Kirim AJAX request untuk validasi kode promo
                    fetch(`/dashboard/validate-promosi?kode=${kodePromosi}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const totalHarga = parseFloat(totalHargaInput.value.replace(
                                    /[^0-9.-]+/g, "")) || 0;

                                // Jika tidak ada kode membership, lakukan pengecekan minimum payment
                                if (!kodeMembership && totalHarga < data.minimum_payment) {
                                    Swal.fire({
                                        title: 'Tidak Memenuhi Syarat!',
                                        text: `Total harga harus minimal Rp${data.minimum_payment} untuk menggunakan kode promosi ini.`,
                                        icon: 'warning',
                                        confirmButtonText: 'OK'
                                    });

                                    return; // Jika tidak memenuhi syarat, hentikan eksekusi
                                }

                                // Tampilkan SweetAlert jika kode promosi valid
                                Swal.fire({
                                    title: 'Kode Promosi Valid!',
                                    text: `Nama Promosi: ${data.nama_promosi}\nDiskon: ${(data.discount * 100)}%`,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                // Tampilkan nama promosi dan diskon yang diambil dari server
                                promosiSection.style.display = 'block';
                                discountSection.style.display = 'block';
                                namaPromosiInput.value = data.nama_promosi;
                                discountInput.value = (data.discount * 100) + '%';

                                // Hitung total dengan diskon
                                calculateTotal(data.discount);

                            } else {
                                Swal.fire({
                                    title: 'Kode Promosi Tidak Valid',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Coba Lagi'
                                });

                                promosiSection.style.display = 'none';
                                discountSection.style.display = 'none';
                                calculateTotal(0); // Tanpa diskon
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan dalam validasi promosi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                } else {
                    Swal.fire({
                        title: 'Kode Promosi Kosong',
                        text: 'Silakan masukkan kode promosi terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });

            // Validate membership button
            validateMembershipBtn.addEventListener('click', function() {
                const kodeMembership = membershipKodeInput.value;

                if (kodeMembership) {
                    // Kirim AJAX request untuk validasi kode membership
                    fetch(`/dashboard/validate-membership?kode=${kodeMembership}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Kode Membership Valid!',
                                    text: `Kelas Membership: ${data.kelas_membership}\nDiskon: ${data.discount * 100}%`,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                document.getElementById('nama_customer').value = data.nama_membership;
                                document.getElementById('email_customer').value = data.email_membership;
                                document.getElementById('notelp_customer').value = data
                                .phone_membership;
                                document.getElementById('alamat_customer').value = data
                                    .alamat_membership;

                                membershipsSection.style.display = 'block';
                                discountMembershipsSection.style.display = 'block';
                                kelasMembershipInput.value = data.kelas_membership;
                                discountMembershipInput.value = (data.discount * 100) + '%';

                                // Terapkan diskon ke total harga
                                calculateTotal(data.discount);

                            } else {
                                Swal.fire({
                                    title: 'Kode Membership Tidak Valid',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'Coba Lagi'
                                });

                                membershipsSection.style.display = 'none';
                                discountMembershipsSection.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan dalam validasi membership.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            membershipsSection.style.display = 'none';
                            discountMembershipsSection.style.display = 'none';
                        });
                } else {
                    Swal.fire({
                        title: 'Kode Membership Kosong',
                        text: 'Silakan masukkan kode membership terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });



            function calculateTotal() {
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

                // Ambil nilai diskon membership dari input form (nilai diambil dari input #discountMembership)
                const membershipDiscount = parseFloat(document.getElementById('discountMembership').value || 0) /
                    100;
                if (membershipDiscount > 0) {
                    totalHarga -= totalHarga * membershipDiscount; // Terapkan diskon membership terlebih dahulu
                }

                // Setelah diskon membership, terapkan diskon promosi pada harga yang sudah didiskon
                const promoDiscount = parseFloat(document.getElementById('discount').value || 0) / 100;
                if (promoDiscount > 0) {
                    totalHarga -= totalHarga * promoDiscount; // Terapkan diskon promosi setelah diskon membership
                }

                // Tampilkan hasil akhir ke input total harga
                totalHargaInput.value = formatPrice(totalHarga);

                console.log(membershipDiscount, promoDiscount, totalHarga);

                // Hitung remaining payment jika downpayment
                if (statusSelect.value === 'downpayment') {
                    const downpayment = parseFloat(downpaymentAmountInput.value || 0);
                    remainingPaymentInput.value = formatPrice(totalHarga - downpayment);
                }
            }


            function formatPrice(value) {
                // Jika harga bulat, tampilkan tanpa desimal
                return (value % 1 === 0) ? value : value;
            }

            function toggleDownpaymentSection() {
                if (statusSelect.value === 'downpayment') {
                    downpaymentSection.style.display = 'block';
                    remainingPaymentSection.style.display = 'block';
                    confirmDpBtn.style.display = 'inline-block';
                } else {
                    downpaymentSection.style.display = 'none';
                    remainingPaymentSection.style.display = 'none';
                    confirmDpBtn.style.display = 'none';
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

            // Fungsi untuk konfirmasi downpayment
            confirmDpBtn.addEventListener('click', function() {
                const downpayment = parseFloat(downpaymentAmountInput.value || 0);
                const totalHarga = parseFloat(totalHargaInput.value || 0);
                const minimalDownpayment = totalHarga * 0.40; // Minimal 40%

                if (downpayment < minimalDownpayment) {
                    Swal.fire({
                        title: 'Error!',
                        text: `Minimal downpayment adalah 40% dari total harga. Minimal DP: Rp${minimalDownpayment}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Konfirmasi Downpayment',
                        text: `Anda yakin ingin mengunci downpayment sebesar Rp${downpayment}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kunci!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kunci input downpayment
                            downpaymentAmountInput.readOnly = true;
                            confirmDpBtn.style.display = 'none'; // Sembunyikan tombol konfirmasi
                            editDpBtn.style.display =
                                'block'; // Tampilkan tombol untuk ubah downpayment
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Downpayment telah dikunci.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });

            // Fungsi untuk mengedit downpayment
            editDpBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Ubah Downpayment',
                    text: 'Apakah Anda yakin ingin mengubah downpayment?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buka kembali input downpayment
                        downpaymentAmountInput.readOnly = false;
                        confirmDpBtn.style.display = 'block'; // Tampilkan kembali tombol konfirmasi
                        editDpBtn.style.display = 'none'; // Sembunyikan tombol ubah
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Sekarang Anda dapat mengubah downpayment.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Inisialisasi pada load halaman
            toggleDownpaymentSection();
            calculateTotal();
        });
    </script>
@endsection
