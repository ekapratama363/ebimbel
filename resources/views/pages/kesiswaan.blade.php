<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen kesiswaan — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'kesiswaan'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Manajemen kesiswaan</h1>
          <p class="eb-page-desc">
            Kelompok/kelas, data siswa, dan wali — sesuai cakupan modul M-01.
          </p>
        </div>
      </div>

      <div class="eb-subnav-wrap">
        <ul class="nav eb-subnav" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-kelompok"
              data-bs-toggle="tab"
              data-bs-target="#pane-kelompok"
              type="button"
              role="tab"
            >
              <i class="bi bi-grid-3x3-gap me-1"></i> Kelompok &amp; kelas
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-siswa"
              data-bs-toggle="tab"
              data-bs-target="#pane-siswa"
              type="button"
              role="tab"
            >
              <i class="bi bi-person-lines-fill me-1"></i> Data siswa
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-wali"
              data-bs-toggle="tab"
              data-bs-target="#pane-wali"
              type="button"
              role="tab"
            >
              <i class="bi bi-person-hearts me-1"></i> Wali &amp; kontak
            </button>
          </li>
        </ul>
      </div>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="pane-kelompok" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Kelompok / rombel / grup belajar</span>
              <button
                type="button"
                class="btn btn-sm btn-eb"
                data-bs-toggle="modal"
                data-bs-target="#modalKelompok"
              >
                <i class="bi bi-plus-lg"></i> Tambah kelompok
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama kelompok</th>
                    <th>Program</th>
                    <th>Kapasitas</th>
                    <th>Siswa terisi</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($kelompoks as $kelompok)
                  <tr>
                    <td><code>{{ $kelompok->code }}</code></td>
                    <td>{{ $kelompok->name }}</td>
                    <td>{{ $kelompok->program?->code }} {{ $kelompok->program?->name }}</td>
                    <td>{{ $kelompok->capacity }}</td>
                    <td>{{ $kelompok->students_count }}</td>
                    <td>
                      @if ($kelompok->students_count >= $kelompok->capacity)
                        <span class="badge badge-soft-warning rounded-pill">Penuh</span>
                      @elseif ($kelompok->status === 'aktif')
                        <span class="badge badge-eb rounded-pill">Aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst($kelompok->status) }}</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalKelompok"
                        data-eb-action="{{ route('kesiswaan.kelompoks.update', $kelompok) }}"
                        data-eb-title="Ubah kelompok"
                        data-eb-edit="{{ json_encode(['code' => $kelompok->code, 'name' => $kelompok->name, 'program_id' => $kelompok->program_id, 'capacity' => $kelompok->capacity]) }}"
                      >Ubah</button>
                      <form
                        method="post"
                        action="{{ route('kesiswaan.kelompoks.destroy', $kelompok) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus kelompok ini?')"
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
                    <td colspan="7" class="text-center text-muted py-4">Belum ada kelompok.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-siswa" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-3"
            >
              <div class="d-flex flex-wrap gap-2 align-items-center flex-grow-1">
                <span class="fw-semibold">Data siswa</span>
                <form method="get" action="{{ route('kesiswaan') }}" class="eb-input-icon-wrap flex-grow-1" style="max-width: 280px">
                  <i class="bi bi-search eb-input-icon" aria-hidden="true"></i>
                  <input
                    type="search"
                    class="form-control form-control-sm"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Cari nama / NIS…"
                    aria-label="Cari siswa"
                  />
                </form>
              </div>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalSiswa">
                <i class="bi bi-plus-lg"></i> Tambah siswa
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>NIS</th>
                    <th>Nama lengkap</th>
                    <th>Kelompok</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($students as $student)
                  <tr>
                    <td><code>{{ $student->card_number ?? '—' }}</code></td>
                    <td><code>{{ $student->nis }}</code></td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->kelompok?->code ?? '—' }}</td>
                    <td>
                      @if ($student->status === 'aktif')
                        <span class="badge badge-soft-success rounded-pill">Aktif</span>
                      @elseif ($student->status === 'cuti')
                        <span class="badge rounded-pill text-secondary bg-light">Cuti</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ ucfirst($student->status) }}</span>
                      @endif
                    </td>
                    <td class="small text-muted">
                      {{ $student->registered_at?->locale('id')->translatedFormat('d M Y') ?? '—' }}
                    </td>
                    <td class="text-end">
                      <a class="btn btn-link btn-sm p-0 me-2 fw-semibold" href="{{ route('kesiswaan.students.card', $student) }}" target="_blank" rel="noopener">Kartu</a>
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 fw-semibold"
                        data-eb-modal="modalSiswa"
                        data-eb-action="{{ route('kesiswaan.students.update', $student) }}"
                        data-eb-title="Ubah data siswa"
                        data-eb-edit="{{ json_encode(['nis' => $student->nis, 'name' => $student->name, 'kelompok_id' => $student->kelompok_id, 'status' => $student->status, 'birth_date' => $student->birth_date?->format('Y-m-d'), 'gender' => $student->gender, 'birth_place' => $student->birth_place, 'religion' => $student->religion, 'address' => $student->address]) }}"
                      >Ubah</button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                      @if ($search)
                        Tidak ada siswa yang cocok dengan pencarian.
                      @else
                        Belum ada data siswa.
                      @endif
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-wali" role="tabpanel">
          <div class="card table-card border-0">
            <div
              class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2"
            >
              <span class="fw-semibold">Wali siswa &amp; kontak darurat</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalWali">
                <i class="bi bi-plus-lg"></i> Hubungkan wali
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Siswa</th>
                    <th>Nama wali</th>
                    <th>Hubungan</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($guardians as $guardian)
                  <tr>
                    <td>{{ $guardian->student?->name ?? '—' }}</td>
                    <td>{{ $guardian->name }}</td>
                    <td>{{ $guardian->relationship }}</td>
                    <td>{{ $guardian->phone ?: '—' }}</td>
                    <td class="small">{{ $guardian->email ?: '—' }}</td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 fw-semibold"
                        data-eb-modal="modalWali"
                        data-eb-action="{{ route('kesiswaan.guardians.update', $guardian) }}"
                        data-eb-title="Ubah data wali"
                        data-eb-edit="{{ json_encode(['student_id' => $guardian->student_id, 'name' => $guardian->name, 'relationship' => $guardian->relationship, 'phone' => $guardian->phone, 'email' => $guardian->email]) }}"
                      >Ubah</button>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada data wali.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modalKelompok" tabindex="-1" aria-hidden="true" data-default-title="Tambah kelompok">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('kesiswaan.kelompoks.store') }}" data-store-action="{{ route('kesiswaan.kelompoks.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah kelompok</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="kelompok-code">Kode kelompok</label>
              <input type="text" class="form-control" id="kelompok-code" name="code" placeholder="KEL-C" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="kelompok-name">Nama kelompok</label>
              <input type="text" class="form-control" id="kelompok-name" name="name" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="kelompok-program">Program</label>
              <select class="form-select" id="kelompok-program" name="program_id" required>
                @foreach ($programs as $program)
                  <option value="{{ $program->id }}">{{ $program->code }} {{ $program->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-0">
              <label class="form-label" for="kelompok-capacity">Kapasitas</label>
              <input type="number" class="form-control" id="kelompok-capacity" name="capacity" min="1" value="25" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modalSiswa" tabindex="-1" aria-hidden="true" data-default-title="Tambah siswa">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="{{ route('kesiswaan.students.store') }}" data-store-action="{{ route('kesiswaan.students.store') }}" class="modal-content" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah siswa</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label" for="student-photo">Foto siswa</label>
                <input type="file" class="form-control" id="student-photo" name="photo" accept="image/*" />
                <div class="form-text">PNG/JPG/WebP, maks. 2 MB. Kosongkan jika belum ada.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-nis">NIS / nomor induk</label>
                <input type="text" class="form-control" id="student-nis" name="nis" required />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-name">Nama lengkap</label>
                <input type="text" class="form-control" id="student-name" name="name" required />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-kelompok">Kelompok</label>
                <select class="form-select" id="student-kelompok" name="kelompok_id" required>
                  @foreach ($kelompoks as $kelompok)
                    <option value="{{ $kelompok->id }}">{{ $kelompok->code }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-status">Status</label>
                <select class="form-select" id="student-status" name="status" required>
                  <option value="aktif">Aktif</option>
                  <option value="cuti">Cuti</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-birth">Tanggal lahir</label>
                <input type="date" class="form-control" id="student-birth" name="birth_date" />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-gender">Jenis kelamin</label>
                <select class="form-select" id="student-gender" name="gender">
                  <option value="">— Opsional —</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-birth-place">Tempat lahir</label>
                <input type="text" class="form-control" id="student-birth-place" name="birth_place" placeholder="Jakarta" />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="student-religion">Agama</label>
                <input type="text" class="form-control" id="student-religion" name="religion" placeholder="Islam" />
              </div>
              <div class="col-12 mb-0">
                <label class="form-label" for="student-address">Alamat</label>
                <textarea class="form-control" id="student-address" name="address" rows="2"></textarea>
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

    <div class="modal fade eb-modal" id="modalWali" tabindex="-1" aria-hidden="true" data-default-title="Hubungkan wali siswa">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('kesiswaan.guardians.store') }}" data-store-action="{{ route('kesiswaan.guardians.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Hubungkan wali siswa</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="guardian-student">Siswa</label>
              <select class="form-select" id="guardian-student" name="student_id" required>
                @foreach ($students as $student)
                  <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="guardian-name">Nama wali</label>
              <input type="text" class="form-control" id="guardian-name" name="name" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="guardian-relationship">Hubungan</label>
              <select class="form-select" id="guardian-relationship" name="relationship" required>
                <option value="Ayah">Ayah</option>
                <option value="Ibu">Ibu</option>
                <option value="Wali">Wali</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="guardian-phone">Telepon</label>
              <input type="tel" class="form-control" id="guardian-phone" name="phone" />
            </div>
            <div class="mb-0">
              <label class="form-label" for="guardian-email">Email</label>
              <input type="email" class="form-control" id="guardian-email" name="email" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-crud.js') }}"></script>
  </body>
</html>
