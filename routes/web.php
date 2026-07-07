<?php

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
    Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik');
    Route::post('/akademik/programs', [AkademikController::class, 'storeProgram'])->name('akademik.programs.store');
    Route::put('/akademik/programs/{program}', [AkademikController::class, 'updateProgram'])->name('akademik.programs.update');
    Route::delete('/akademik/programs/{program}', [AkademikController::class, 'destroyProgram'])->name('akademik.programs.destroy');
    Route::post('/akademik/subjects', [AkademikController::class, 'storeSubject'])->name('akademik.subjects.store');
    Route::put('/akademik/subjects/{subject}', [AkademikController::class, 'updateSubject'])->name('akademik.subjects.update');
    Route::delete('/akademik/subjects/{subject}', [AkademikController::class, 'destroySubject'])->name('akademik.subjects.destroy');
    Route::post('/akademik/schedules', [AkademikController::class, 'storeSchedule'])->name('akademik.schedules.store');
    Route::put('/akademik/schedules/{schedule}', [AkademikController::class, 'updateSchedule'])->name('akademik.schedules.update');
    Route::delete('/akademik/schedules/{schedule}', [AkademikController::class, 'destroySchedule'])->name('akademik.schedules.destroy');
    Route::post('/akademik/journals', [AkademikController::class, 'storeJournal'])->name('akademik.journals.store');
    Route::put('/akademik/journals/{journal}', [AkademikController::class, 'updateJournal'])->name('akademik.journals.update');
    Route::post('/akademik/reports', [AkademikController::class, 'storeReport'])->name('akademik.reports.store');
    Route::put('/akademik/reports/{report}', [AkademikController::class, 'updateReport'])->name('akademik.reports.update');
    Route::post('/akademik/reports/{report}/media', [AkademikController::class, 'uploadReportMedia'])->name('akademik.reports.media.store');
    Route::delete('/akademik/reports/{report}/media/{media}', [AkademikController::class, 'destroyReportMedia'])->name('akademik.reports.media.destroy');
    Route::patch('/akademik/reports/{report}/publish', [AkademikController::class, 'publishReport'])->name('akademik.reports.publish');
    Route::patch('/akademik/reports/{report}/unpublish', [AkademikController::class, 'unpublishReport'])->name('akademik.reports.unpublish');

    Route::get('/kesiswaan', [KesiswaanController::class, 'index'])->name('kesiswaan');
    Route::post('/kesiswaan/kelompoks', [KesiswaanController::class, 'storeKelompok'])->name('kesiswaan.kelompoks.store');
    Route::put('/kesiswaan/kelompoks/{kelompok}', [KesiswaanController::class, 'updateKelompok'])->name('kesiswaan.kelompoks.update');
    Route::delete('/kesiswaan/kelompoks/{kelompok}', [KesiswaanController::class, 'destroyKelompok'])->name('kesiswaan.kelompoks.destroy');
    Route::post('/kesiswaan/students', [KesiswaanController::class, 'storeStudent'])->name('kesiswaan.students.store');
    Route::put('/kesiswaan/students/{student}', [KesiswaanController::class, 'updateStudent'])->name('kesiswaan.students.update');
    Route::get('/kesiswaan/students/{student}/card', [KesiswaanController::class, 'studentCard'])->name('kesiswaan.students.card');
    Route::post('/kesiswaan/guardians', [KesiswaanController::class, 'storeGuardian'])->name('kesiswaan.guardians.store');
    Route::put('/kesiswaan/guardians/{guardian}', [KesiswaanController::class, 'updateGuardian'])->name('kesiswaan.guardians.update');

    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
    Route::get('/keuangan/payments/{payment}', [KeuanganController::class, 'showPayment'])->name('keuangan.payments.show');
    Route::post('/keuangan/payments', [KeuanganController::class, 'storePayment'])->name('keuangan.payments.store');
    Route::put('/keuangan/payments/{payment}', [KeuanganController::class, 'updatePayment'])->name('keuangan.payments.update');
    Route::post('/keuangan/payments/{payment}/proof', [KeuanganController::class, 'uploadProof'])->name('keuangan.payments.proof');
    Route::patch('/keuangan/payments/{payment}/confirm', [KeuanganController::class, 'confirmPayment'])->name('keuangan.payments.confirm');
    Route::get('/keuangan/payments/{payment}/duitku', [DuitkuController::class, 'pay'])->name('keuangan.payments.duitku');
    Route::post('/keuangan/payments/{payment}/link', [DuitkuController::class, 'createLink'])->name('keuangan.payments.link');
    Route::delete('/keuangan/payments/{payment}', [KeuanganController::class, 'destroyPayment'])->name('keuangan.payments.destroy');
    Route::post('/keuangan/payment-types', [KeuanganController::class, 'storePaymentType'])->name('keuangan.payment-types.store');
    Route::put('/keuangan/payment-types/{paymentType}', [KeuanganController::class, 'updatePaymentType'])->name('keuangan.payment-types.update');
    Route::delete('/keuangan/payment-types/{paymentType}', [KeuanganController::class, 'destroyPaymentType'])->name('keuangan.payment-types.destroy');
    Route::post('/keuangan/expenses', [KeuanganController::class, 'storeExpense'])->name('keuangan.expenses.store');
    Route::put('/keuangan/expenses/{expense}', [KeuanganController::class, 'updateExpense'])->name('keuangan.expenses.update');
    Route::delete('/keuangan/expenses/{expense}', [KeuanganController::class, 'destroyExpense'])->name('keuangan.expenses.destroy');

    Route::get('/kepegawaian', [KepegawaianController::class, 'index'])->name('kepegawaian');
    Route::post('/kepegawaian/employees', [KepegawaianController::class, 'storeEmployee'])->name('kepegawaian.employees.store');
    Route::put('/kepegawaian/employees/{employee}', [KepegawaianController::class, 'updateEmployee'])->name('kepegawaian.employees.update');
    Route::delete('/kepegawaian/employees/{employee}', [KepegawaianController::class, 'destroyEmployee'])->name('kepegawaian.employees.destroy');
    Route::post('/kepegawaian/tutors', [KepegawaianController::class, 'storeTutor'])->name('kepegawaian.tutors.store');
    Route::put('/kepegawaian/tutors/{tutor}', [KepegawaianController::class, 'updateTutor'])->name('kepegawaian.tutors.update');
    Route::delete('/kepegawaian/tutors/{tutor}', [KepegawaianController::class, 'destroyTutor'])->name('kepegawaian.tutors.destroy');

    Route::get('/pengaturan', [SiteSettingController::class, 'edit'])->name('pengaturan');
    Route::put('/pengaturan', [SiteSettingController::class, 'update'])->name('pengaturan.update');

    Route::get('/konten-landing', [LandingAdminController::class, 'index'])->name('konten-landing');
    Route::post('/konten-landing/slides', [LandingAdminController::class, 'storeSlide'])->name('konten-landing.slides.store');
    Route::put('/konten-landing/slides/{slide}', [LandingAdminController::class, 'updateSlide'])->name('konten-landing.slides.update');
    Route::delete('/konten-landing/slides/{slide}', [LandingAdminController::class, 'destroySlide'])->name('konten-landing.slides.destroy');
    Route::post('/konten-landing/programs', [LandingAdminController::class, 'storeProgram'])->name('konten-landing.programs.store');
    Route::put('/konten-landing/programs/{program}', [LandingAdminController::class, 'updateProgram'])->name('konten-landing.programs.update');
    Route::delete('/konten-landing/programs/{program}', [LandingAdminController::class, 'destroyProgram'])->name('konten-landing.programs.destroy');
    Route::put('/konten-landing/sections/{key}', [LandingAdminController::class, 'updateSection'])->name('konten-landing.sections.update');
    Route::post('/konten-landing/items', [LandingAdminController::class, 'storeItem'])->name('konten-landing.items.store');
    Route::put('/konten-landing/items/{item}', [LandingAdminController::class, 'updateItem'])->name('konten-landing.items.update');
    Route::delete('/konten-landing/items/{item}', [LandingAdminController::class, 'destroyItem'])->name('konten-landing.items.destroy');
});
