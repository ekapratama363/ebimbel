<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen akademik — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'akademik'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @php
        $canAkademik = [
          'create' => $can('akademik.create'),
          'edit' => $can('akademik.edit'),
          'delete' => $can('akademik.delete'),
          'publish' => $can('akademik.publish'),
        ];
      @endphp
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Manajemen akademik</h1>
          <p class="eb-page-desc">
            Program, jadwal, jurnal mengajar, dan laporan harian untuk orang tua.
          </p>
        </div>
      </div>

      <div class="eb-subnav-wrap">
        <ul class="nav eb-subnav" id="akademikTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-jenjang"
              data-bs-toggle="tab"
              data-bs-target="#pane-jenjang"
              type="button"
              role="tab"
            >
              <i class="bi bi-mortarboard me-1"></i> Jenjang
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-program"
              data-bs-toggle="tab"
              data-bs-target="#pane-program"
              type="button"
              role="tab"
            >
              <i class="bi bi-journal-bookmark me-1"></i> Program
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-map"
              data-bs-toggle="tab"
              data-bs-target="#pane-map"
              type="button"
              role="tab"
            >
              <i class="bi bi-book me-1"></i> Mata pelajaran
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-jadwal"
              data-bs-toggle="tab"
              data-bs-target="#pane-jadwal"
              type="button"
              role="tab"
            >
              <i class="bi bi-calendar3 me-1"></i> Jadwal
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-jurnal"
              data-bs-toggle="tab"
              data-bs-target="#pane-jurnal"
              type="button"
              role="tab"
            >
              <i class="bi bi-file-earmark-text me-1"></i> Jurnal mengajar
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-laporan-ortu"
              data-bs-toggle="tab"
              data-bs-target="#pane-laporan-ortu"
              type="button"
              role="tab"
            >
              <i class="bi bi-people me-1"></i> Laporan harian (orang tua)
            </button>
          </li>
        </ul>
      </div>

      <div class="tab-content" id="akademikTabsContent">
        <div class="tab-pane fade" id="pane-jenjang" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Master jenjang pendidikan</span>
              @if ($canAkademik['create'])
              <button
                type="button"
                class="btn btn-sm btn-eb"
                data-bs-toggle="modal"
                data-bs-target="#modalJenjang"
              >
                <i class="bi bi-plus-lg"></i> Tambah jenjang
              </button>
              @endif
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama jenjang</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($jenjangs as $jenjang)
                  <tr>
                    <td><code>{{ $jenjang->code }}</code></td>
                    <td>{{ $jenjang->name }}</td>
                    <td>{{ $jenjang->sort_order }}</td>
                    <td>
                      @if ($jenjang->status === 'aktif')
                        <span class="badge badge-eb rounded-pill">Aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst($jenjang->status) }}</span>
                      @endif
                    </td>
                    <td class="text-end">
                      @if ($canAkademik['edit'])
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalJenjang"
                        data-eb-action="{{ route('akademik.jenjangs.update', $jenjang) }}"
                        data-eb-title="Ubah jenjang"
                        data-eb-edit="{{ json_encode(['code' => $jenjang->code, 'name' => $jenjang->name, 'sort_order' => $jenjang->sort_order, 'status' => $jenjang->status]) }}"
                      >Ubah</button>
                      @endif
                      @if ($canAkademik['delete'])
                      <form
                        method="post"
                        action="{{ route('akademik.jenjangs.destroy', $jenjang) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus jenjang ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">
                          Hapus
                        </button>
                      </form>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada jenjang.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade show active" id="pane-program" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Daftar program bimbel</span>
              <button
                type="button"
                class="btn btn-sm btn-eb"
                data-bs-toggle="modal"
                data-bs-target="#modalTambahProgram"
              >
                <i class="bi bi-plus-lg"></i> Tambah program
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama program</th>
                    <th>Jenjang</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($programs as $program)
                  <tr>
                    <td><code>{{ $program->code }}</code></td>
                    <td>{{ $program->name }}</td>
                    <td>{{ $program->jenjang?->name ?? '—' }}</td>
                    <td>
                      @if ($program->status === 'aktif')
                        <span class="badge badge-eb rounded-pill">Aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst($program->status) }}</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalTambahProgram"
                        data-eb-action="{{ route('akademik.programs.update', $program) }}"
                        data-eb-title="Ubah program"
                        data-eb-edit="{{ json_encode(['code' => $program->code, 'name' => $program->name, 'jenjang_id' => $program->jenjang_id]) }}"
                      >Ubah</button>
                      <form
                        method="post"
                        action="{{ route('akademik.programs.destroy', $program) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus program ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada program.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-map" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Master mata pelajaran</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalMapel">
                <i class="bi bi-plus-lg"></i> Tambah mapel
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Mata pelajaran</th>
                    <th>Kelompok</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($subjects as $subject)
                  <tr>
                    <td><code>{{ $subject->code }}</code></td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->kelompok }}</td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalMapel"
                        data-eb-action="{{ route('akademik.subjects.update', $subject) }}"
                        data-eb-title="Ubah mata pelajaran"
                        data-eb-edit="{{ json_encode(['code' => $subject->code, 'name' => $subject->name, 'kelompok' => $subject->kelompok]) }}"
                      >Ubah</button>
                      <form
                        method="post"
                        action="{{ route('akademik.subjects.destroy', $subject) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus mata pelajaran ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada mata pelajaran.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-jadwal" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Jadwal mengajar / kelas</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalJadwal">
                <i class="bi bi-plus-lg"></i> Tambah jadwal
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Kelas / program</th>
                    <th>Mapel</th>
                    <th>Tentor</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($schedules as $schedule)
                  <tr>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ $schedule->start_time }}–{{ $schedule->end_time }}</td>
                    <td>{{ $schedule->kelompok?->name ?? '—' }}</td>
                    <td>{{ $schedule->subject?->name ?? '—' }}</td>
                    <td>{{ $schedule->tutor?->name ?? '—' }}</td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalJadwal"
                        data-eb-action="{{ route('akademik.schedules.update', $schedule) }}"
                        data-eb-title="Ubah jadwal"
                        data-eb-edit="{{ json_encode(['day' => $schedule->day, 'start_time' => $schedule->start_time, 'end_time' => $schedule->end_time, 'kelompok_id' => $schedule->kelompok_id, 'subject_id' => $schedule->subject_id, 'tutor_id' => $schedule->tutor_id]) }}"
                      >Ubah</button>
                      <form
                        method="post"
                        action="{{ route('akademik.schedules.destroy', $schedule) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus jadwal ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada jadwal.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-jurnal" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Jurnal mengajar</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalJurnal">
                <i class="bi bi-plus-lg"></i> Entri jurnal
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Sesi</th>
                    <th>Materi / capaian</th>
                    <th>Catatan</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($journals as $journal)
                  <tr>
                    <td>{{ $journal->date->locale('id')->translatedFormat('d M Y') }}</td>
                    <td>{{ $journal->subject?->name ?? '—' }} — {{ $journal->kelompok?->name ?? '—' }}</td>
                    <td>{{ $journal->material }}</td>
                    <td class="small text-muted">{{ $journal->notes ?: '—' }}</td>
                    <td class="text-end">
                      <button type="button" class="btn btn-link btn-sm p-0 me-2 fw-semibold">Detail</button>
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 fw-semibold"
                        data-eb-modal="modalJurnal"
                        data-eb-action="{{ route('akademik.journals.update', $journal) }}"
                        data-eb-title="Ubah jurnal mengajar"
                        data-eb-edit="{{ json_encode(['date' => $journal->date->format('Y-m-d'), 'kelompok_id' => $journal->kelompok_id, 'subject_id' => $journal->subject_id, 'tutor_id' => $journal->tutor_id, 'material' => $journal->material, 'notes' => $journal->notes]) }}"
                      >Ubah</button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada jurnal mengajar.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-laporan-ortu" role="tabpanel">
          <div class="alert alert-light border small mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: var(--eb-border) !important; background: var(--eb-surface-2)">
            <span class="mb-0">
              <i class="bi bi-info-circle text-primary me-1"></i>
              Guru menulis ringkasan sesi (boleh melampirkan <strong>banyak foto &amp; video</strong>) yang
              <strong>diterbitkan</strong> ke akun wali.
            </span>
            <a href="{{ route('orang-tua') }}" class="btn btn-sm btn-outline-primary flex-shrink-0"
              >Pratinjau portal orang tua</a
            >
          </div>
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Laporan harian untuk orang tua</span>
              <button
                type="button"
                class="btn btn-sm btn-eb"
                data-bs-toggle="modal"
                data-bs-target="#modalLaporanOrtu"
              >
                <i class="bi bi-plus-lg"></i> Buat laporan harian
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Ringkasan</th>
                    <th>Lampiran</th>
                    <th>Terbit ke wali</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($reports as $report)
                  <tr>
                    <td>{{ $report->date->locale('id')->translatedFormat('d M Y') }}</td>
                    <td>{{ $report->kelompok?->name ?? '—' }}</td>
                    <td>{{ $report->subject?->name ?? '—' }}</td>
                    <td>{{ $report->tutor?->name ?? '—' }}</td>
                    <td class="small text-muted">{{ Str::limit($report->summary, 60) }}</td>
                    <td class="small">
                      @php $photoTotal = $report->media->where('type', 'photo')->count(); $videoTotal = $report->media->where('type', 'video')->count(); @endphp
                      @if ($photoTotal > 0 || $videoTotal > 0)
                        @if ($photoTotal > 0)<span class="text-nowrap">{{ $photoTotal }} foto</span>@if ($videoTotal > 0), @endif @endif
                        @if ($videoTotal > 0)<span class="text-nowrap">{{ $videoTotal }} video</span>@endif
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>
                      @if ($report->status === 'published')
                        <span class="badge rounded-pill badge-soft-success">Ya</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">Draf</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalLaporanOrtu"
                        data-eb-action="{{ route('akademik.reports.update', $report) }}"
                        data-eb-title="Ubah laporan harian"
                        data-eb-edit="{{ json_encode(['date' => $report->date->format('Y-m-d'), 'kelompok_id' => $report->kelompok_id, 'subject_id' => $report->subject_id, 'tutor_id' => $report->tutor_id, 'summary' => $report->summary, 'homework' => $report->homework, 'status' => $report->status]) }}"
                      >Ubah</button>
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold btn-report-media"
                        data-bs-toggle="modal"
                        data-bs-target="#modalLaporanLampiran"
                        data-report-label="{{ $report->date->locale('id')->translatedFormat('d M Y') }} · {{ $report->kelompok?->name }}"
                        data-media-action="{{ route('akademik.reports.media.store', $report) }}"
                        data-media-items="{{ json_encode($report->media->map(fn ($m) => ['id' => $m->id, 'type' => $m->type, 'url' => $m->url(), 'name' => $m->original_name, 'destroy' => route('akademik.reports.media.destroy', [$report, $m])])->values()) }}"
                      >Lampiran</button>
                      @if ($report->status === 'published')
                        <form
                          method="post"
                          action="{{ route('akademik.reports.unpublish', $report) }}"
                          class="d-inline"
                        >
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-link btn-sm p-0 fw-semibold">Tarik</button>
                        </form>
                      @else
                        <form
                          method="post"
                          action="{{ route('akademik.reports.publish', $report) }}"
                          class="d-inline"
                        >
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-link btn-sm p-0 fw-semibold">Terbitkan</button>
                        </form>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada laporan harian.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modalJenjang" tabindex="-1" aria-hidden="true" data-default-title="Tambah jenjang">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('akademik.jenjangs.store') }}" data-store-action="{{ route('akademik.jenjangs.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah jenjang</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="jenjang-code">Kode jenjang</label>
              <input type="text" class="form-control" id="jenjang-code" name="code" placeholder="SD" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="jenjang-name">Nama jenjang</label>
              <input type="text" class="form-control" id="jenjang-name" name="name" placeholder="Sekolah Dasar" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="jenjang-sort">Urutan tampil</label>
              <input type="number" class="form-control" id="jenjang-sort" name="sort_order" min="0" value="0" />
            </div>
            <div class="mb-0">
              <label class="form-label" for="jenjang-status">Status</label>
              <select class="form-select" id="jenjang-status" name="status">
                <option value="aktif" selected>Aktif</option>
                <option value="nonaktif">Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalTambahProgram" tabindex="-1" aria-labelledby="lblProgram" aria-hidden="true" data-default-title="Tambah program">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('akademik.programs.store') }}" data-store-action="{{ route('akademik.programs.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title" id="lblProgram">Tambah program</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="program-code">Kode program</label>
              <input type="text" class="form-control" id="program-code" name="code" placeholder="PRG-03" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="program-name">Nama program</label>
              <input type="text" class="form-control" id="program-name" name="name" placeholder="Nama lengkap program" required />
            </div>
            <div class="mb-0">
              <label class="form-label" for="program-jenjang">Jenjang</label>
              <select class="form-select" id="program-jenjang" name="jenjang_id" required>
                <option value="" disabled selected>Pilih jenjang</option>
                @foreach ($jenjangs->where('status', 'aktif') as $jenjang)
                  <option value="{{ $jenjang->id }}">{{ $jenjang->code }} — {{ $jenjang->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalMapel" tabindex="-1" aria-hidden="true" data-default-title="Tambah mata pelajaran">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('akademik.subjects.store') }}" data-store-action="{{ route('akademik.subjects.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah mata pelajaran</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="subject-code">Kode mapel</label>
              <input type="text" class="form-control" id="subject-code" name="code" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="subject-name">Nama</label>
              <input type="text" class="form-control" id="subject-name" name="name" required />
            </div>
            <div class="mb-0">
              <label class="form-label" for="subject-kelompok">Kelompok</label>
              <select class="form-select" id="subject-kelompok" name="kelompok" required>
                <option value="Wajib">Wajib</option>
                <option value="Peminatan">Peminatan</option>
                <option value="Jurusan">Jurusan</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalJadwal" tabindex="-1" aria-hidden="true" data-default-title="Tambah jadwal">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="{{ route('akademik.schedules.store') }}" data-store-action="{{ route('akademik.schedules.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah jadwal</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="schedule-day">Hari</label>
                <select class="form-select" id="schedule-day" name="day" required>
                  <option value="Senin">Senin</option>
                  <option value="Selasa">Selasa</option>
                  <option value="Rabu">Rabu</option>
                  <option value="Kamis">Kamis</option>
                  <option value="Jumat">Jumat</option>
                  <option value="Sabtu">Sabtu</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="schedule-start">Waktu mulai</label>
                <input type="text" class="form-control" id="schedule-start" name="start_time" placeholder="16:00" required />
              </div>
              <div class="col-md-3">
                <label class="form-label" for="schedule-end">Waktu selesai</label>
                <input type="text" class="form-control" id="schedule-end" name="end_time" placeholder="18:00" required />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="schedule-kelompok">Program / kelas</label>
                <select class="form-select" id="schedule-kelompok" name="kelompok_id" required>
                  @foreach ($kelompoks as $kelompok)
                    <option value="{{ $kelompok->id }}">{{ $kelompok->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="schedule-subject">Mata pelajaran</label>
                <select class="form-select" id="schedule-subject" name="subject_id" required>
                  @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label" for="schedule-tutor">Tentor</label>
                <select class="form-select" id="schedule-tutor" name="tutor_id" required>
                  @foreach ($tutors as $tutor)
                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalJurnal" tabindex="-1" aria-hidden="true" data-default-title="Entri jurnal mengajar">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="{{ route('akademik.journals.store') }}" data-store-action="{{ route('akademik.journals.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Entri jurnal mengajar</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="journal-date">Tanggal</label>
                <input type="date" class="form-control" id="journal-date" name="date" required />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="journal-kelompok">Sesi / kelas</label>
                <select class="form-select" id="journal-kelompok" name="kelompok_id" required>
                  @foreach ($kelompoks as $kelompok)
                    <option value="{{ $kelompok->id }}">{{ $kelompok->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="journal-subject">Mata pelajaran</label>
                <select class="form-select" id="journal-subject" name="subject_id" required>
                  @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="journal-tutor">Tentor</label>
                <select class="form-select" id="journal-tutor" name="tutor_id">
                  <option value="">— Opsional —</option>
                  @foreach ($tutors as $tutor)
                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label" for="journal-material">Materi atau capaian pembelajaran</label>
                <input type="text" class="form-control" id="journal-material" name="material" required />
              </div>
              <div class="col-12 mb-0">
                <label class="form-label" for="journal-notes">Catatan untuk orang tua / internal</label>
                <textarea class="form-control" id="journal-notes" name="notes" rows="3"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalLaporanOrtu" tabindex="-1" aria-labelledby="lblLaporanOrtu" aria-hidden="true" data-default-title="Laporan harian untuk orang tua">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="{{ route('akademik.reports.store') }}" data-store-action="{{ route('akademik.reports.store') }}" class="modal-content" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="status" id="report-status" value="draft" />
          <div class="modal-header">
            <h2 class="modal-title" id="lblLaporanOrtu">Laporan harian untuk orang tua</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p class="small text-muted mb-3">
              Isian ini akan muncul di portal wali setelah diterbitkan. Hanya siswa di kelas terkait yang
              akan menerima laporan.
            </p>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="report-date">Tanggal sesi</label>
                <input type="date" class="form-control" id="report-date" name="date" required />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="report-kelompok">Kelas / kelompok</label>
                <select class="form-select" id="report-kelompok" name="kelompok_id" required>
                  @foreach ($kelompoks as $kelompok)
                    <option value="{{ $kelompok->id }}">{{ $kelompok->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="report-subject">Mata pelajaran</label>
                <select class="form-select" id="report-subject" name="subject_id" required>
                  @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="report-tutor">Guru / tentor</label>
                <select class="form-select" id="report-tutor" name="tutor_id">
                  <option value="">— Opsional —</option>
                  @foreach ($tutors as $tutor)
                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label" for="report-summary">Ringkasan kegiatan (untuk orang tua)</label>
                <textarea
                  class="form-control"
                  id="report-summary"
                  name="summary"
                  rows="3"
                  placeholder="Contoh: Hari ini membahas limit fungsi aljabar; siswa berlatih soal di kelas."
                  required
                ></textarea>
              </div>
              <div class="col-12">
                <label class="form-label" for="report-homework">Tugas / saran belajar di rumah (opsional)</label>
                <textarea
                  class="form-control"
                  id="report-homework"
                  name="homework"
                  rows="2"
                  placeholder="Contoh: Kerjakan latihan modul hal. 12–14."
                ></textarea>
              </div>
              <div class="col-12">
                <label class="form-label" for="report-photos">Foto kegiatan</label>
                <input type="file" class="form-control" id="report-photos" name="photos[]" accept="image/*" multiple />
                <div class="form-text">Bisa pilih banyak foto sekaligus. PNG/JPG/WebP, maks. 2 MB per file.</div>
              </div>
              <div class="col-12">
                <label class="form-label" for="report-videos">Video sesi</label>
                <input type="file" class="form-control" id="report-videos" name="videos[]" accept="video/*" multiple />
                <div class="form-text">MP4/MOV/WebM, maks. 2 MB per file. Tambah lampiran lagi lewat tombol <strong>Lampiran</strong> di tabel.</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" onclick="document.getElementById('report-status').value='draft'" class="btn btn-outline-primary">Simpan draf</button>
            <button type="submit" onclick="document.getElementById('report-status').value='published'" class="btn btn-eb">Terbitkan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="modalLaporanLampiran" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title">Lampiran laporan</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <p class="small text-muted" id="lampiran-report-label"></p>
            <div id="lampiran-current-list" class="mb-4"></div>
            <form method="post" id="formLampiranTambah" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="lampiran-photos">Tambah foto</label>
                <input type="file" class="form-control" id="lampiran-photos" name="photos[]" accept="image/*" multiple />
                <div class="form-text">PNG/JPG/WebP, maks. 2 MB per file.</div>
              </div>
              <div class="mb-3">
                <label class="form-label" for="lampiran-videos">Tambah video</label>
                <input type="file" class="form-control" id="lampiran-videos" name="videos[]" accept="video/*" multiple />
                <div class="form-text">MP4/MOV/WebM, maks. 2 MB per file.</div>
              </div>
              <button type="submit" class="btn btn-eb btn-sm">Unggah lampiran</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-crud.js') }}"></script>
    <script>
      document.querySelectorAll('.btn-report-media').forEach((btn) => {
        btn.addEventListener('click', () => {
          const label = document.getElementById('lampiran-report-label');
          const list = document.getElementById('lampiran-current-list');
          const form = document.getElementById('formLampiranTambah');
          const items = JSON.parse(btn.dataset.mediaItems || '[]');

          label.textContent = btn.dataset.reportLabel || '';
          form.action = btn.dataset.mediaAction || '#';
          form.reset();

          if (!items.length) {
            list.innerHTML = '<p class="small text-muted mb-0">Belum ada lampiran.</p>';
            return;
          }

          list.innerHTML = items.map((item) => {
            const preview = item.type === 'photo'
              ? `<img src="${item.url}" alt="" class="rounded" width="72" height="54" style="object-fit:cover" />`
              : `<span class="badge bg-dark"><i class="bi bi-play-btn me-1"></i>Video</span>`;
            return `
              <div class="d-flex align-items-center justify-content-between gap-2 border rounded p-2 mb-2">
                <div class="d-flex align-items-center gap-2">${preview}<span class="small">${item.name || 'Lampiran'}</span></div>
                <form method="post" action="${item.destroy}" onsubmit="return confirm('Hapus lampiran ini?')">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                  <input type="hidden" name="_method" value="DELETE" />
                  <button type="submit" class="btn btn-link btn-sm text-danger p-0">Hapus</button>
                </form>
              </div>`;
          }).join('');
        });
      });
    </script>
  </body>
</html>
