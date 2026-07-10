<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\DailyReport;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Guardian;
use App\Models\Jenjang;
use App\Models\Kelompok;
use App\Models\LandingProgram;
use App\Models\LandingSlide;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\SiteSetting;
use App\Models\Student;
use App\Models\StudentAttendance;
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
                'role_id' => Role::where('slug', 'super_admin')->value('id'),
            ]
        );

        $budiUser = User::updateOrCreate(
            ['email' => 'budi.s@email.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'guardian')->value('id'),
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
            LandingSlide::updateOrCreate(['sort_order' => $slide['sort_order']], $slide);
        }

        $landingPrograms = [
            ['title' => 'Calistung & literasi', 'description' => 'Membaca, menulis, dan berhitung dasar untuk PAUD–SD awal dengan permainan dan cerita.', 'icon' => 'alphabet', 'color_variant' => 'teal', 'sort_order' => 1],
            ['title' => 'Matematika', 'description' => 'Konsep numerik, logika, dan latihan soal sesuai kurikulum sekolah — tidak menakutkan.', 'icon' => 'calculator', 'color_variant' => 'amber', 'sort_order' => 2],
            ['title' => 'Bahasa Indonesia & Inggris', 'description' => 'Kosakata, tata bahasa, dan percakapan sederhana untuk percaya diri berkomunikasi.', 'icon' => 'translate', 'color_variant' => 'orange', 'sort_order' => 3],
            ['title' => 'Kreativitas & minat', 'description' => 'Seni, eksplorasi, dan kegiatan pendukung minat anak di luar mapel inti sekolah.', 'icon' => 'palette', 'color_variant' => 'red', 'sort_order' => 4],
        ];

        foreach ($landingPrograms as $lp) {
            LandingProgram::updateOrCreate(['sort_order' => $lp['sort_order']], $lp);
        }

        $sma = Jenjang::query()->updateOrCreate(['code' => 'SMA'], ['name' => 'SMA', 'sort_order' => 3, 'status' => 'aktif']);
        Jenjang::query()->updateOrCreate(['code' => 'SD'], ['name' => 'Sekolah Dasar', 'sort_order' => 1, 'status' => 'aktif']);
        Jenjang::query()->updateOrCreate(['code' => 'SMP'], ['name' => 'SMP', 'sort_order' => 2, 'status' => 'aktif']);

        $prg1 = Program::updateOrCreate(
            ['code' => 'PRG-01'],
            ['name' => 'IPA Intensif UTBK', 'jenjang_id' => $sma->id, 'status' => 'aktif']
        );
        $prg2 = Program::updateOrCreate(
            ['code' => 'PRG-02'],
            ['name' => 'Matematika Dasar Kelas 10', 'jenjang_id' => $sma->id, 'status' => 'aktif']
        );

        $mat = Subject::updateOrCreate(['code' => 'MAP-MAT'], ['name' => 'Matematika', 'kelompok' => 'Wajib']);
        $fis = Subject::updateOrCreate(['code' => 'MAP-FIS'], ['name' => 'Fisika', 'kelompok' => 'Jurusan']);

        $kelA = Kelompok::updateOrCreate(
            ['code' => 'KEL-A'],
            ['program_id' => $prg1->id, 'name' => 'IPA Intensif — Kelas A', 'capacity' => 30, 'status' => 'aktif']
        );
        $kelB = Kelompok::updateOrCreate(
            ['code' => 'KEL-B'],
            ['program_id' => $prg1->id, 'name' => 'IPA Intensif — Kelas B', 'capacity' => 28, 'status' => 'aktif']
        );
        $kelMtd = Kelompok::updateOrCreate(
            ['code' => 'KEL-MTD'],
            ['program_id' => $prg2->id, 'name' => 'Matematika Dasar — Kelas 10', 'capacity' => 20, 'status' => 'penuh']
        );

        $andi = Student::updateOrCreate(
            ['nis' => '2026-0142'],
            ['kelompok_id' => $kelA->id, 'name' => 'Andi Pratama', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2026-01-12']
        );
        $dewi = Student::updateOrCreate(
            ['nis' => '2026-0208'],
            ['kelompok_id' => $kelA->id, 'name' => 'Dewi Lestari', 'gender' => 'Perempuan', 'status' => 'aktif', 'registered_at' => '2026-02-03']
        );
        Student::updateOrCreate(
            ['nis' => '2025-0091'],
            ['kelompok_id' => $kelB->id, 'name' => 'Rizky Maulana', 'gender' => 'Laki-laki', 'status' => 'cuti', 'registered_at' => '2025-08-08']
        );
        $ahmad = Student::updateOrCreate(
            ['nis' => '2024001'],
            ['kelompok_id' => $kelMtd->id, 'name' => 'Ahmad Abdullah', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2024-01-15']
        );
        $budiS = Student::updateOrCreate(
            ['nis' => '2024002'],
            ['kelompok_id' => $kelB->id, 'name' => 'Budi Santoso', 'gender' => 'Laki-laki', 'status' => 'aktif', 'registered_at' => '2024-02-01']
        );
        $citra = Student::updateOrCreate(
            ['nis' => '2024003'],
            ['kelompok_id' => $kelA->id, 'name' => 'Citra Rahayu', 'gender' => 'Perempuan', 'status' => 'aktif', 'registered_at' => '2024-03-10']
        );

        Guardian::updateOrCreate(
            ['student_id' => $andi->id, 'email' => 'budi.s@email.test'],
            ['user_id' => $budiUser->id, 'name' => 'Budi Santoso', 'relationship' => 'Ayah', 'phone' => '0812-xxxx-xxxx']
        );
        Guardian::updateOrCreate(
            ['student_id' => $dewi->id, 'email' => 'siti.r@email.test'],
            ['name' => 'Siti Rahayu', 'relationship' => 'Ibu', 'phone' => '0857-xxxx-xxxx']
        );

        $tutorBudi = Tutor::updateOrCreate(
            ['code' => 'T004'],
            ['name' => 'Budi Santoso', 'subject' => 'Matematika', 'experience_years' => 6, 'status' => 'aktif', 'joined_at' => '2023-06-01']
        );
        $tutorSiti = Tutor::updateOrCreate(
            ['code' => 'T005'],
            ['name' => 'Siti Aminah', 'subject' => 'Fisika', 'experience_years' => 4, 'status' => 'aktif', 'joined_at' => '2024-01-15']
        );
        Tutor::updateOrCreate(['code' => 'T001'], ['name' => 'Muhammad Surya', 'subject' => 'Matematika', 'experience_years' => 5, 'status' => 'aktif', 'joined_at' => '2024-01-01']);
        Tutor::updateOrCreate(['code' => 'T002'], ['name' => 'Ani Rahmawati', 'subject' => 'Bahasa Indonesia', 'experience_years' => 3, 'status' => 'aktif', 'joined_at' => '2024-02-01']);
        Tutor::updateOrCreate(['code' => 'T003'], ['name' => 'Budi Prasetyo', 'subject' => 'Fisika', 'experience_years' => 7, 'status' => 'tidak_aktif', 'joined_at' => '2023-12-01']);

        Schedule::updateOrCreate(
            ['day' => 'Senin', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'start_time' => '16:00'],
            ['end_time' => '18:00', 'tutor_id' => $tutorBudi->id]
        );
        Schedule::updateOrCreate(
            ['day' => 'Rabu', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'start_time' => '15:30'],
            ['end_time' => '17:30', 'tutor_id' => $tutorSiti->id]
        );

        TeachingJournal::updateOrCreate(
            ['date' => '2026-04-28', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id],
            ['tutor_id' => $tutorBudi->id, 'material' => 'Limit fungsi aljabar', 'notes' => 'Siswa latihan soal nomor 1–10']
        );
        TeachingJournal::updateOrCreate(
            ['date' => '2026-04-30', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id],
            ['tutor_id' => $tutorSiti->id, 'material' => 'Hukum Newton', 'notes' => 'Praktikum ringkas']
        );

        DailyReport::updateOrCreate(
            ['date' => '2026-05-05', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'status' => 'published'],
            [
                'tutor_id' => $tutorBudi->id,
                'summary' => 'Hari ini membahas limit fungsi aljabar. Siswa aktif mengerjakan latihan di kelas; beberapa masih perlu penguatan pada substitusi langsung.',
                'homework' => 'Kerjakan latihan modul halaman 12–14 (nomor 5–8).',
                'photo_count' => 2, 'video_count' => 1, 'published_at' => now(),
            ]
        );
        DailyReport::updateOrCreate(
            ['date' => '2026-05-04', 'kelompok_id' => $kelA->id, 'subject_id' => $fis->id, 'status' => 'published'],
            [
                'tutor_id' => $tutorSiti->id,
                'summary' => 'Pembahasan Hukum Newton I dan II dengan contoh soal gerak lurus. Diskusi kelompok berjalan baik.',
                'homework' => 'Baca ringkasan bab 4; pilih 3 soal pilihan ganda untuk dibahas minggu depan.',
                'photo_count' => 0, 'video_count' => 1, 'published_at' => now(),
            ]
        );
        DailyReport::updateOrCreate(
            ['date' => '2026-05-05', 'kelompok_id' => $kelA->id, 'subject_id' => $mat->id, 'status' => 'draft'],
            [
                'tutor_id' => $tutorBudi->id,
                'summary' => 'Evaluasi singkat, catatan internal saja.',
                'homework' => null, 'photo_count' => 0, 'video_count' => 0, 'published_at' => null,
            ]
        );

        Payment::updateOrCreate(
            ['student_id' => $ahmad->id, 'amount' => 500000],
            ['status' => 'lunas', 'paid_at' => '2024-05-15']
        );
        Payment::updateOrCreate(
            ['student_id' => $budiS->id, 'amount' => 500000],
            ['status' => 'pending', 'paid_at' => null]
        );
        Payment::updateOrCreate(
            ['student_id' => $citra->id, 'amount' => 450000],
            ['status' => 'belum_bayar', 'paid_at' => null]
        );

        Expense::updateOrCreate(
            ['description' => 'Sewa ruangan bulan Mei', 'expense_date' => '2024-05-01'],
            ['category' => 'Operasional', 'amount' => 2500000]
        );
        Expense::updateOrCreate(
            ['description' => 'Pembelian alat tulis', 'expense_date' => '2024-05-10'],
            ['category' => 'Supplies', 'amount' => 750000]
        );
        Expense::updateOrCreate(
            ['description' => 'Gaji tutor matematika', 'expense_date' => '2024-05-15'],
            ['category' => 'Tenaga pengajar', 'amount' => 1200000]
        );

        $empSiti = Employee::updateOrCreate(
            ['nik' => '2024001'],
            ['name' => 'Siti Aminah', 'position' => 'Administrasi', 'department' => 'Umum', 'status' => 'aktif', 'joined_at' => '2024-01-01']
        );
        $empRudi = Employee::updateOrCreate(
            ['nik' => '2024002'],
            ['name' => 'Rudi Bakti', 'position' => 'Keuangan', 'department' => 'Keuangan', 'status' => 'aktif', 'joined_at' => '2024-02-01']
        );
        $empDewi = Employee::updateOrCreate(
            ['nik' => '2024003'],
            ['name' => 'Dewi Wahyuni', 'position' => 'IT Support', 'department' => 'Teknologi', 'status' => 'cuti', 'joined_at' => '2024-03-01']
        );

        $today = now()->toDateString();
        Attendance::updateOrCreate(
            ['employee_id' => $empSiti->id, 'date' => $today],
            ['check_in' => '08:00', 'status' => 'hadir']
        );
        Attendance::updateOrCreate(
            ['employee_id' => $empRudi->id, 'date' => $today],
            ['check_in' => '08:15', 'status' => 'hadir']
        );
        Attendance::updateOrCreate(
            ['employee_id' => $empDewi->id, 'date' => $today],
            ['check_in' => null, 'status' => 'cuti']
        );

        $sampleDates = [
            now()->subDays(2)->toDateString(),
            now()->subDays(1)->toDateString(),
            now()->toDateString(),
        ];
        $sampleStatuses = ['hadir', 'hadir', 'izin'];

        foreach ($sampleDates as $i => $sampleDate) {
            StudentAttendance::updateOrCreate(
                ['date' => $sampleDate, 'student_id' => $andi->id],
                ['kelompok_id' => $kelA->id, 'status' => $sampleStatuses[$i]]
            );
            StudentAttendance::updateOrCreate(
                ['date' => $sampleDate, 'student_id' => $dewi->id],
                ['kelompok_id' => $kelA->id, 'status' => 'hadir']
            );
        }
    }
}
