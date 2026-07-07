<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Guardian;
use App\Models\Kelompok;
use App\Models\LandingProgram;
use App\Models\LandingSlide;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\SiteSetting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeachingJournal;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EbimbelSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->firstOrCreate([], SiteSetting::defaults());

        User::updateOrCreate(
            ['email' => 'admin@bimbel.contoh'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $budiUser = User::updateOrCreate(
            ['email' => 'budi.s@email.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'guardian',
            ]
        );

        $slides = [
            [
                'tag' => 'Jenius Kids · BiMBA',
                'title' => 'Where fun meets learning',
                'description' => 'Bimbingan belajar anak yang hangat, bermain, dan membangun percaya diri sejak usia dini.',
                'cta_text' => 'Jelajahi program',
                'cta_link' => '#program',
                'slide_style' => 1,
                'sort_order' => 1,
            ],
            [
                'tag' => 'Program belajar',
                'title' => 'Calistung, matematika, bahasa & kreativitas',
                'description' => 'Dari playgroup hingga kelas 6 SD — kurikulum disesuaikan usia dan gaya belajar anak, dengan kelas kecil yang akrab.',
                'cta_text' => 'Daftar & konsultasi gratis',
                'cta_link' => '#daftar',
                'slide_style' => 2,
                'sort_order' => 2,
            ],
            [
                'tag' => 'Kemitraan cabang',
                'title' => 'Ingin membuka cabang Jenius Kids?',
                'description' => 'Wujudkan bimbel anak bermerek di kota Anda — dengan dukungan brand, kurikulum, pelatihan, dan standar operasional dari pusat.',
                'cta_text' => 'Pelajari kemitraan',
                'cta_link' => '#kemitraan',
                'slide_style' => 3,
                'sort_order' => 3,
            ],
        ];

        foreach ($slides as $slide) {
            LandingSlide::create($slide);
        }

        $landingPrograms = [
            ['title' => 'Calistung & literasi', 'description' => 'Membaca, menulis, dan berhitung dasar untuk PAUD–SD awal dengan permainan dan cerita.', 'icon' => 'alphabet', 'color_variant' => 'teal', 'sort_order' => 1],
            ['title' => 'Matematika', 'description' => 'Konsep numerik, logika, dan latihan soal sesuai kurikulum sekolah — tidak menakutkan.', 'icon' => 'calculator', 'color_variant' => 'amber', 'sort_order' => 2],
            ['title' => 'Bahasa Indonesia & Inggris', 'description' => 'Kosakata, tata bahasa, dan percakapan sederhana untuk percaya diri berkomunikasi.', 'icon' => 'translate', 'color_variant' => 'orange', 'sort_order' => 3],
            ['title' => 'Kreativitas & minat', 'description' => 'Seni, eksplorasi, dan kegiatan pendukung minat anak di luar mapel inti sekolah.', 'icon' => 'palette', 'color_variant' => 'red', 'sort_order' => 4],
        ];

        foreach ($landingPrograms as $lp) {
            LandingProgram::create($lp);
        }

        $prg1 = Program::create(['code' => 'PRG-01', 'name' => 'IPA Intensif UTBK', 'jenjang' => 'SMA', 'status' => 'aktif']);
        $prg2 = Program::create(['code' => 'PRG-02', 'name' => 'Matematika Dasar Kelas 10', 'jenjang' => 'SMA', 'status' => 'aktif']);

        $mat = Subject::create(['code' => 'MAP-MAT', 'name' => 'Matematika', 'kelompok' => 'Wajib']);
        $fis = Subject::create(['code' => 'MAP-FIS', 'name' => 'Fisika', 'kelompok' => 'Jurusan']);

        $kelA = Kelompok::create(['program_id' => $prg1->id, 'code' => 'KEL-A', 'name' => 'IPA Intensif — Kelas A', 'capacity' => 30, 'status' => 'aktif']);
        $kelB = Kelompok::create(['program_id' => $prg1->id, 'code' => 'KEL-B', 'name' => 'IPA Intensif — Kelas B', 'capacity' => 28, 'status' => 'aktif']);
        $kelMtd = Kelompok::create(['program_id' => $prg2->id, 'code' => 'KEL-MTD', 'name' => 'Matematika Dasar — Kelas 10', 'capacity' => 20, 'status' => 'penuh']);

        $andi = Student::create(['kelompok_id' => $kelA->id, 'nis' => '2026-0142', 'name' => 'Andi Pratama', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2026-01-12']);
        $dewi = Student::create(['kelompok_id' => $kelA->id, 'nis' => '2026-0208', 'name' => 'Dewi Lestari', 'gender' => 'Perempuan', 'status' => 'aktif', 'registered_at' => '2026-02-03']);
        $rizky = Student::create(['kelompok_id' => $kelB->id, 'nis' => '2025-0091', 'name' => 'Rizky Maulana', 'gender' => 'Laki-laki', 'status' => 'cuti', 'registered_at' => '2025-08-08']);
        $ahmad = Student::create(['kelompok_id' => $kelMtd->id, 'nis' => '2024001', 'name' => 'Ahmad Abdullah', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2024-01-15']);
        $budiS = Student::create(['kelompok_id' => $kelB->id, 'nis' => '2024002', 'name' => 'Budi Santoso', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2024-02-01']);
        $citra = Student::create(['kelompok_id' => $kelA->id, 'nis' => '2024003', 'name' => 'Citra Rahayu', 'gender' => 'Perempuan', 'status' => 'aktif', 'registered_at' => '2024-03-10']);

        Guardian::create(['student_id' => $andi->id, 'user_id' => $budiUser->id, 'name' => 'Budi Santoso', 'relationship' => 'Ayah', 'phone' => '0812-xxxx-xxxx', 'email' => 'budi.s@email.test']);
        Guardian::create(['student_id' => $dewi->id, 'name' => 'Siti Rahayu', 'relationship' => 'Ibu', 'phone' => '0857-xxxx-xxxx', 'email' => 'siti.r@email.test']);

        $tutorBudi = Tutor::create(['code' => 'T004', 'name' => 'Budi Santoso', 'subject' => 'Matematika', 'experience_years' => 6, 'status' => 'aktif', 'joined_at' => '2023-06-01']);
        $tutorSiti = Tutor::create(['code' => 'T005', 'name' => 'Siti Aminah', 'subject' => 'Fisika', 'experience_years' => 4, 'status' => 'aktif', 'joined_at' => '2024-01-15']);
        Tutor::create(['code' => 'T001', 'name' => 'Muhammad Surya', 'subject' => 'Matematika', 'experience_years' => 5, 'status' => 'aktif', 'joined_at' => '2024-01-01']);
        Tutor::create(['code' => 'T002', 'name' => 'Ani Rahmawati', 'subject' => 'Bahasa Indonesia', 'experience_years' => 3, 'status' => 'aktif', 'joined_at' => '2024-02-01']);
        Tutor::create(['code' => 'T003', 'name' => 'Budi Prasetyo', 'subject' => 'Fisika', 'experience_years' => 7, 'status' => 'tidak_aktif', 'joined_at' => '2023-12-01']);

        Schedule::create(['day' => 'Senin', 'start_time' => '16:00', 'end_time' => '18:00', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'tutor_id' => $tutorBudi->id]);
        Schedule::create(['day' => 'Rabu', 'start_time' => '15:30', 'end_time' => '17:30', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'tutor_id' => $tutorSiti->id]);

        TeachingJournal::create(['date' => '2026-04-28', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'tutor_id' => $tutorBudi->id, 'material' => 'Limit fungsi aljabar', 'notes' => 'Siswa latihan soal nomor 1–10']);
        TeachingJournal::create(['date' => '2026-04-30', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'tutor_id' => $tutorSiti->id, 'material' => 'Hukum Newton', 'notes' => 'Praktikum ringkas']);

        DailyReport::create([
            'date' => '2026-05-05', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'tutor_id' => $tutorBudi->id,
            'summary' => 'Hari ini membahas limit fungsi aljabar. Siswa aktif mengerjakan latihan di kelas; beberapa masih perlu penguatan pada substitusi langsung.',
            'homework' => 'Kerjakan latihan modul halaman 12–14 (nomor 5–8).',
            'photo_count' => 2, 'video_count' => 1, 'status' => 'published', 'published_at' => now(),
        ]);
        DailyReport::create([
            'date' => '2026-05-04', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'tutor_id' => $tutorSiti->id,
            'summary' => 'Pembahasan Hukum Newton I dan II dengan contoh soal gerak lurus. Diskusi kelompok berjalan baik.',
            'homework' => 'Baca ringkasan bab 4; pilih 3 soal pilihan ganda untuk dibahas minggu depan.',
            'photo_count' => 0, 'video_count' => 1, 'status' => 'published', 'published_at' => now(),
        ]);
        DailyReport::create([
            'date' => '2026-05-05', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'tutor_id' => $tutorBudi->id,
            'summary' => 'Evaluasi singkat, catatan internal saja.',
            'homework' => null, 'photo_count' => 0, 'video_count' => 0, 'status' => 'draft',
        ]);
        DailyReport::create([
            'date' => '2026-05-05', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'tutor_id' => $tutorBudi->id,
            'summary' => 'Limit fungsi, siswa aktif mengerjakan latihan.',
            'homework' => null, 'photo_count' => 2, 'video_count' => 1, 'status' => 'published', 'published_at' => now(),
        ]);
        DailyReport::create([
            'date' => '2026-05-04', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'tutor_id' => $tutorSiti->id,
            'summary' => 'Hukum Newton I–II, diskusi contoh soal.',
            'homework' => null, 'photo_count' => 0, 'video_count' => 1, 'status' => 'published', 'published_at' => now(),
        ]);

        Payment::create(['student_id' => $ahmad->id, 'amount' => 500000, 'status' => 'lunas', 'paid_at' => '2024-05-15']);
        Payment::create(['student_id' => $budiS->id, 'amount' => 500000, 'status' => 'pending', 'paid_at' => null]);
        Payment::create(['student_id' => $citra->id, 'amount' => 450000, 'status' => 'belum_bayar', 'paid_at' => null]);

        Expense::create(['description' => 'Sewa ruangan bulan Mei', 'category' => 'Operasional', 'amount' => 2500000, 'expense_date' => '2024-05-01']);
        Expense::create(['description' => 'Pembelian alat tulis', 'category' => 'Supplies', 'amount' => 750000, 'expense_date' => '2024-05-10']);
        Expense::create(['description' => 'Gaji tutor matematika', 'category' => 'Tenaga pengajar', 'amount' => 1200000, 'expense_date' => '2024-05-15']);

        $empSiti = Employee::create(['nik' => '2024001', 'name' => 'Siti Aminah', 'position' => 'Administrasi', 'department' => 'Umum', 'status' => 'aktif', 'joined_at' => '2024-01-01']);
        $empRudi = Employee::create(['nik' => '2024002', 'name' => 'Rudi Bakti', 'position' => 'Keuangan', 'department' => 'Keuangan', 'status' => 'aktif', 'joined_at' => '2024-02-01']);
        $empDewi = Employee::create(['nik' => '2024003', 'name' => 'Dewi Wahyuni', 'position' => 'IT Support', 'department' => 'Teknologi', 'status' => 'cuti', 'joined_at' => '2024-03-01']);

        $today = now()->toDateString();
        Attendance::create(['employee_id' => $empSiti->id, 'date' => $today, 'check_in' => '08:00', 'status' => 'hadir']);
        Attendance::create(['employee_id' => $empRudi->id, 'date' => $today, 'check_in' => '08:15', 'status' => 'hadir']);
        Attendance::create(['employee_id' => $empDewi->id, 'date' => $today, 'check_in' => null, 'status' => 'cuti']);
    }
}
