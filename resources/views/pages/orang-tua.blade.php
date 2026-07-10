<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laporan harian — Portal wali {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
    <style>
      .eb-ortu-feed .card {
        border: 1px solid var(--eb-border);
        border-radius: var(--eb-radius);
        box-shadow: var(--eb-shadow-sm);
        transition: box-shadow 0.15s ease;
      }
      .eb-ortu-feed .card:hover {
        box-shadow: var(--eb-shadow);
      }
      .eb-ortu-date {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--eb-text-muted);
      }
      .eb-ortu-thumb {
        background: linear-gradient(145deg, #e8eef7, #f1f5f9);
        border: 1px solid var(--eb-border);
        cursor: default;
      }
      .eb-ortu-thumb--video {
        background: linear-gradient(145deg, #1e3a5f, #334155);
        color: rgba(255, 255, 255, 0.92);
        border-color: rgba(30, 58, 95, 0.35);
      }
    </style>
  </head>
  <body class="eb-app">
    <nav class="navbar navbar-dark navbar-eb shadow-sm">
      <div class="container-fluid px-3 px-md-4">
        <a
          class="navbar-brand py-1 mb-0"
          href="{{ route('orang-tua') }}"
          aria-label="{{ $site->admin_brand }} — portal wali"
        >
          <img
            class="eb-logo"
            src="{{ $site->logoUrl() }}"
            alt="{{ $site->site_name }}"
            width="220"
            height="66"
            decoding="async"
          />
        </a>
        <div class="d-flex align-items-center gap-2">
          <span class="eb-user-pill d-none d-sm-inline-flex align-items-center gap-1 mb-0 small">
            <i class="bi bi-person-hearts" aria-hidden="true"></i> {{ $guardianName }}
          </span>
          <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Keluar</a>
        </div>
      </div>
    </nav>

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Laporan harian dari guru</h1>
          <p class="eb-page-desc mb-3">
            Ringkasan sesi, tugas, dan <strong>foto / video</strong> yang guru lampirkan — hanya laporan
            terbit untuk kelas anak Anda.
          </p>
          <div class="row g-2 align-items-end">
            <div class="col-md-6 col-lg-4">
              <form method="get" action="{{ route('orang-tua') }}">
                <label for="pilih-anak" class="form-label small fw-semibold mb-1">Anak</label>
                <select id="pilih-anak" name="siswa" class="form-select form-select-sm" onchange="this.form.submit()">
                  @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected($student->id == $selectedStudentId)>
                      {{ $student->name }} — {{ $student->kelompok?->name ?? 'Tanpa kelompok' }}
                    </option>
                  @endforeach
                </select>
              </form>
            </div>
            <div class="col-md-6 col-lg-8 text-md-end">
              <a href="{{ route('akademik') }}" class="btn btn-sm btn-outline-secondary eb-doc-link"
                >Lihat sisi guru (admin)</a
              >
            </div>
          </div>
        </div>
      </div>

      @if ($selectedStudentId && ($attendanceSummary['total'] ?? 0) > 0)
      <div class="card border-0 mb-4">
        <div class="card-body">
          <h2 class="h6 fw-bold mb-3"><i class="bi bi-calendar-check me-1"></i> Ringkasan kehadiran bulan ini</h2>
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <div class="text-center p-3 rounded" style="background: var(--eb-surface)">
                <div class="h4 fw-bold text-success mb-0">{{ $attendanceSummary['hadir'] }}</div>
                <div class="small text-muted">Hadir</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center p-3 rounded" style="background: var(--eb-surface)">
                <div class="h4 fw-bold text-warning mb-0">{{ $attendanceSummary['izin'] }}</div>
                <div class="small text-muted">Izin</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center p-3 rounded" style="background: var(--eb-surface)">
                <div class="h4 fw-bold text-info mb-0">{{ $attendanceSummary['sakit'] }}</div>
                <div class="small text-muted">Sakit</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="text-center p-3 rounded" style="background: var(--eb-surface)">
                <div class="h4 fw-bold text-danger mb-0">{{ $attendanceSummary['alpha'] }}</div>
                <div class="small text-muted">Alpha</div>
              </div>
            </div>
          </div>
          <p class="small text-muted mb-0 mt-3">
            Total {{ $attendanceSummary['total'] }} pertemuan tercatat bulan {{ now()->locale('id')->translatedFormat('F Y') }}.
          </p>
        </div>
      </div>
      @endif

      <p class="small text-muted mb-3">
        <i class="bi bi-funnel me-1"></i> Menampilkan laporan yang statusnya <strong>terbit</strong> untuk
        kelas terkait.
      </p>

      <div class="eb-ortu-feed d-flex flex-column gap-3">
        @forelse ($reports as $report)
        <article class="card">
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
              <div>
                <p class="eb-ortu-date mb-1">{{ $report->date->locale('id')->translatedFormat('d M Y') }}</p>
                <h2 class="h6 fw-bold mb-0">{{ $report->subject?->name ?? '—' }} · {{ $report->kelompok?->name ?? '—' }}</h2>
              </div>
              <span class="badge badge-eb rounded-pill align-self-start">Dari guru</span>
            </div>
            <p class="small text-muted mb-2">
              <i class="bi bi-person-badge me-1"></i>Guru: <strong>{{ $report->tutor?->name ?? '—' }}</strong>
            </p>
            <p class="mb-2">{{ $report->summary }}</p>
            @if ($report->media->isNotEmpty())
            <div class="mb-3">
              <p class="small fw-semibold mb-2 text-muted">
                <i class="bi bi-images me-1"></i>Lampiran (foto &amp; video dari guru)
              </p>
              <div class="row g-2">
                @foreach ($report->media->where('type', 'photo') as $photo)
                <div class="col-6 col-md-4">
                  <a href="{{ $photo->url() }}" target="_blank" rel="noopener" class="d-block ratio ratio-4x3 rounded-3 overflow-hidden eb-ortu-thumb">
                    <img src="{{ $photo->url() }}" alt="{{ $photo->original_name }}" class="w-100 h-100" style="object-fit:cover" />
                  </a>
                </div>
                @endforeach
                @foreach ($report->media->where('type', 'video') as $video)
                <div class="col-12 {{ $report->media->where('type', 'photo')->count() > 0 ? 'col-md-6' : 'col-sm-8 col-md-6' }}">
                  <div class="ratio ratio-16x9 rounded-3 overflow-hidden eb-ortu-thumb eb-ortu-thumb--video">
                    <video src="{{ $video->url() }}" controls class="w-100 h-100" style="object-fit:cover" preload="metadata">
                      Browser Anda tidak mendukung pemutaran video.
                    </video>
                  </div>
                  @if ($video->original_name)
                  <p class="small text-muted mb-0 mt-1">{{ $video->original_name }}</p>
                  @endif
                </div>
                @endforeach
              </div>
            </div>
            @elseif ($report->photo_count > 0 || $report->video_count > 0)
            <div class="mb-3">
              <p class="small fw-semibold mb-2 text-muted">
                <i class="bi bi-images me-1"></i>Lampiran (foto &amp; video dari guru)
              </p>
              <div class="row g-2">
                @for ($i = 0; $i < min($report->photo_count, 2); $i++)
                <div class="col-6 col-md-4">
                  <div class="ratio ratio-4x3 rounded-3 eb-ortu-thumb d-flex align-items-center justify-content-center">
                    <span class="small text-center px-2 text-muted"
                      ><i class="bi bi-image d-block fs-3 mb-1"></i>Foto kegiatan {{ $i + 1 }}</span
                    >
                  </div>
                </div>
                @endfor
                @if ($report->video_count > 0)
                <div class="col-12 {{ $report->photo_count > 0 ? 'col-md-4' : 'col-sm-8 col-md-6' }}">
                  <div class="ratio ratio-16x9 rounded-3 eb-ortu-thumb eb-ortu-thumb--video d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-play-circle fs-2 mb-1" aria-hidden="true"></i>
                    <span class="small text-center px-2">
                      {{ $report->video_count > 1 ? $report->video_count . ' video sesi' : 'Cuplikan video sesi' }}
                    </span>
                  </div>
                </div>
                @endif
              </div>
              <p class="small text-muted mb-0 mt-1">
                Di aplikasi nyata, ketuk untuk memperbesar foto atau memutar video.
              </p>
            </div>
            @endif
            @if ($report->homework)
            <div class="rounded-3 p-3 small" style="background: var(--eb-surface-2); border: 1px dashed var(--eb-border)">
              <span class="fw-semibold text-body"><i class="bi bi-journal-text me-1"></i>Tugas / belajar di rumah</span>
              <p class="mb-0 mt-1 text-muted">{{ $report->homework }}</p>
            </div>
            @endif
          </div>
        </article>
        @empty
        <div class="card">
          <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
            Belum ada laporan terbit untuk kelas anak ini.
          </div>
        </div>
        @endforelse
      </div>

      <p class="text-center text-muted small mt-4 mb-0">
        Ingin mengelola laporan? Gunakan akun staf di
        <a href="{{ route('login') }}" class="fw-semibold">halaman masuk</a>.
      </p>
    </main>

    @include('partials.footer-scripts')
  </body>
</html>
