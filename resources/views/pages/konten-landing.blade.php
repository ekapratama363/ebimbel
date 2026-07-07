@php
  $sectionLabels = [
    'hero' => 'Hero utama',
    'hero_card' => 'Kartu hero (kanan)',
    'strip' => 'Strip singkat',
    'about' => 'Section tentang',
    'about_sidebar' => 'Sidebar visi & nilai',
    'program_intro' => 'Intro program',
    'mengapa_intro' => 'Intro mengapa kami',
    'orang_tua' => 'Section orang tua',
    'ortu_preview' => 'Preview laporan ortu',
    'kemitraan' => 'Section kemitraan',
    'cta' => 'CTA daftar',
    'footer_tagline' => 'Tagline footer',
  ];
  $itemGroups = [
    'hero_point' => ['label' => 'Poin hero', 'hasDesc' => false, 'hasValue' => false],
    'quick_link' => ['label' => 'Usia & jenjang', 'hasDesc' => true, 'hasValue' => false],
    'stat' => ['label' => 'Statistik tentang', 'hasDesc' => false, 'hasValue' => true],
    'about_value' => ['label' => 'Nilai kelas', 'hasDesc' => false, 'hasValue' => false],
    'feature' => ['label' => 'Kartu fitur', 'hasDesc' => true, 'hasValue' => false],
    'ortu_point' => ['label' => 'Poin orang tua', 'hasDesc' => false, 'hasValue' => false],
    'kemitraan_point' => ['label' => 'Poin kemitraan', 'hasDesc' => false, 'hasValue' => false],
    'step' => ['label' => 'Langkah kemitraan', 'hasDesc' => true, 'hasValue' => false],
  ];
