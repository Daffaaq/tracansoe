<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CategoryBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instansiasi Faker
        $faker = Faker::create();
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

        // Definisikan kategori terkait shoe cleaning
        $categories = [
            ['name_category_blog' => 'Cleaning Tips', 'slug' => Str::slug('Cleaning Tips')],
            ['name_category_blog' => 'Product Reviews', 'slug' => Str::slug('Product Reviews')],
            ['name_category_blog' => 'Service Updates', 'slug' => Str::slug('Service Updates')],
            ['name_category_blog' => 'Customer Stories', 'slug' => Str::slug('Customer Stories')],
            ['name_category_blog' => 'Maintenance Guides', 'slug' => Str::slug('Maintenance Guides')],
        ];

        // Masukkan kategori ke dalam database
        foreach ($categories as $category) {
            DB::table('category_blogs')->insert([
                'uuid' => (string) Str::uuid(),
                'name_category_blog' => $category['name_category_blog'],
                'slug' => $category['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mengambil semua category_blog_id dari database setelah insert
        $categoryBlogs = DB::table('category_blogs')->get();

        // Mengambil user_id dari user dengan role 'karyawan'
        $karyawanUserId = DB::table('users')->where('role', 'karyawan')->value('id');

        // Data blog untuk setiap kategori dengan deskripsi lebih panjang
        $blogs = [
            'Cleaning Tips' => [
                'title' => 'Bagaimana caranya membersihkan sepatu canvas',
                'slug' => 'bagaimana-caranya-membersihkan-sepatu-canvas',
                'content' => 'Temukan cara mudah membersihkan sepatu canvas Anda.',
                'description' => 'Membersihkan sepatu canvas adalah salah satu tantangan tersendiri karena materialnya yang mudah rusak '
                    . 'jika tidak ditangani dengan hati-hati. Dalam panduan ini, Anda akan menemukan cara terbaik untuk '
                    . 'membersihkan sepatu canvas Anda tanpa merusak kain atau warna sepatu tersebut. '
                    . 'Langkah-langkah ini mencakup persiapan bahan yang aman, penggunaan alat-alat yang tepat, serta tips '
                    . 'tentang cara menjaga sepatu tetap bersih dan segar lebih lama. Kami juga akan membahas beberapa produk '
                    . 'pembersih yang direkomendasikan untuk sepatu berbahan canvas yang telah teruji dan aman digunakan.',
            ],
            'Product Reviews' => [
                'title' => 'Sepatu Lokal Branded Terbaru Airwalk',
                'slug' => 'sepatu-lokal-branded-terbaru-airwalk',
                'content' => 'Sepatu Airwalk terbaru, stylish dan nyaman.',
                'description' => 'Sepatu Airwalk telah menjadi salah satu pilihan favorit di kalangan pecinta sepatu lokal. '
                    . 'Dengan desain yang stylish dan bahan yang berkualitas, Airwalk kembali meluncurkan seri terbaru yang '
                    . 'mendapat banyak perhatian. Seri terbaru ini tidak hanya unggul dari segi tampilan, tetapi juga dari segi '
                    . 'kenyamanan. Dalam ulasan ini, kami akan membahas lebih lanjut mengenai teknologi yang digunakan dalam pembuatan sepatu, '
                    . 'kenyamanan saat dipakai, serta daya tahan sepatu ini setelah penggunaan sehari-hari. '
                    . 'Banyak pengguna yang sudah mencoba sepatu ini menyatakan puas dengan kualitasnya.',
            ],
            'Service Updates' => [
                'title' => 'Update Layanan Pembersihan Sepatu di Kota Anda',
                'slug' => 'update-layanan-pembersihan-sepatu-di-kota-anda',
                'content' => 'Layanan pembersihan sepatu kini hadir di kota Anda.',
                'description' => 'Kami terus berupaya memperluas jangkauan layanan kami agar lebih dekat dengan pelanggan setia. '
                    . 'Kini, layanan pembersihan sepatu kami telah tersedia di lebih banyak kota besar di Indonesia. Dalam pembaruan layanan kali ini, '
                    . 'kami juga menambahkan beberapa fitur baru, seperti layanan antar jemput sepatu gratis dan diskon khusus bagi pelanggan pertama kali. '
                    . 'Kami juga memperkenalkan beberapa produk pembersih baru yang lebih ramah lingkungan tanpa mengurangi kualitas pembersihan. '
                    . 'Di artikel ini, Anda akan menemukan kota-kota mana saja yang telah kami layani, serta informasi lebih lanjut tentang promo yang sedang berlangsung.',
            ],
            'Customer Stories' => [
                'title' => 'Pengalaman Pelanggan Setia Kami',
                'slug' => 'pengalaman-pelanggan-setia-kami',
                'content' => 'Cerita inspiratif dari pelanggan kami.',
                'description' => 'Di sini, kami berbagi pengalaman luar biasa dari pelanggan-pelanggan setia kami yang telah menggunakan layanan kami selama bertahun-tahun. '
                    . 'Beberapa dari mereka telah mempercayakan sepatu kesayangan mereka kepada kami sejak layanan kami pertama kali dibuka. '
                    . 'Dalam cerita-cerita ini, mereka berbagi bagaimana kami telah membantu menjaga sepatu mereka tetap dalam kondisi prima. '
                    . 'Mereka juga membagikan tips dan pengalaman pribadi mereka mengenai cara terbaik menjaga sepatu dan barang-barang kesayangan lainnya. '
                    . 'Cerita-cerita ini penuh inspirasi dan bisa memberikan insight baru untuk Anda yang ingin merawat sepatu dengan lebih baik.',
            ],
            'Maintenance Guides' => [
                'title' => 'Cara Merawat Sepatu Kulit Agar Tahan Lama',
                'slug' => 'cara-merawat-sepatu-kulit-agar-tahan-lama',
                'content' => 'Tips penting merawat sepatu kulit Anda.',
                'description' => 'Sepatu kulit merupakan salah satu jenis sepatu yang paling elegan namun membutuhkan perawatan khusus agar tetap awet. '
                    . 'Dalam panduan ini, kami akan menjelaskan langkah-langkah detail tentang cara membersihkan sepatu kulit tanpa merusak teksturnya, '
                    . 'produk-produk perawatan apa saja yang bisa Anda gunakan, dan cara menyimpan sepatu kulit agar terhindar dari kelembaban yang bisa '
                    . 'merusak kulit. Panduan ini juga mencakup tips untuk menghilangkan noda dan menjaga kilap alami sepatu kulit agar tetap terlihat baru meskipun sudah lama digunakan.',
            ],
        ];

        // Loop setiap kategori dan insert data blog sesuai dengan kategori
        foreach ($categoryBlogs as $category) {
            // Ambil data blog yang sesuai dengan nama kategori
            $blogData = $blogs[$category->name_category_blog];

            DB::table('blogs')->insert([
                'uuid' => (string) Str::uuid(),
                'title' => $blogData['title'], // Judul sesuai kategori
                'slug' => $blogData['slug'],   // Slug sesuai judul
                'content' => $blogData['content'], // Konten manual yang singkat dan menarik
                'image_url' => $faker->imageUrl(width: 640, height:480), // Gambar dari Faker Picsum
                'description' => $blogData['description'], // Deskripsi manual panjang sesuai kategori
                'status_publish' => 'published', // Semua blog dipublikasikan
                'date_publish' => now()->format('Y-m-d'), // Tanggal sekarang
                'time_publish' => now()->format('H:i:s'), // Waktu sekarang
                'user_id' => $karyawanUserId, // Menggunakan user_id dari karyawan
                'category_blog_id' => $category->id, // ID kategori yang sesuai
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
