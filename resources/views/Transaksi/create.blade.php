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

    .subcategory-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem;
    }

    .subcategory-item {
        flex: 1 1 300px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .subcategory-item .price {
        font-weight: bold;
        color: #007bff;
        margin-right: 10px;
    }

    .subcategory-item .form-check-input {
        margin-right: 5px;
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

                                @foreach ($kategori_sepatu as $sepatu)
                                    <!-- Checkbox for Main Category (Sepatu) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input sepatu-checkbox" type="checkbox"
                                                    id="sepatu_checkbox_{{ $sepatu->id }}"
                                                    name="category_sepatu[{{ $sepatu->id }}][id]"
                                                    data-sepatu-id="{{ $sepatu->id }}">
                                                <label class="form-check-label" for="sepatu_checkbox_{{ $sepatu->id }}">
                                                    {{ $sepatu->category_sepatu }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sub-category Section: Display all sub-categories when main category is selected -->
                                    <div class="col-md-12 subkategori-sepatu-section" id="subkategori_{{ $sepatu->id }}"
                                        style="display: none; background-color: white; padding: 10px; border-radius: 5px;">
                                        <h5>Pilih Sub-kategori untuk {{ $sepatu->category_sepatu }}</h5>
                                        <div class="border p-3 rounded">
                                            @foreach ($categories as $category)
                                                <!-- Kategori Induk -->
                                                @if ($category->parent_id === null)
                                                    <div class="service-category">
                                                        <h3>{{ $category->nama_kategori }}</h3>
                                                        <div class="subcategory-container">
                                                            @foreach ($category->subKriteria as $subcategory)
                                                                <div class="subcategory-item d-flex align-items-center">
                                                                    <div>
                                                                        <input type="checkbox"
                                                                            name="category_hargas[{{ $subcategory->id }}][id]"
                                                                            value="{{ $subcategory->id }}"
                                                                            class="form-check-input me-2"
                                                                            data-price="{{ $subcategory->price }}">
                                                                        <strong>{{ $subcategory->nama_kategori }}</strong>
                                                                    </div>
                                                                    <span class="price">Rp
                                                                        {{ number_format($subcategory->price, 0, ',', '.') }}</span>
                                                                    <div>
                                                                        <input type="number"
                                                                            name="category_hargas[{{ $subcategory->id }}][qty]"
                                                                            class="form-control w-100" placeholder="Qty"
                                                                            style="max-width: 80px;">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Plus Services Section -->
                                    <div class="col-md-12 plus-services-section" id="plus_services_{{ $sepatu->id }}"
                                        style="display: none; background-color: white; padding: 10px; border-radius: 5px;">
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
                                                            for="plus_service_{{ $service->id }}">
                                                            {{ $service->name }} -
                                                            Rp{{ number_format($service->price, 0, ',', '.') }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


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
            // Initialize elements and functions
            const numericInputs = document.querySelectorAll('.numeric-only');
            const confirmDpBtn = document.getElementById('confirm_dp_btn');
            const editDpBtn = document.getElementById('edit_dp_btn');
            const applyPromoBtn = document.getElementById('apply_promo_btn');
            const validateMembershipBtn = document.getElementById('validate_membership_btn');
            const statusSelect = document.getElementById('status');
            const downpaymentSection = document.getElementById('downpayment-section');
            const remainingPaymentSection = document.getElementById('remaining-payment-section');
            const downpaymentAmountInput = document.getElementById('downpayment_amount');
            const remainingPaymentInput = document.getElementById('remaining_payment');
            const totalHargaInput = document.getElementById('total_harga');
            const discountMembershipInput = document.getElementById('discountMembership');
            const discountInput = document.getElementById('discount');
            const sepatuCheckboxes = document.querySelectorAll('.sepatu-checkbox');
            let totalHarga = 0;

            // Reset Form
            function resetForm() {
                document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"]').forEach(
                    input => {
                        input.value = '';
                        input.disabled = true;
                    });
                document.querySelectorAll('.form-check-input').forEach(checkbox => checkbox.checked = false);
                document.getElementById('status').selectedIndex = 0;
                document.getElementById('promosi-section').style.display = 'none';
                document.getElementById('discount-section').style.display = 'none';
                document.getElementById('memberships-section').style.display = 'none';
                document.getElementById('discountMemberships-section').style.display = 'none';
                document.getElementById('total_harga').value = '';
                toggleDownpaymentSection();
            }

            // Numeric Input Restriction
            numericInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // Fungsi untuk menghitung total harga
            function calculateTotal() {
                let totalHarga = 0;

                document.querySelectorAll('.subcategory-item .form-check-input:checked').forEach(subCheckbox => {
                    const qtyInput = subCheckbox.closest('.subcategory-item').querySelector(
                        'input[type="number"]');
                    const qty = parseInt(qtyInput.value) || 1;
                    const price = parseFloat(subCheckbox.getAttribute('data-price')) || 0;
                    totalHarga += price * qty;

                    console.log(`Qty: ${qty}, Price: ${price}, Total: ${totalHarga}`);
                });

                document.querySelectorAll('.plus-service-checkbox:checked').forEach(service => {
                    totalHarga += parseFloat(service.getAttribute('data-price')) || 0;
                });

                const membershipDiscount = parseFloat(discountMembershipInput.value || 0) / 100;
                if (membershipDiscount > 0) totalHarga -= totalHarga * membershipDiscount;

                const promoDiscount = parseFloat(discountInput.value || 0) / 100;
                if (promoDiscount > 0) totalHarga -= totalHarga * promoDiscount;

                totalHargaInput.value = formatPrice(totalHarga);

                if (statusSelect.value === 'downpayment') {
                    remainingPaymentInput.value = formatPrice(totalHarga - parseFloat(downpaymentAmountInput
                        .value || 0));
                }
            }

            function formatPrice(value) {
                return value.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
            }

            // Toggle Downpayment Section
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

            sepatuCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const sepatuId = checkbox.getAttribute('data-sepatu-id');
                    const subKategoriSection = document.getElementById(`subkategori_${sepatuId}`);
                    const plusServiceCheckbox = document.getElementById(`plus_services_${sepatuId}`);

                    if (checkbox.checked) {
                        subKategoriSection.style.display = 'block';
                        plusServiceCheckbox.style.display = 'block';

                        subKategoriSection.querySelectorAll('.form-check-input').forEach(
                            subCheckbox => {
                                subCheckbox.disabled = false;

                                subCheckbox.addEventListener('change', function() {
                                    const qtyInput = subCheckbox.closest(
                                        '.subcategory-item').querySelector(
                                        'input[type="number"]');
                                    if (subCheckbox.checked) {
                                        qtyInput.value = qtyInput.value || 1;
                                        qtyInput.disabled = false;
                                    } else {
                                        qtyInput.value = '';
                                        qtyInput.disabled = true;
                                    }
                                    calculateTotal();
                                });
                            });
                    } else {
                        subKategoriSection.style.display = 'none';
                        plusServiceCheckbox.style.display = 'none';

                        subKategoriSection.querySelectorAll('.form-check-input').forEach(
                            subCheckbox => {
                                subCheckbox.checked = false;
                                subCheckbox.disabled = true;
                            });

                        subKategoriSection.querySelectorAll('input[type="number"]').forEach(
                            qtyInput => {
                                qtyInput.value = '';
                                qtyInput.disabled = true;
                            });
                        calculateTotal();
                    }
                });
            });

            // Event listeners
            statusSelect.addEventListener('change', () => {
                toggleDownpaymentSection();
                calculateTotal();
            });
            downpaymentAmountInput.addEventListener('input', calculateTotal);
            document.querySelectorAll('.plus-service-checkbox').forEach(checkbox => checkbox.addEventListener(
                'change', calculateTotal));

            // Tambahkan event listener pada input qty
            document.querySelectorAll('.subcategory-item input[type="number"]').forEach(qtyInput => {
                qtyInput.addEventListener('input', calculateTotal);
            });

            // Apply Promo
            applyPromoBtn.addEventListener('click', function() {
                const kodePromosi = document.getElementById('promosi_kode').value;
                if (!kodePromosi) {
                    Swal.fire({
                        title: 'Kode Promosi Kosong',
                        text: 'Silakan masukkan kode promosi terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                fetch(`/dashboard/validate-promosi?kode=${kodePromosi}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Kode Promosi Valid!',
                                text: `Nama Promosi: ${data.nama_promosi}\nDiskon: ${(data.discount * 100)}%`,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            document.getElementById('promosi-section').style.display = 'block';
                            document.getElementById('discount-section').style.display = 'block';
                            document.getElementById('nama_promosi').value = data.nama_promosi;
                            discountInput.value = (data.discount * 100) + '%';
                            calculateTotal(data.discount);
                        } else {
                            Swal.fire({
                                title: 'Kode Promosi Tidak Valid',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Coba Lagi'
                            });
                            document.getElementById('promosi-section').style.display = 'none';
                            document.getElementById('discount-section').style.display = 'none';
                            calculateTotal(0);
                        }
                    })
                    .catch(() => Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan dalam validasi promosi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }));
            });

            // Validate Membership
            validateMembershipBtn.addEventListener('click', function() {
                const kodeMembership = document.getElementById('membership_kode').value;
                if (!kodeMembership) {
                    Swal.fire({
                        title: 'Kode Membership Kosong',
                        text: 'Silakan masukkan kode membership terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                fetch(`/dashboard/validate-membership?kode=${kodeMembership}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
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
                            document.getElementById('notelp_customer').value = data.phone_membership;
                            document.getElementById('alamat_customer').value = data.alamat_membership;
                            document.getElementById('memberships-section').style.display = 'block';
                            document.getElementById('discountMemberships-section').style.display =
                                'block';
                            document.getElementById('kelas_membership').value = data.kelas_membership;
                            discountMembershipInput.value = (data.discount * 100) + '%';
                            calculateTotal(data.discount);
                        } else {
                            Swal.fire({
                                title: 'Kode Membership Tidak Valid',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'Coba Lagi'
                            });
                            document.getElementById('memberships-section').style.display = 'none';
                            document.getElementById('discountMemberships-section').style.display =
                                'none';
                        }
                    })
                    .catch(() => Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan dalam validasi membership.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }));
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
            // Initialize
            toggleDownpaymentSection();
            calculateTotal();
        });
    </script>
@endsection