@endphp
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Konten landing — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'konten-landing'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">
          <div>
            <h1 class="eb-page-title">Konten landing page</h1>
            <p class="eb-page-desc mb-0">
              Kelola banner, kartu program, teks section, dan daftar poin yang tampil di halaman beranda.
            </p>
          </div>
          <a class="btn btn-sm btn-outline-primary" href="{{ route('landing') }}" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat beranda
          </a>
        </div>
      </div>

      <div class="eb-subnav-wrap">
        <ul class="nav eb-subnav" id="landingTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-banner" type="button" role="tab">
              <i class="bi bi-images me-1"></i> Banner
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-program" type="button" role="tab">
              <i class="bi bi-grid me-1"></i> Kartu program
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-sections" type="button" role="tab">
              <i class="bi bi-text-paragraph me-1"></i> Teks section
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-items" type="button" role="tab">
              <i class="bi bi-list-ul me-1"></i> Daftar item
            </button>
          </li>
        </ul>
      </div>

      <div class="tab-content" id="landingTabsContent">
        {{-- Banner --}}
        <div class="tab-pane fade show active" id="pane-banner" role="tabpanel">
          <div class="card table-card border-0">
            <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
              <span class="fw-semibold">Slide banner carousel</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalSlide">
                <i class="bi bi-plus-lg"></i> Tambah slide
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                    <tr>
                    <th>Urut</th>
                    <th>Gambar</th>
                    <th>Tag</th>
                    <th>Judul</th>
                    <th>Style</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($slides as $slide)
                  <tr>
                    <td>{{ $slide->sort_order }}</td>
                    <td>
                      @if ($slide->imageUrl())
                        <img src="{{ $slide->imageUrl() }}" alt="" width="72" height="48" class="rounded object-fit-cover" style="object-fit:cover" />
                      @else
                        <span class="text-muted small">—</span>
                      @endif
                    </td>
                    <td>{{ $slide->tag }}</td>
                    <td>{{ $slide->title }}</td>
                    <td>{{ $slide->slide_style }}</td>
                    <td>
                      @if ($slide->is_active)
                        <span class="badge badge-eb rounded-pill">Aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">Nonaktif</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalSlide"
                        data-eb-action="{{ route('konten-landing.slides.update', $slide) }}"
                        data-eb-title="Ubah slide"
                        data-eb-edit="{{ json_encode(['tag' => $slide->tag, 'title' => $slide->title, 'description' => $slide->description, 'cta_text' => $slide->cta_text, 'cta_link' => $slide->cta_link, 'slide_style' => $slide->slide_style, 'sort_order' => $slide->sort_order, 'is_active' => $slide->is_active ? '1' : '0']) }}"
                      >Ubah</button>
                      <form method="post" action="{{ route('konten-landing.slides.destroy', $slide) }}" class="d-inline" onsubmit="return confirm('Hapus slide ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">Hapus</button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="7" class="text-center text-muted py-4">Belum ada slide.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- Program cards --}}
        <div class="tab-pane fade" id="pane-program" role="tabpanel">
          <div class="card table-card border-0">
            <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
              <span class="fw-semibold">Kartu program di landing</span>
              <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalProgram">
                <i class="bi bi-plus-lg"></i> Tambah kartu
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Urut</th>
                    <th>Judul</th>
                    <th>Icon</th>
                    <th>Warna</th>
                    <th class="text-end">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($programs as $program)
                  <tr>
                    <td>{{ $program->sort_order }}</td>
                    <td>{{ $program->title }}</td>
                    <td><i class="bi bi-{{ preg_replace('/^bi-/', '', $program->icon) }}"></i> <code>{{ $program->icon }}</code></td>
                    <td>{{ $program->color_variant }}</td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                        data-eb-modal="modalProgram"
                        data-eb-action="{{ route('konten-landing.programs.update', $program) }}"
                        data-eb-title="Ubah kartu program"
                        data-eb-edit="{{ json_encode(['title' => $program->title, 'description' => $program->description, 'icon' => $program->icon, 'color_variant' => $program->color_variant, 'sort_order' => $program->sort_order]) }}"
                      >Ubah</button>
                      <form method="post" action="{{ route('konten-landing.programs.destroy', $program) }}" class="d-inline" onsubmit="return confirm('Hapus kartu ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">Hapus</button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kartu program.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        {{-- Sections --}}
        <div class="tab-pane fade" id="pane-sections" role="tabpanel">
          <div class="alert alert-light border mb-3 small mb-4">
            <i class="bi bi-info-circle me-1 text-primary"></i>
            Teks di bawah ini <strong>disimpan di database</strong> dan langsung tampil di beranda.
            Daftar section (hero, tentang, CTA, dll.) mengikuti layout halaman — bukan halaman statis,
            tetapi section baru tidak bisa ditambah dari sini.
          </div>
          <div class="row g-3">
            @foreach ($sectionLabels as $key => $label)
              @php $section = $sections->get($key); @endphp
              @if ($section)
              <div class="col-lg-6">
                <div class="card table-card border-0 h-100">
                  <div class="card-body">
                    <h2 class="h6 fw-bold mb-3">{{ $label }}</h2>
                    <form method="post" action="{{ route('konten-landing.sections.update', $key) }}">
                      @csrf
                      @method('PUT')
                      <div class="mb-2">
                        <label class="form-label small">Kicker / label kecil</label>
                        <input type="text" class="form-control form-control-sm" name="kicker" value="{{ $section->kicker }}" />
                      </div>
                      <div class="mb-2">
                        <label class="form-label small">Judul</label>
                        <input type="text" class="form-control form-control-sm" name="title" value="{{ $section->title }}" />
                      </div>
                      <div class="mb-2">
                        <label class="form-label small">Subjudul / aksen</label>
                        <input type="text" class="form-control form-control-sm" name="subtitle" value="{{ $section->subtitle }}" />
                      </div>
                      <div class="mb-2">
                        <label class="form-label small">Isi utama</label>
                        <textarea class="form-control form-control-sm" name="body" rows="3">{{ $section->body }}</textarea>
                      </div>
                      <div class="mb-2">
                        <label class="form-label small">Isi tambahan</label>
                        <textarea class="form-control form-control-sm" name="body_secondary" rows="2">{{ $section->body_secondary }}</textarea>
                      </div>
                      <div class="row g-2 mb-2">
                        <div class="col-6">
                          <label class="form-label small">CTA utama</label>
                          <input type="text" class="form-control form-control-sm" name="cta_primary_text" value="{{ $section->cta_primary_text }}" />
                        </div>
                        <div class="col-6">
                          <label class="form-label small">Link CTA utama</label>
                          <input type="text" class="form-control form-control-sm" name="cta_primary_link" value="{{ $section->cta_primary_link }}" />
                        </div>
                        <div class="col-6">
                          <label class="form-label small">CTA sekunder</label>
                          <input type="text" class="form-control form-control-sm" name="cta_secondary_text" value="{{ $section->cta_secondary_text }}" />
                        </div>
                        <div class="col-6">
                          <label class="form-label small">Link CTA sekunder</label>
                          <input type="text" class="form-control form-control-sm" name="cta_secondary_link" value="{{ $section->cta_secondary_link }}" />
                        </div>
                      </div>
                      <div class="row g-2 mb-3">
                        <div class="col-6">
                          <label class="form-label small">Email kontak</label>
                          <input type="text" class="form-control form-control-sm" name="contact_email" value="{{ $section->contact_email }}" />
                        </div>
                        <div class="col-6">
                          <label class="form-label small">Telepon / WhatsApp</label>
                          <input type="text" class="form-control form-control-sm" name="contact_phone" value="{{ $section->contact_phone }}" />
                        </div>
                        <div class="col-12">
                          <label class="form-label small">Teks ekstra</label>
                          <input type="text" class="form-control form-control-sm" name="extra_text" value="{{ $section->extra_text }}" />
                        </div>
                      </div>
                      <button type="submit" class="btn btn-sm btn-eb">Simpan {{ $label }}</button>
                    </form>
                  </div>
                </div>
              </div>
              @endif
            @endforeach
          </div>
        </div>

        {{-- Items --}}
        <div class="tab-pane fade" id="pane-items" role="tabpanel">
          @foreach ($itemGroups as $group => $meta)
            @php
              $groupItems = match ($group) {
                'hero_point' => $heroPoints,
                'quick_link' => $quickLinks,
                'stat' => $stats,
                'about_value' => $aboutValues,
                'feature' => $features,
                'ortu_point' => $ortuPoints,
                'kemitraan_point' => $kemitraanPoints,
                'step' => $steps,
                default => collect(),
              };
            @endphp
            <div class="card table-card border-0 mb-3">
              <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-semibold">{{ $meta['label'] }}</span>
                <button
                  type="button"
                  class="btn btn-sm btn-eb btn-add-item"
                  data-bs-toggle="modal"
                  data-bs-target="#modalItem"
                  data-item-group="{{ $group }}"
                  data-item-label="{{ $meta['label'] }}"
                >
                  <i class="bi bi-plus-lg"></i> Tambah
                </button>
              </div>
              <div class="table-responsive eb-table-wrap">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Urut</th>
                      @if ($meta['hasValue'])<th>Nilai</th>@endif
                      <th>Judul</th>
                      @if ($meta['hasDesc'])<th>Deskripsi</th>@endif
                      <th>Icon</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($groupItems as $item)
                    <tr>
                      <td>{{ $item->sort_order }}</td>
                      @if ($meta['hasValue'])
                      <td>{{ $item->value }}{{ $item->suffix }}</td>
                      @endif
                      <td>{{ $item->title }}</td>
                      @if ($meta['hasDesc'])
                      <td class="small text-muted">{{ Str::limit($item->description, 60) }}</td>
                      @endif
                      <td>@if ($item->icon)<i class="bi bi-{{ preg_replace('/^bi-/', '', $item->icon) }}"></i>@endif</td>
                      <td class="text-end">
                        <button
                          type="button"
                          class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                          data-eb-modal="modalItem"
                          data-eb-action="{{ route('konten-landing.items.update', $item) }}"
                          data-eb-title="Ubah item"
                          data-eb-edit="{{ json_encode(['group' => $item->group, 'icon' => $item->icon, 'title' => $item->title, 'description' => $item->description, 'value' => $item->value, 'suffix' => $item->suffix, 'sort_order' => $item->sort_order, 'is_active' => $item->is_active ? '1' : '0']) }}"
                        >Ubah</button>
                        <form method="post" action="{{ route('konten-landing.items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">Hapus</button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada item.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </main>

    {{-- Modal slide --}}
    <div class="modal fade eb-modal" id="modalSlide" tabindex="-1" data-default-title="Tambah slide">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="post" action="{{ route('konten-landing.slides.store') }}" data-store-action="{{ route('konten-landing.slides.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Tambah slide</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
              <div class="col-md-4">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="sort_order" value="0" min="0" required />
              </div>
              <div class="col-md-4">
                <label class="form-label">Style (1–3)</label>
                <input type="number" class="form-control" name="slide_style" value="1" min="1" max="3" required />
              </div>
              <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" name="is_active">
                  <option value="1">Aktif</option>
                  <option value="0">Nonaktif</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Tag</label>
                <input type="text" class="form-control" name="tag" />
              </div>
              <div class="col-12">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" required />
              </div>
              <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Gambar slide</label>
                <input type="file" class="form-control" name="image" accept="image/*" />
                <div class="form-text">PNG/JPG/WebP, maks. 2 MB. Tampil di sisi kanan banner; style 1 tanpa gambar memakai logo situs.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Teks CTA</label>
                <input type="text" class="form-control" name="cta_text" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Link CTA</label>
                <input type="text" class="form-control" name="cta_link" placeholder="#daftar" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-eb">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Modal program --}}
    <div class="modal fade eb-modal" id="modalProgram" tabindex="-1" data-default-title="Tambah kartu program">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="{{ route('konten-landing.programs.store') }}" data-store-action="{{ route('konten-landing.programs.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Tambah kartu program</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Icon Bootstrap</label>
                <input type="text" class="form-control" name="icon" placeholder="alphabet" required />
                <div class="form-text">Tanpa prefix <code>bi-</code>, mis. <code>calculator</code></div>
              </div>
              <div class="mb-3">
                <label class="form-label">Varian warna</label>
                <input type="text" class="form-control" name="color_variant" placeholder="teal" required />
                <div class="form-text">teal, amber, orange, red, dll.</div>
              </div>
              <div class="mb-0">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="sort_order" value="0" min="0" required />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-eb">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Modal item --}}
    <div class="modal fade eb-modal" id="modalItem" tabindex="-1" data-default-title="Tambah item">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="{{ route('konten-landing.items.store') }}" data-store-action="{{ route('konten-landing.items.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Tambah item</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Grup</label>
                <select class="form-select" name="group" id="item-group" required>
                  @foreach ($itemGroups as $group => $meta)
                    <option value="{{ $group }}">{{ $meta['label'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" required />
              </div>
              <div class="mb-3" id="item-desc-wrap">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="2"></textarea>
              </div>
              <div class="row g-2 mb-3" id="item-value-wrap">
                <div class="col-6">
                  <label class="form-label">Nilai angka</label>
                  <input type="number" class="form-control" name="value" min="0" />
                </div>
                <div class="col-6">
                  <label class="form-label">Suffix</label>
                  <input type="text" class="form-control" name="suffix" placeholder="+, %, dll." />
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Icon</label>
                <input type="text" class="form-control" name="icon" placeholder="check2-circle" />
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label">Urutan</label>
                  <input type="number" class="form-control" name="sort_order" value="0" min="0" required />
                </div>
                <div class="col-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="is_active">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
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
    </div>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-crud.js') }}"></script>
    <script>
      document.querySelectorAll('.btn-add-item').forEach((btn) => {
        btn.addEventListener('click', () => {
          const group = document.getElementById('item-group');
          if (group) group.value = btn.dataset.itemGroup;
        });
      });
    </script>
  </body>
</html>
