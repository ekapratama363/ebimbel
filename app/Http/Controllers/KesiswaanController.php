<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Kelompok;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Picqer\Barcode\BarcodeGeneratorPNG;

class KesiswaanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $studentsQuery = Student::with('kelompok')->orderBy('name');
        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        return view('pages.kesiswaan', [
            'kelompoks' => Kelompok::with('program')->withCount('students')->orderBy('code')->get(),
            'students' => $studentsQuery->get(),
            'guardians' => Guardian::with('student')->orderBy('name')->get(),
            'programs' => Program::orderBy('code')->get(),
            'search' => $search,
        ]);
    }

    public function storeKelompok(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:kelompoks,code',
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'capacity' => 'required|integer|min:1',
        ]);
        $data['status'] = 'aktif';
        Kelompok::create($data);

        return back()->with('status', 'Kelompok berhasil ditambahkan.');
    }

    public function updateKelompok(Request $request, Kelompok $kelompok): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:kelompoks,code,'.$kelompok->id,
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'capacity' => 'required|integer|min:1',
        ]);
        $kelompok->update($data);

        return back()->with('status', 'Kelompok berhasil diperbarui.');
    }

    public function destroyKelompok(Kelompok $kelompok): RedirectResponse
    {
        $kelompok->delete();

        return back()->with('status', 'Kelompok dihapus.');
    }

    public function storeStudent(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->validate([
            'nis' => 'required|string|max:30|unique:students,nis',
            'name' => 'required|string|max:255',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'status' => 'required|string|max:20',
            'gender' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        $data['registered_at'] = now()->toDateString();
        $student = Student::create($data);
        $student->ensureCardNumber();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students/'.$student->id, 'public');
            $student->update(['photo_path' => 'storage/'.$path]);
        }

        return back()->with('status', 'Siswa berhasil ditambahkan.');
    }

    public function updateStudent(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->validate([
            'nis' => 'required|string|max:30|unique:students,nis,'.$student->id,
            'name' => 'required|string|max:255',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'status' => 'required|string|max:20',
            'gender' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        $student->update($data);
        $student->ensureCardNumber();

        if ($request->hasFile('photo')) {
            $student->deletePhotoFile();
            $path = $request->file('photo')->store('students/'.$student->id, 'public');
            $student->update(['photo_path' => 'storage/'.$path]);
        }

        return back()->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function studentCard(Student $student): View
    {
        $student->load(['kelompok.program', 'guardians']);
        $student->ensureCardNumber();

        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($student->nis, $generator::TYPE_CODE_128, 2, 50));

        return view('pages.kartu-pelajar', [
            'student' => $student,
            'barcodePngBase64' => $barcode,
        ]);
    }

    public function storeGuardian(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
        ]);
        Guardian::create($data);

        return back()->with('status', 'Wali berhasil dihubungkan.');
    }

    public function updateGuardian(Request $request, Guardian $guardian): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
        ]);
        $guardian->update($data);

        return back()->with('status', 'Data wali berhasil diperbarui.');
    }
}
