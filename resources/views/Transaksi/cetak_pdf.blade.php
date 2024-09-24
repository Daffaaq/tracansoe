<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            /* Mengurangi jarak bawah header */
        }

        .header h1 {
            margin: 5px 0;
            /* Mengurangi jarak di atas dan bawah heading */
            font-size: 18px;
            /* Ukuran font lebih kecil */
        }

        .header p {
            margin: 5px 0;
            /* Mengurangi jarak antara paragraf di header */
            font-size: 12px;
            /* Mengurangi ukuran font */
        }

        .details,
        .category,
        .services {
            margin-bottom: 10px;
            /* Mengurangi margin bawah setiap bagian */
        }

        .details table,
        .category table,
        .services table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            /* Mengurangi jarak atas tabel */
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            /* Mengurangi padding */
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            /* Mengurangi margin atas footer */
        }

        .footer p {
            font-size: 10px;
            /* Ukuran font lebih kecil untuk footer */
            margin: 2px 0;
            /* Mengurangi jarak antar paragraf */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Nota Transaksi</h1>
            <p>Transaksi #{{ $transaksi->tracking_number }}</p>
            <p>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
        </div>

        <div class="details">
            <h3>Informasi Customer</h3>
            <table>
                <tr>
                    <th>Nama Customer</th>
                    <td>{{ $transaksi->nama_customer }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $transaksi->email_customer }}</td>
                </tr>
                <tr>
                    <th>Nomor Telepon</th>
                    <td>{{ $transaksi->notelp_customer }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $transaksi->alamat_customer }}</td>
                </tr>
            </table>
        </div>

        <!-- Informasi Promosi -->
        @if ($transaksi->promosi)
            <div class="details">
                <h3>Informasi Promosi</h3>
                <table>
                    <tr>
                        <th>Kode Promosi</th>
                        <td>{{ $transaksi->promosi->kode }}</td>
                    </tr>
                    <tr>
                        <th>Diskon</th>
                        <td>{{ $transaksi->promosi->discount * 100 }}%</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $transaksi->promosi->description }}</td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="category">
            <h3 style="text-align: center; font-family: Arial, sans-serif;">Kategori Harga yang Dipilih</h3>
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr>
                        <th style="padding: 10px; background-color: #4CAF50; color: white; text-align: left;">Nama
                            Kategori</th>
                        <th style="padding: 10px; background-color: #4CAF50; color: white; text-align: center;">Jumlah
                        </th>
                        <th style="padding: 10px; background-color: #4CAF50; color: white; text-align: right;">Harga
                        </th>
                        <th style="padding: 10px; background-color: #4CAF50; color: white; text-align: right;">Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalKategoriHarga = 0;
                    @endphp

                    <!-- Loop untuk kategori induk -->
                    @foreach ($categories->where('parent_id', null) as $categoryInduk)
                        @php
                            // Ambil subkategori yang dipilih dalam transaksi berdasarkan kategori induk
                            $selectedSubCategories = $transaksi->categoryHargas->where('parent_id', $categoryInduk->id);
                        @endphp

                        <!-- Hanya tampilkan kategori induk jika ada subkategori yang dipilih -->
                        @if ($selectedSubCategories->isNotEmpty())
                            <!-- Tampilkan kategori induk -->
                            <tr>
                                <td colspan="4"
                                    style="padding: 10px; background-color: #f0f0f0; font-weight: bold; text-align: center; vertical-align: middle;">
                                    {{ $categoryInduk->nama_kategori }}
                                </td>
                            </tr>

                            <!-- Loop untuk sub-kategori yang terkait dengan kategori induk -->
                            @foreach ($selectedSubCategories as $subCategory)
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px;">{{ $subCategory->nama_kategori }}</td>
                                    <td style="padding: 10px; text-align: center;">{{ $subCategory->pivot->qty }}</td>
                                    <td style="padding: 10px; text-align: right;">
                                        Rp{{ number_format($subCategory->price, 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 10px; text-align: right;">
                                        Rp{{ number_format($subCategory->pivot->qty * $subCategory->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @php
                                    $totalKategoriHarga += $subCategory->pivot->qty * $subCategory->price;
                                @endphp
                            @endforeach
                        @endif
                    @endforeach

                    <!-- Total Kategori Harga -->
                    @if ($totalKategoriHarga > 0)
                        <tr style="background-color: #ffeb3b; font-weight: bold;">
                            <td colspan="3" style="padding: 10px;">Total Kategori Harga</td>
                            <td style="padding: 10px; text-align: right;">
                                Rp{{ number_format($totalKategoriHarga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>


        <div class="services">
            <h3>Layanan Tambahan</h3>
            @if ($transaksi->plusServices->isEmpty())
                <p>Tidak ada layanan tambahan yang dipilih.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="background-color: #4CAF50; color: white">Layanan</th>
                            <th style="background-color: #4CAF50; color: white">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPlusServices = 0;
                        @endphp
                        @foreach ($transaksi->plusServices as $plusService)
                            <tr>
                                <td>{{ $plusService->name }}</td>
                                <td>Rp{{ number_format($plusService->price, 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $totalPlusServices += $plusService->price;
                            @endphp
                        @endforeach
                        <tr>
                            <th colspan="1">Total Layanan Tambahan</th>
                            <td>Rp{{ number_format($totalPlusServices, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Total Keseluruhan -->
        <div class="total">
            <h3>Total Keseluruhan</h3>
            <table>
                <tr>
                    <th>Total Kategori Harga</th>
                    <td>Rp{{ number_format($totalKategoriHarga, 0, ',', '.') }}</td>
                </tr>
                @if ($transaksi->plusServices->isNotEmpty())
                    <tr>
                        <th>Total Layanan Tambahan</th>
                        <td>Rp{{ number_format($totalPlusServices, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @php
                    if ($transaksi->plusServices->isEmpty()) {
                        $totalKeseluruhan = $totalKategoriHarga;
                    } else {
                        $totalKeseluruhan = $totalKategoriHarga + $totalPlusServices;
                    }
                @endphp
                <tr>
                    <th>Sub Total</th>
                    <td>Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                </tr>

                @php
                    if ($transaksi->plusServices->isEmpty()) {
                        $totalKeseluruhan = $totalKategoriHarga;
                    } else {
                        $totalKeseluruhan = $totalKategoriHarga + $totalPlusServices;
                    }
                    $diskon = 0;
                    if ($transaksi->promosi) {
                        $diskon = $transaksi->promosi->discount * $totalKeseluruhan;
                    }
                @endphp

                @if ($diskon > 0)
                    <tr>
                        <th>Diskon</th>
                        <td>-Rp{{ number_format($diskon, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Setelah Diskon</th>
                        <td>Rp{{ number_format($totalKeseluruhan - $diskon, 0, ',', '.') }}</td>
                    </tr>
                @endif

                @if ($transaksi->status == 'downpayment')
                    <tr>
                        <th>Downpayment</th>
                        <td>Rp{{ number_format($transaksi->downpayment_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Pembayaran</th>
                        <td>Rp{{ number_format($transaksi->remaining_payment, 0, ',', '.') }}</td>
                    </tr>
                @endif
                {{-- <tr>
                    <th>Grand Total</th>
                    <td>Rp{{ number_format($totalKeseluruhan - $diskon, 0, ',', '.') }}</td>
                </tr> --}}
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas transaksi Anda!</p>
            <p>Silakan hubungi kami jika Anda membutuhkan bantuan lebih lanjut.</p>
        </div>
    </div>
</body>

</html>
