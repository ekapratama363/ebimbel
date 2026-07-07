<?php

namespace Database\Seeders;

use App\Models\LandingItem;
use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'hero' => [
                'kicker' => 'Bimbingan Minat Belajar Anak (BiMBA)',
                'title' => 'Belajar seru, tumbuh',
                'subtitle' => 'percaya diri',
                'body' => 'adalah lembaga bimbingan belajar anak dengan pendekatan bermain sambil belajar — seperti sekolah BiMBA: hangat, terstruktur, dan dekat dengan orang tua. Kami menemani anak membangun fondasi akademik sekaligus minat belajar sejak dini.',
                'cta_primary_text' => 'Lihat program',
                'cta_primary_link' => '#program',
                'cta_secondary_text' => 'Daftar & konsultasi',
                'cta_secondary_link' => '#daftar',
            ],
            'hero_card' => [
                'title' => 'Where fun meets learning',
                'kicker' => 'Usia & jenjang',
            ],
            'strip' => [
                'body' => '— mitra belajar terpercaya keluarga yang menginginkan pendidikan anak yang hangat dan bermakna.',
                'cta_primary_text' => 'Kenali kami',
                'cta_primary_link' => '#tentang',
            ],
            'about' => [
                'title' => 'Bimbel anak yang dekat dengan keluarga',
                'body' => 'fokus pada bimbingan minat belajar anak — bukan sekadar mengajar materi, tetapi membantu anak menemukan cara belajar yang cocok, menyenangkan, dan berkelanjutan.',
                'body_secondary' => 'Tim pengajar kami terlatih menyampaikan materi dengan metode visual, bermain peran, dan latihan bertahap. Setiap anak dipantau perkembangannya; orang tua mendapat gambaran jelas tentang apa yang dipelajari dan bagaimana mendampingi di rumah.',
            ],
            'about_sidebar' => [
                'title' => 'Visi kami',
                'body' => 'Menjadi tempat belajar favorit anak — di mana fun dan learning berjalan berdampingan, sehingga anak percaya diri hadir di sekolah dan di kehidupan sehari-hari.',
                'subtitle' => 'Nilai yang kami bawa ke kelas',
            ],
            'program_intro' => [
                'kicker' => 'Program belajar',
                'title' => 'Pilih program sesuai usia anak',
                'body' => 'Materi disusun bertahap dari fondasi calistung hingga penguatan mapel sekolah — dengan metode yang disesuaikan gaya belajar anak.',
            ],
            'mengapa_intro' => [
                'title' => 'Lebih dari sekadar les tambahan',
                'body' => 'Kami memahami kebutuhan orang tua modern: anak betah belajar, progress terpantau, dan komunikasi jelas tanpa harus menunggu rapor semester.',
            ],
            'orang_tua' => [
                'kicker' => 'Untuk orang tua',
                'title' => 'Tetap dekat dengan perjalanan belajar anak',
                'body' => 'menyampaikan laporan harian — ringkasan kegiatan, capaian hari itu, saran belajar di rumah, serta foto atau video momen belajar. Orang tua tidak perlu menebak-nebak: apa yang anak pelajari hari ini sudah jelas.',
                'cta_primary_text' => 'Lihat contoh portal wali',
                'cta_primary_link' => '/orang-tua',
            ],
            'ortu_preview' => [
                'kicker' => 'Laporan harian',
                'title' => 'Hari ini anak belajar penjumlahan berpindah dengan permainan kartu angka.',
                'body' => 'Saran di rumah: latihan 5 soal penjumlahan dua digit bersama orang tua.',
                'extra_text' => 'Senin · Matematika kelas 2',
            ],
            'kemitraan' => [
                'kicker' => 'Kemitraan cabang',
                'title' => 'ke kota Anda',
                'body' => 'Ingin membuka cabang atau lembaga BiMBA bermerek di wilayah Anda? Kami menyediakan paket kemitraan untuk pengusaha, pendidik, dan institusi yang percaya pada pendidikan anak yang menyenangkan dan berkualitas.',
                'contact_email' => 'kemitraan@jeniuskids.id',
                'cta_primary_text' => 'kemitraan@jeniuskids.id',
                'cta_primary_link' => 'mailto:kemitraan@jeniuskids.id',
                'body_secondary' => 'Kirim email singkat: nama, kota, latar belakang Anda, dan rencana lokasi cabang. Tim kemitraan akan menghubungi dalam 2–3 hari kerja.',
                'subtitle' => 'Siap diskusi lebih lanjut?',
                'contact_phone' => '+6281234567890',
                'cta_secondary_text' => 'WhatsApp kemitraan',
                'cta_secondary_link' => 'tel:+6281234567890',
            ],
            'cta' => [
                'title' => 'Siap kenalan?',
                'body' => 'Daftarkan anak Anda atau jadwalkan konsultasi gratis untuk memilih program yang paling sesuai. Tim kami dengan senang hati membantu.',
                'contact_email' => 'info@jeniuskids.id',
                'contact_phone' => '+6281234567890',
                'extra_text' => 'Jam operasional: Senin–Sabtu, 08.00–17.00 WIB · Lokasi: hubungi kami untuk alamat cabang terdekat',
            ],
            'footer_tagline' => [
                'extra_text' => 'Where fun meets learning',
            ],
        ];

        foreach ($sections as $key => $data) {
            LandingSection::query()->updateOrCreate(['section_key' => $key], $data);
        }

        $items = [
            ['group' => 'hero_point', 'icon' => 'check2-circle', 'title' => 'Kelas kecil & pendampingan personal', 'sort_order' => 1],
            ['group' => 'hero_point', 'icon' => 'check2-circle', 'title' => 'Kurikulum menyenangkan, sesuai usia anak', 'sort_order' => 2],
            ['group' => 'hero_point', 'icon' => 'check2-circle', 'title' => 'Komunikasi rutin dengan orang tua', 'sort_order' => 3],
            ['group' => 'quick_link', 'icon' => 'emoji-smile', 'title' => 'Playgroup & TK', 'description' => 'Calistung, motorik, sosial-emosional', 'sort_order' => 1],
            ['group' => 'quick_link', 'icon' => 'pencil', 'title' => 'Kelas 1–3 SD', 'description' => 'Matematika, bahasa, literasi dasar', 'sort_order' => 2],
            ['group' => 'quick_link', 'icon' => 'stars', 'title' => 'Kelas 4–6 SD', 'description' => 'Penguatan mapel & persiapan SMP', 'sort_order' => 3],
            ['group' => 'quick_link', 'icon' => 'heart', 'title' => 'Program khusus', 'description' => 'Les privat & kelompok sesuai kebutuhan', 'sort_order' => 4],
            ['group' => 'stat', 'icon' => null, 'title' => 'Program & modul belajar', 'value' => 0, 'suffix' => '+', 'sort_order' => 1],
            ['group' => 'stat', 'icon' => null, 'title' => 'Siswa per kelas (maks.)', 'value' => 8, 'suffix' => '', 'sort_order' => 2],
            ['group' => 'stat', 'icon' => null, 'title' => 'Laporan ke orang tua', 'value' => 100, 'suffix' => '%', 'sort_order' => 3],
            ['group' => 'about_value', 'icon' => 'sun', 'title' => 'Suasana positif & penuh semangat', 'sort_order' => 1],
            ['group' => 'about_value', 'icon' => 'shield-check', 'title' => 'Aman, sabar, dan menghargai anak', 'sort_order' => 2],
            ['group' => 'about_value', 'icon' => 'graph-up-arrow', 'title' => 'Progress bertahap, tanpa membandingkan', 'sort_order' => 3],
            ['group' => 'about_value', 'icon' => 'house-heart', 'title' => 'Orang tua dilibatkan sebagai mitra', 'sort_order' => 4],
            ['group' => 'feature', 'icon' => 'emoji-laughing', 'title' => 'Belajar sambil bermain', 'description' => 'Metode interaktif supaya anak tidak bosan dan lebih mudah mengingat materi.', 'sort_order' => 1],
            ['group' => 'feature', 'icon' => 'person-check', 'title' => 'Pengajar sabar & berpengalaman', 'description' => 'Tim tentor terbiasa dengan karakter anak usia dini dan sekolah dasar.', 'sort_order' => 2],
            ['group' => 'feature', 'icon' => 'calendar-week', 'title' => 'Jadwal fleksibel', 'description' => 'Pilihan kelas reguler maupun intensif — disesuaikan aktivitas sekolah anak.', 'sort_order' => 3],
            ['group' => 'feature', 'icon' => 'clipboard-data', 'title' => 'Progress terpantau', 'description' => 'Catatan perkembangan per sesi, bukan hanya nilai akhir ujian.', 'sort_order' => 4],
            ['group' => 'feature', 'icon' => 'people', 'title' => 'Lingkungan kecil & akrab', 'description' => 'Anak dikenal namanya, bukan hanya nomor absen — seperti keluarga kecil.', 'sort_order' => 5],
            ['group' => 'feature', 'icon' => 'geo-alt', 'title' => 'Lokasi nyaman', 'description' => 'Ruang belajar rapi dan ramah anak — tempat orang tua tenang menitipkan buah hati.', 'sort_order' => 6],
            ['group' => 'ortu_point', 'icon' => 'camera-video', 'title' => 'Dokumentasi kegiatan kelas untuk wali', 'sort_order' => 1],
            ['group' => 'ortu_point', 'icon' => 'journal-text', 'title' => 'Catatan tugas & saran pendampingan di rumah', 'sort_order' => 2],
            ['group' => 'ortu_point', 'icon' => 'phone', 'title' => 'Akses informasi kapan saja lewat portal wali', 'sort_order' => 3],
            ['group' => 'kemitraan_point', 'icon' => 'award', 'title' => 'Brand & identitas visual resmi', 'sort_order' => 1],
            ['group' => 'kemitraan_point', 'icon' => 'book', 'title' => 'Kurikulum, modul ajar, dan SOP operasional', 'sort_order' => 2],
            ['group' => 'kemitraan_point', 'icon' => 'mortarboard', 'title' => 'Pelatihan guru, admin, dan manajemen cabang', 'sort_order' => 3],
            ['group' => 'kemitraan_point', 'icon' => 'megaphone', 'title' => 'Dukungan pemasaran & grand opening', 'sort_order' => 4],
            ['group' => 'kemitraan_point', 'icon' => 'headset', 'title' => 'Pendampingan berkelanjutan dari pusat', 'sort_order' => 5],
            ['group' => 'step', 'icon' => null, 'title' => 'Konsultasi awal', 'description' => 'Diskusi visi, profil wilayah, dan kesiapan Anda sebagai mitra cabang.', 'sort_order' => 1],
            ['group' => 'step', 'icon' => null, 'title' => 'Survey lokasi & perencanaan', 'description' => 'Penilaian lokasi, layout ruang belajar, dan estimasi kapasitas siswa.', 'sort_order' => 2],
            ['group' => 'step', 'icon' => null, 'title' => 'Pelatihan & persiapan operasional', 'description' => 'Tim Anda dilatih kurikulum, standar layanan, dan sistem administrasi.', 'sort_order' => 3],
            ['group' => 'step', 'icon' => null, 'title' => 'Grand opening & pendampingan', 'description' => 'Peluncuran cabang dengan dukungan pemasaran, lalu monitoring berkala dari pusat.', 'sort_order' => 4],
        ];

        if (LandingItem::query()->count() === 0) {
            foreach ($items as $item) {
                LandingItem::create($item);
            }
        }
    }
}
