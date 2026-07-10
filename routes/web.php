<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DuitkuController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\LandingAdminController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/orang-tua', [OrangTuaController::class, 'index'])->name('orang-tua');

Route::post('/callback', [DuitkuController::class, 'callback'])->name('callback');
Route::get('/return', [DuitkuController::class, 'return'])->name('return');

Route::middleware('auth')->group(function () {
    Route::middleware('permission:akademik.view')->get('/akademik', [AkademikController::class, 'index'])->name('akademik');
    Route::middleware('permission:akademik.create')->post('/akademik/jenjangs', [AkademikController::class, 'storeJenjang'])->name('akademik.jenjangs.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/jenjangs/{jenjang}', [AkademikController::class, 'updateJenjang'])->name('akademik.jenjangs.update');
    Route::middleware('permission:akademik.delete')->delete('/akademik/jenjangs/{jenjang}', [AkademikController::class, 'destroyJenjang'])->name('akademik.jenjangs.destroy');
    Route::middleware('permission:akademik.create')->post('/akademik/programs', [AkademikController::class, 'storeProgram'])->name('akademik.programs.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/programs/{program}', [AkademikController::class, 'updateProgram'])->name('akademik.programs.update');
    Route::middleware('permission:akademik.delete')->delete('/akademik/programs/{program}', [AkademikController::class, 'destroyProgram'])->name('akademik.programs.destroy');
    Route::middleware('permission:akademik.create')->post('/akademik/subjects', [AkademikController::class, 'storeSubject'])->name('akademik.subjects.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/subjects/{subject}', [AkademikController::class, 'updateSubject'])->name('akademik.subjects.update');
    Route::middleware('permission:akademik.delete')->delete('/akademik/subjects/{subject}', [AkademikController::class, 'destroySubject'])->name('akademik.subjects.destroy');
    Route::middleware('permission:akademik.create')->post('/akademik/schedules', [AkademikController::class, 'storeSchedule'])->name('akademik.schedules.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/schedules/{schedule}', [AkademikController::class, 'updateSchedule'])->name('akademik.schedules.update');
    Route::middleware('permission:akademik.delete')->delete('/akademik/schedules/{schedule}', [AkademikController::class, 'destroySchedule'])->name('akademik.schedules.destroy');
    Route::middleware('permission:akademik.create')->post('/akademik/journals', [AkademikController::class, 'storeJournal'])->name('akademik.journals.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/journals/{journal}', [AkademikController::class, 'updateJournal'])->name('akademik.journals.update');
    Route::middleware('permission:akademik.create')->post('/akademik/reports', [AkademikController::class, 'storeReport'])->name('akademik.reports.store');
    Route::middleware('permission:akademik.edit')->put('/akademik/reports/{report}', [AkademikController::class, 'updateReport'])->name('akademik.reports.update');
    Route::middleware('permission:akademik.edit')->post('/akademik/reports/{report}/media', [AkademikController::class, 'uploadReportMedia'])->name('akademik.reports.media.store');
    Route::middleware('permission:akademik.delete')->delete('/akademik/reports/{report}/media/{media}', [AkademikController::class, 'destroyReportMedia'])->name('akademik.reports.media.destroy');
    Route::middleware('permission:akademik.publish')->patch('/akademik/reports/{report}/publish', [AkademikController::class, 'publishReport'])->name('akademik.reports.publish');
    Route::middleware('permission:akademik.publish')->patch('/akademik/reports/{report}/unpublish', [AkademikController::class, 'unpublishReport'])->name('akademik.reports.unpublish');

    Route::middleware('permission:kesiswaan.view')->get('/kesiswaan', [KesiswaanController::class, 'index'])->name('kesiswaan');
    Route::middleware('permission:kesiswaan.create')->post('/kesiswaan/kelompoks', [KesiswaanController::class, 'storeKelompok'])->name('kesiswaan.kelompoks.store');
    Route::middleware('permission:kesiswaan.edit')->put('/kesiswaan/kelompoks/{kelompok}', [KesiswaanController::class, 'updateKelompok'])->name('kesiswaan.kelompoks.update');
    Route::middleware('permission:kesiswaan.delete')->delete('/kesiswaan/kelompoks/{kelompok}', [KesiswaanController::class, 'destroyKelompok'])->name('kesiswaan.kelompoks.destroy');
    Route::middleware('permission:kesiswaan.create')->post('/kesiswaan/students', [KesiswaanController::class, 'storeStudent'])->name('kesiswaan.students.store');
    Route::middleware('permission:kesiswaan.edit')->put('/kesiswaan/students/{student}', [KesiswaanController::class, 'updateStudent'])->name('kesiswaan.students.update');
    Route::middleware('permission:kesiswaan.export')->get('/kesiswaan/students/{student}/card', [KesiswaanController::class, 'studentCard'])->name('kesiswaan.students.card');
    Route::middleware('permission:kesiswaan.create')->post('/kesiswaan/guardians', [KesiswaanController::class, 'storeGuardian'])->name('kesiswaan.guardians.store');
    Route::middleware('permission:kesiswaan.edit')->put('/kesiswaan/guardians/{guardian}', [KesiswaanController::class, 'updateGuardian'])->name('kesiswaan.guardians.update');
    Route::middleware('permission:kesiswaan.create')->post('/kesiswaan/student-attendances', [KesiswaanController::class, 'storeStudentAttendances'])->name('kesiswaan.student-attendances.store');

    Route::middleware('permission:keuangan.view')->get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
    Route::middleware('permission:keuangan.view')->get('/keuangan/payments/{payment}', [KeuanganController::class, 'showPayment'])->name('keuangan.payments.show');
    Route::middleware('permission:keuangan.create')->post('/keuangan/payments', [KeuanganController::class, 'storePayment'])->name('keuangan.payments.store');
    Route::middleware('permission:keuangan.edit')->put('/keuangan/payments/{payment}', [KeuanganController::class, 'updatePayment'])->name('keuangan.payments.update');
    Route::middleware('permission:keuangan.edit')->post('/keuangan/payments/{payment}/proof', [KeuanganController::class, 'uploadProof'])->name('keuangan.payments.proof');
    Route::middleware('permission:keuangan.confirm')->patch('/keuangan/payments/{payment}/confirm', [KeuanganController::class, 'confirmPayment'])->name('keuangan.payments.confirm');
    Route::middleware('permission:keuangan.view')->get('/keuangan/payments/{payment}/duitku', [DuitkuController::class, 'pay'])->name('keuangan.payments.duitku');
    Route::middleware('permission:keuangan.create')->post('/keuangan/payments/{payment}/link', [DuitkuController::class, 'createLink'])->name('keuangan.payments.link');
    Route::middleware('permission:keuangan.delete')->delete('/keuangan/payments/{payment}', [KeuanganController::class, 'destroyPayment'])->name('keuangan.payments.destroy');
    Route::middleware('permission:keuangan.create')->post('/keuangan/payment-types', [KeuanganController::class, 'storePaymentType'])->name('keuangan.payment-types.store');
    Route::middleware('permission:keuangan.edit')->put('/keuangan/payment-types/{paymentType}', [KeuanganController::class, 'updatePaymentType'])->name('keuangan.payment-types.update');
    Route::middleware('permission:keuangan.delete')->delete('/keuangan/payment-types/{paymentType}', [KeuanganController::class, 'destroyPaymentType'])->name('keuangan.payment-types.destroy');
    Route::middleware('permission:keuangan.create')->post('/keuangan/expenses', [KeuanganController::class, 'storeExpense'])->name('keuangan.expenses.store');
    Route::middleware('permission:keuangan.edit')->put('/keuangan/expenses/{expense}', [KeuanganController::class, 'updateExpense'])->name('keuangan.expenses.update');
    Route::middleware('permission:keuangan.delete')->delete('/keuangan/expenses/{expense}', [KeuanganController::class, 'destroyExpense'])->name('keuangan.expenses.destroy');

    Route::middleware('permission:kepegawaian.view')->get('/kepegawaian', [KepegawaianController::class, 'index'])->name('kepegawaian');
    Route::middleware('permission:kepegawaian.create')->post('/kepegawaian/employees', [KepegawaianController::class, 'storeEmployee'])->name('kepegawaian.employees.store');
    Route::middleware('permission:kepegawaian.edit')->put('/kepegawaian/employees/{employee}', [KepegawaianController::class, 'updateEmployee'])->name('kepegawaian.employees.update');
    Route::middleware('permission:kepegawaian.delete')->delete('/kepegawaian/employees/{employee}', [KepegawaianController::class, 'destroyEmployee'])->name('kepegawaian.employees.destroy');
    Route::middleware('permission:kepegawaian.create')->post('/kepegawaian/tutors', [KepegawaianController::class, 'storeTutor'])->name('kepegawaian.tutors.store');
    Route::middleware('permission:kepegawaian.edit')->put('/kepegawaian/tutors/{tutor}', [KepegawaianController::class, 'updateTutor'])->name('kepegawaian.tutors.update');
    Route::middleware('permission:kepegawaian.delete')->delete('/kepegawaian/tutors/{tutor}', [KepegawaianController::class, 'destroyTutor'])->name('kepegawaian.tutors.destroy');
    Route::middleware('permission:kepegawaian.create')->post('/kepegawaian/attendances', [KepegawaianController::class, 'storeAttendance'])->name('kepegawaian.attendances.store');
    Route::middleware('permission:kepegawaian.edit')->put('/kepegawaian/attendances/{attendance}', [KepegawaianController::class, 'updateAttendance'])->name('kepegawaian.attendances.update');
    Route::middleware('permission:kepegawaian.delete')->delete('/kepegawaian/attendances/{attendance}', [KepegawaianController::class, 'destroyAttendance'])->name('kepegawaian.attendances.destroy');

    Route::middleware('permission:pengaturan.view')->get('/pengaturan', [SiteSettingController::class, 'edit'])->name('pengaturan');
    Route::middleware('permission:pengaturan.edit')->put('/pengaturan', [SiteSettingController::class, 'update'])->name('pengaturan.update');

    Route::middleware('permission:akses.view')->get('/akses/roles', [AccessController::class, 'roles'])->name('akses.roles');
    Route::middleware('permission:akses.create')->post('/akses/roles', [AccessController::class, 'storeRole'])->name('akses.roles.store');
    Route::middleware('permission:akses.edit')->put('/akses/roles/{role}', [AccessController::class, 'updateRole'])->name('akses.roles.update');
    Route::middleware('permission:akses.delete')->delete('/akses/roles/{role}', [AccessController::class, 'destroyRole'])->name('akses.roles.destroy');
    Route::middleware('permission:akses.view')->get('/akses/users', [AccessController::class, 'users'])->name('akses.users');
    Route::middleware('permission:akses.create')->post('/akses/users', [AccessController::class, 'storeUser'])->name('akses.users.store');
    Route::middleware('permission:akses.edit')->put('/akses/users/{user}', [AccessController::class, 'updateUser'])->name('akses.users.update');
    Route::middleware('permission:akses.delete')->delete('/akses/users/{user}', [AccessController::class, 'destroyUser'])->name('akses.users.destroy');

    Route::middleware('permission:konten-landing.view')->get('/konten-landing', [LandingAdminController::class, 'index'])->name('konten-landing');
    Route::middleware('permission:konten-landing.create')->post('/konten-landing/slides', [LandingAdminController::class, 'storeSlide'])->name('konten-landing.slides.store');
    Route::middleware('permission:konten-landing.edit')->put('/konten-landing/slides/{slide}', [LandingAdminController::class, 'updateSlide'])->name('konten-landing.slides.update');
    Route::middleware('permission:konten-landing.delete')->delete('/konten-landing/slides/{slide}', [LandingAdminController::class, 'destroySlide'])->name('konten-landing.slides.destroy');
    Route::middleware('permission:konten-landing.create')->post('/konten-landing/programs', [LandingAdminController::class, 'storeProgram'])->name('konten-landing.programs.store');
    Route::middleware('permission:konten-landing.edit')->put('/konten-landing/programs/{program}', [LandingAdminController::class, 'updateProgram'])->name('konten-landing.programs.update');
    Route::middleware('permission:konten-landing.delete')->delete('/konten-landing/programs/{program}', [LandingAdminController::class, 'destroyProgram'])->name('konten-landing.programs.destroy');
    Route::middleware('permission:konten-landing.edit')->put('/konten-landing/sections/{key}', [LandingAdminController::class, 'updateSection'])->name('konten-landing.sections.update');
    Route::middleware('permission:konten-landing.create')->post('/konten-landing/items', [LandingAdminController::class, 'storeItem'])->name('konten-landing.items.store');
    Route::middleware('permission:konten-landing.edit')->put('/konten-landing/items/{item}', [LandingAdminController::class, 'updateItem'])->name('konten-landing.items.update');
    Route::middleware('permission:konten-landing.delete')->delete('/konten-landing/items/{item}', [LandingAdminController::class, 'destroyItem'])->name('konten-landing.items.destroy');
});
