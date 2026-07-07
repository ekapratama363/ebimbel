<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyReportMedia;
use App\Models\Kelompok;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TeachingJournal;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkademikController extends Controller
{
    public function index(): View
    {
        return view('pages.akademik', [
            'programs' => Program::orderBy('code')->get(),
            'subjects' => Subject::orderBy('code')->get(),
            'schedules' => Schedule::with(['kelompok', 'subject', 'tutor'])->get(),
            'journals' => TeachingJournal::with(['kelompok', 'subject', 'tutor'])->orderByDesc('date')->get(),
            'reports' => DailyReport::with(['kelompok', 'subject', 'tutor', 'media'])->orderByDesc('date')->get(),
            'kelompoks' => Kelompok::orderBy('code')->get(),
            'tutors' => Tutor::where('status', 'aktif')->orderBy('name')->get(),
        ]);
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:programs,code',
            'name' => 'required|string|max:255',
            'jenjang' => 'required|string|max:50',
        ]);
        $data['status'] = 'aktif';
        Program::create($data);

        return back()->with('status', 'Program berhasil ditambahkan.');
    }

    public function updateProgram(Request $request, Program $program): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:programs,code,'.$program->id,
            'name' => 'required|string|max:255',
            'jenjang' => 'required|string|max:50',
        ]);
        $program->update($data);

        return back()->with('status', 'Program berhasil diperbarui.');
    }

    public function destroyProgram(Program $program): RedirectResponse
    {
        $program->delete();

        return back()->with('status', 'Program dihapus.');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:255',
            'kelompok' => 'required|string|max:50',
        ]);
        Subject::create($data);

        return back()->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function updateSubject(Request $request, Subject $subject): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,'.$subject->id,
            'name' => 'required|string|max:255',
            'kelompok' => 'required|string|max:50',
        ]);
        $subject->update($data);

        return back()->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroySubject(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return back()->with('status', 'Mata pelajaran dihapus.');
    }

    public function storeSchedule(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'day' => 'required|string|max:20',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'required|exists:tutors,id',
        ]);
        Schedule::create($data);

        return back()->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function updateSchedule(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'day' => 'required|string|max:20',
            'start_time' => 'required|string|max:10',
            'end_time' => 'required|string|max:10',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'required|exists:tutors,id',
        ]);
        $schedule->update($data);

        return back()->with('status', 'Jadwal berhasil diperbarui.');
    }

    public function destroySchedule(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return back()->with('status', 'Jadwal dihapus.');
    }

    public function storeJournal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => 'required|date',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'nullable|exists:tutors,id',
            'material' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        TeachingJournal::create($data);

        return back()->with('status', 'Jurnal mengajar berhasil ditambahkan.');
    }

    public function updateJournal(Request $request, TeachingJournal $journal): RedirectResponse
    {
        $data = $request->validate([
            'date' => 'required|date',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'nullable|exists:tutors,id',
            'material' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $journal->update($data);

        return back()->with('status', 'Jurnal mengajar berhasil diperbarui.');
    }

    public function storeReport(Request $request): RedirectResponse
    {
        $this->validateReportFiles($request);

        $data = $request->validate([
            'date' => 'required|date',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'nullable|exists:tutors,id',
            'summary' => 'required|string',
            'homework' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);
        $data['photo_count'] = 0;
        $data['video_count'] = 0;
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $report = DailyReport::create($data);
        $this->attachReportFiles($report, $request);

        return back()->with('status', 'Laporan harian berhasil dibuat.');
    }

    public function updateReport(Request $request, DailyReport $report): RedirectResponse
    {
        $this->validateReportFiles($request);

        $data = $request->validate([
            'date' => 'required|date',
            'kelompok_id' => 'required|exists:kelompoks,id',
            'subject_id' => 'required|exists:subjects,id',
            'tutor_id' => 'nullable|exists:tutors,id',
            'summary' => 'required|string',
            'homework' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);
        if ($data['status'] === 'published' && $report->status !== 'published') {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }
        $report->update($data);
        $this->attachReportFiles($report, $request);

        return back()->with('status', 'Laporan harian berhasil diperbarui.');
    }

    public function uploadReportMedia(Request $request, DailyReport $report): RedirectResponse
    {
        $this->validateReportFiles($request);
        $this->attachReportFiles($report, $request);

        return back()->with('status', 'Lampiran laporan berhasil ditambahkan.');
    }

    public function destroyReportMedia(DailyReport $report, DailyReportMedia $media): RedirectResponse
    {
        if ($media->daily_report_id !== $report->id) {
            abort(404);
        }

        $media->deleteFile();
        $media->delete();
        $report->syncMediaCounts();

        return back()->with('status', 'Lampiran dihapus.');
    }

    public function publishReport(DailyReport $report): RedirectResponse
    {
        $report->update(['status' => 'published', 'published_at' => now()]);

        return back()->with('status', 'Laporan diterbitkan ke portal wali.');
    }

    public function unpublishReport(DailyReport $report): RedirectResponse
    {
        $report->update(['status' => 'draft', 'published_at' => null]);

        return back()->with('status', 'Laporan ditarik dari portal wali.');
    }

    private function validateReportFiles(Request $request): void
    {
        $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:5120',
            'videos' => 'nullable|array',
            'videos.*' => 'file|mimetypes:video/mp4,video/quicktime,video/webm,video/x-msvideo|max:51200',
        ]);
    }

    private function attachReportFiles(DailyReport $report, Request $request): void
    {
        $sort = (int) $report->media()->max('sort_order');

        foreach ($request->file('photos', []) as $photo) {
            $sort++;
            $path = 'storage/'.$photo->store('reports/'.$report->id.'/photos', 'public');
            $report->media()->create([
                'type' => 'photo',
                'file_path' => $path,
                'original_name' => $photo->getClientOriginalName(),
                'sort_order' => $sort,
            ]);
        }

        foreach ($request->file('videos', []) as $video) {
            $sort++;
            $path = 'storage/'.$video->store('reports/'.$report->id.'/videos', 'public');
            $report->media()->create([
                'type' => 'video',
                'file_path' => $path,
                'original_name' => $video->getClientOriginalName(),
                'sort_order' => $sort,
            ]);
        }

        if ($request->hasFile('photos') || $request->hasFile('videos')) {
            $report->syncMediaCounts();
        }
    }
}
