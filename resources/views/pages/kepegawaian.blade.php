<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen kepegawaian — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'kepegawaian'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Manajemen kepegawaian</h1>
          <p class="eb-page-desc">
            Data karyawan, tutor, dan staf administrasi.
          </p>
        </div>
      </div>

      <div class="eb-subnav-wrap">
        <ul class="nav eb-subnav" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-karyawan"
              data-bs-toggle="tab"
              data-bs-target="#pane-karyawan"
              type="button"
              role="tab"
            >
              <i class="bi bi-person-lines-fill me-1"></i> Daftar karyawan
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-tutor"
              data-bs-toggle="tab"
              data-bs-target="#pane-tutor"
              type="button"
              role="tab"
            >
              <i class="bi bi-mortarboard me-1"></i> Tutor &amp; pengajar
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-absensi"
              data-bs-toggle="tab"
              data-bs-target="#pane-absensi"
              type="button"
              role="tab"
            >
              <i class="bi bi-calendar-check me-1"></i> Absensi &amp; jadwal
            </button>
          </li>
        </ul>
      </div>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="pane-karyawan" role="tabpanel">
          <div
            class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 rounded-3 border"
            style="background: var(--eb-surface); border-color: var(--eb-border) !important"
          >
            <h2 class="h6 fw-bold mb-0 px-1">Daftar karyawan &amp; staf</h2>
            <button
              class="btn btn-eb btn-sm"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#modal-tambah-karyawan"
            >
              <i class="bi bi-plus-lg me-1"></i>Tambah karyawan
            </button>
          </div>
          <div class="card table-card border-0">
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Nama</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($employees as $employee)
                  @php
                    $initials = collect(explode(' ', $employee->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
                    $avatarClass = ['eb-avatar--primary', 'eb-avatar--secondary', 'eb-avatar--success'][$loop->index % 3];
                  @endphp
                  <tr>
                    <td class="ps-3">
                      <div class="d-flex align-items-center gap-2">
                        <span class="eb-avatar eb-avatar-sm {{ $avatarClass }}">{{ strtoupper($initials) }}</span>
                        <div>
                          <div class="fw-semibold">{{ $employee->name }}</div>
                          <small class="text-muted">NIK: {{ $employee->nik }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>
                      @if ($employee->status === 'aktif')
                        <span class="badge rounded-pill badge-soft-success">Aktif</span>
                      @elseif (in_array($employee->status, ['cuti', 'izin']))
                        <span class="badge rounded-pill badge-soft-warning">{{ ucfirst($employee->status) }}</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst($employee->status) }}</span>
                      @endif
                    </td>
                    <td>{{ $employee->joined_at?->locale('id')->translatedFormat('M Y') ?? '—' }}</td>
                    <td class="text-center pe-3">
                      <button
                        class="btn btn-sm btn-light border me-1"
                        type="button"
                        title="Edit"
                        data-eb-modal="modal-tambah-karyawan"
                        data-eb-action="{{ route('kepegawaian.employees.update', $employee) }}"
                        data-eb-title="Ubah karyawan"
                        data-eb-edit="{{ json_encode(['nik' => $employee->nik, 'name' => $employee->name, 'position' => $employee->position, 'department' => $employee->department, 'joined_at' => $employee->joined_at?->format('Y-m-d')]) }}"
                      ><i class="bi bi-pencil"></i></button>
                      <form
                        method="post"
                        action="{{ route('kepegawaian.employees.destroy', $employee) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus karyawan ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light border text-danger" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada karyawan.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-tutor" role="tabpanel">
          <div
            class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 rounded-3 border"
            style="background: var(--eb-surface); border-color: var(--eb-border) !important"
          >
            <h2 class="h6 fw-bold mb-0 px-1">Daftar tutor &amp; pengajar</h2>
            <button
              class="btn btn-eb btn-sm"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#modal-tambah-tutor"
            >
              <i class="bi bi-plus-lg me-1"></i>Tambah tutor
            </button>
          </div>
          <div class="card table-card border-0">
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Nama</th>
                    <th>Mata pelajaran</th>
                    <th>Pengalaman</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($tutors as $tutor)
                  @php
                    $initials = collect(explode(' ', $tutor->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
                    $avatarClass = ['eb-avatar--primary', 'eb-avatar--secondary', 'eb-avatar--success'][$loop->index % 3];
                  @endphp
                  <tr>
                    <td class="ps-3">
                      <div class="d-flex align-items-center gap-2">
                        <span class="eb-avatar eb-avatar-sm {{ $avatarClass }}">{{ strtoupper($initials) }}</span>
                        <div>
                          <div class="fw-semibold">{{ $tutor->name }}</div>
                          <small class="text-muted">ID: {{ $tutor->code }}</small>
                        </div>
                      </div>
                    </td>
                    <td>{{ $tutor->subject }}</td>
                    <td>{{ $tutor->experience_years }} tahun</td>
                    <td>
                      @if ($tutor->status === 'aktif')
                        <span class="badge rounded-pill badge-soft-success">Aktif</span>
                      @elseif ($tutor->status === 'tidak_aktif')
                        <span class="badge rounded-pill badge-soft-warning">Tidak aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst(str_replace('_', ' ', $tutor->status)) }}</span>
                      @endif
                    </td>
                    <td>{{ $tutor->joined_at?->locale('id')->translatedFormat('M Y') ?? '—' }}</td>
                    <td class="text-center pe-3">
                      <button
                        class="btn btn-sm btn-light border me-1"
                        type="button"
                        title="Edit"
                        data-eb-modal="modal-tambah-tutor"
                        data-eb-action="{{ route('kepegawaian.tutors.update', $tutor) }}"
                        data-eb-title="Ubah tutor"
                        data-eb-edit="{{ json_encode(['code' => $tutor->code, 'name' => $tutor->name, 'subject' => $tutor->subject, 'experience_years' => $tutor->experience_years, 'joined_at' => $tutor->joined_at?->format('Y-m-d')]) }}"
                      ><i class="bi bi-pencil"></i></button>
                      <form
                        method="post"
                        action="{{ route('kepegawaian.tutors.destroy', $tutor) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus tutor ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light border text-danger" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada tutor.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-absensi" role="tabpanel">
          <h2 class="h6 fw-bold mb-3">Absensi &amp; jadwal kerja bulan ini</h2>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div class="eb-stat-icon text-success bg-success bg-opacity-10">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold text-success mb-1">{{ $hadirCount }} hari</h3>
                  <p class="text-muted small mb-0">Hadir</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div class="eb-stat-icon text-warning bg-warning bg-opacity-10">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold text-warning mb-1">{{ $izinCount }} hari</h3>
                  <p class="text-muted small mb-0">Izin / cuti</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div class="eb-stat-icon text-danger bg-danger bg-opacity-10">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold text-danger mb-1">{{ $tidakHadirCount }} hari</h3>
                  <p class="text-muted small mb-0">Tidak hadir</p>
                </div>
              </div>
            </div>
          </div>
          <div class="card table-card border-0">
            <div class="eb-table-toolbar">
              <h6 class="mb-0 fw-bold">Jadwal kerja hari ini</h6>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Nama</th>
                    <th>Jabatan</th>
                    <th>Jam masuk</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($attendancesToday as $attendance)
                  @php
                    $initials = collect(explode(' ', $attendance->employee->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
                    $avatarClass = ['eb-avatar--primary', 'eb-avatar--secondary', 'eb-avatar--success'][$loop->index % 3];
                  @endphp
                  <tr>
                    <td class="ps-3">
                      <div class="d-flex align-items-center gap-2">
                        <span class="eb-avatar eb-avatar-sm {{ $avatarClass }}">{{ strtoupper($initials) }}</span>
                        <span class="fw-semibold">{{ $attendance->employee->name }}</span>
                      </div>
                    </td>
                    <td>{{ $attendance->employee->position }}</td>
                    <td>{{ $attendance->check_in ?: '—' }}</td>
                    <td>
                      @if ($attendance->status === 'hadir')
                        <span class="badge badge-soft-success">Hadir</span>
                      @elseif (in_array($attendance->status, ['izin', 'cuti']))
                        <span class="badge badge-soft-warning">{{ ucfirst($attendance->status) }}</span>
                      @elseif ($attendance->status === 'tidak_hadir')
                        <span class="badge badge-soft-danger">Tidak hadir</span>
                      @else
                        <span class="badge text-secondary bg-light">{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada data absensi hari ini.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modal-tambah-karyawan" tabindex="-1" aria-hidden="true" data-default-title="Tambah karyawan">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('kepegawaian.employees.store') }}" data-store-action="{{ route('kepegawaian.employees.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nik-karyawan-input" class="form-label">NIK</label>
              <input type="text" class="form-control" id="nik-karyawan-input" name="nik" placeholder="2024004" required />
            </div>
            <div class="mb-3">
              <label for="nama-karyawan-input" class="form-label">Nama lengkap</label>
              <input
                type="text"
                class="form-control"
                id="nama-karyawan-input"
                name="name"
                placeholder="Masukkan nama lengkap"
                required
              />
            </div>
            <div class="mb-3">
              <label for="jabatan-select" class="form-label">Jabatan</label>
              <input type="text" class="form-control" id="jabatan-select" name="position" placeholder="Administrasi" required />
            </div>
            <div class="mb-3">
              <label for="departemen-select" class="form-label">Departemen</label>
              <input type="text" class="form-control" id="departemen-select" name="department" placeholder="Umum" required />
            </div>
            <div class="mb-0">
              <label for="tanggal-bergabung-input" class="form-label">Tanggal bergabung</label>
              <input type="date" class="form-control" id="tanggal-bergabung-input" name="joined_at" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan karyawan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modal-tambah-tutor" tabindex="-1" aria-hidden="true" data-default-title="Tambah tutor">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('kepegawaian.tutors.store') }}" data-store-action="{{ route('kepegawaian.tutors.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah tutor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="kode-tutor-input" class="form-label">Kode tutor</label>
              <input type="text" class="form-control" id="kode-tutor-input" name="code" placeholder="T004" required />
            </div>
            <div class="mb-3">
              <label for="nama-tutor-input" class="form-label">Nama lengkap</label>
              <input
                type="text"
                class="form-control"
                id="nama-tutor-input"
                name="name"
                placeholder="Masukkan nama lengkap"
                required
              />
            </div>
            <div class="mb-3">
              <label for="mapel-select" class="form-label">Mata pelajaran</label>
              <input type="text" class="form-control" id="mapel-select" name="subject" placeholder="Matematika" required />
            </div>
            <div class="mb-3">
              <label for="pengalaman-input" class="form-label">Pengalaman mengajar (tahun)</label>
              <input type="number" class="form-control" id="pengalaman-input" name="experience_years" placeholder="3" min="0" />
            </div>
            <div class="mb-0">
              <label for="tanggal-tutor-bergabung-input" class="form-label">Tanggal bergabung</label>
              <input type="date" class="form-control" id="tanggal-tutor-bergabung-input" name="joined_at" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan tutor</button>
          </div>
        </form>
      </div>
    </div>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-crud.js') }}"></script>
  </body>
</html>
