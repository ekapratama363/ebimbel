<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KepegawaianController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->query('tanggal', now()->toDateString());
        $monthStart = now()->parse($selectedDate)->startOfMonth()->toDateString();
        $monthEnd = now()->parse($selectedDate)->endOfMonth()->toDateString();

        $attendancesOnDate = Attendance::with('employee')
            ->where('date', $selectedDate)
            ->orderBy('check_in')
            ->get();

        $monthAttendances = Attendance::whereBetween('date', [$monthStart, $monthEnd])->get();

        return view('pages.kepegawaian', [
            'employees' => Employee::where('status', 'aktif')->orderBy('name')->get(),
            'tutors' => Tutor::orderBy('name')->get(),
            'attendancesOnDate' => $attendancesOnDate,
            'selectedDate' => $selectedDate,
            'hadirCount' => $monthAttendances->where('status', 'hadir')->count(),
            'izinCount' => $monthAttendances->whereIn('status', ['izin', 'cuti'])->count(),
            'tidakHadirCount' => $monthAttendances->where('status', 'tidak_hadir')->count(),
        ]);
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nik' => 'required|string|max:30|unique:employees,nik',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'joined_at' => 'nullable|date',
        ]);
        $data['status'] = 'aktif';
        Employee::create($data);

        return back()->with('status', 'Karyawan berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'nik' => 'required|string|max:30|unique:employees,nik,'.$employee->id,
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'joined_at' => 'nullable|date',
        ]);
        $employee->update($data);

        return back()->with('status', 'Data karyawan berhasil diperbarui.');
    }

    public function destroyEmployee(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return back()->with('status', 'Karyawan dihapus.');
    }

    public function storeTutor(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:tutors,code',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'joined_at' => 'nullable|date',
        ]);
        $data['status'] = 'aktif';
        $data['experience_years'] = $data['experience_years'] ?? 0;
        Tutor::create($data);

        return back()->with('status', 'Tutor berhasil ditambahkan.');
    }

    public function updateTutor(Request $request, Tutor $tutor): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:tutors,code,'.$tutor->id,
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'joined_at' => 'nullable|date',
        ]);
        $data['experience_years'] = $data['experience_years'] ?? 0;
        $tutor->update($data);

        return back()->with('status', 'Data tutor berhasil diperbarui.');
    }

    public function destroyTutor(Tutor $tutor): RedirectResponse
    {
        $tutor->delete();

        return back()->with('status', 'Tutor dihapus.');
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|string|max:10',
            'check_out' => 'nullable|string|max:10',
            'status' => 'required|in:hadir,izin,cuti,tidak_hadir',
            'notes' => 'nullable|string',
        ]);

        if (Attendance::where('employee_id', $data['employee_id'])->where('date', $data['date'])->exists()) {
            return back()
                ->withInput()
                ->withErrors(['employee_id' => 'Absensi karyawan ini pada tanggal tersebut sudah tercatat.']);
        }

        Attendance::create($data);

        return redirect()
            ->route('kepegawaian', ['tanggal' => $data['date'], 'tab' => 'absensi'])
            ->with('status', 'Absensi karyawan berhasil dicatat.');
    }

    public function updateAttendance(Request $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update($request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|string|max:10',
            'check_out' => 'nullable|string|max:10',
            'status' => 'required|in:hadir,izin,cuti,tidak_hadir',
            'notes' => 'nullable|string',
        ]));

        return redirect()
            ->route('kepegawaian', ['tanggal' => $attendance->date->format('Y-m-d'), 'tab' => 'absensi'])
            ->with('status', 'Absensi berhasil diperbarui.');
    }

    public function destroyAttendance(Attendance $attendance): RedirectResponse
    {
        $date = $attendance->date->format('Y-m-d');
        $attendance->delete();

        return redirect()
            ->route('kepegawaian', ['tanggal' => $date, 'tab' => 'absensi'])
            ->with('status', 'Absensi dihapus.');
    }
}
