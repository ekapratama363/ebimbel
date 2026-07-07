<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrangTuaController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with('kelompok')->where('status', 'aktif')->orderBy('name')->get();
        $selectedStudentId = $request->query('siswa', $students->first()?->id);

        $reports = DailyReport::with(['kelompok', 'subject', 'tutor', 'media'])
            ->where('status', 'published')
            ->when($selectedStudentId, function ($q) use ($selectedStudentId) {
                $student = Student::find($selectedStudentId);
                if ($student?->kelompok_id) {
                    $q->where('kelompok_id', $student->kelompok_id);
                }
            })
            ->orderByDesc('date')
            ->get();

        $guardianName = Guardian::where('student_id', $selectedStudentId)->value('name') ?? 'Wali';

        return view('pages.orang-tua', [
            'students' => $students,
            'selectedStudentId' => (int) $selectedStudentId,
            'reports' => $reports,
            'guardianName' => $guardianName,
        ]);
    }
}
