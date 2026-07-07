<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengaturan situs — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'pengaturan'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Pengaturan situs</h1>
          <p class="eb-page-desc">
            Kelola nama brand, judul halaman, logo, dan informasi organisasi yang tampil di seluruh website.
          </p>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card table-card border-0">
            <div class="card-body">
              <form method="post" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4 text-center">
                  <img
                    class="eb-brand-mark-lg mb-3"
                    src="{{ $setting->logoUrl() }}"
                    alt="{{ $setting->site_name }}"
                    width="220"
                    height="66"
                    decoding="async"
                  />
                  <div>
                    <label class="form-label" for="logo">Ganti logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                    <div class="form-text">PNG/JPG/WebP, maks. 2 MB. Kosongkan jika tidak ingin mengganti.</div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="site-name">Nama situs / brand</label>
                  <input
                    type="text"
                    class="form-control"
                    id="site-name"
                    name="site_name"
                    value="{{ old('site_name', $setting->site_name) }}"
                    required
                  />
                  <div class="form-text">Dipakai di landing page, footer, dan teks brand.</div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="admin-brand">Nama panel admin</label>
                  <input
                    type="text"
                    class="form-control"
                    id="admin-brand"
                    name="admin_brand"
                    value="{{ old('admin_brand', $setting->admin_brand) }}"
                    required
                  />
                  <div class="form-text">Muncul di judul tab browser modul admin, mis. "Akademik — eBimbel".</div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="landing-title">Judul halaman beranda</label>
                  <input
                    type="text"
                    class="form-control"
                    id="landing-title"
                    name="landing_title"
                    value="{{ old('landing_title', $setting->landing_title) }}"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label class="form-label" for="meta-description">Deskripsi SEO beranda</label>
                  <textarea
                    class="form-control"
                    id="meta-description"
                    name="meta_description"
                    rows="3"
                  >{{ old('meta_description', $setting->meta_description) }}</textarea>
                </div>

                <div class="mb-4">
                  <label class="form-label" for="organization-name">Nama organisasi</label>
                  <input
                    type="text"
                    class="form-control"
                    id="organization-name"
                    name="organization_name"
                    value="{{ old('organization_name', $setting->organization_name) }}"
                  />
                  <div class="form-text">Contoh: yayasan atau entitas induk yang tampil di footer.</div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                  <a href="{{ route('akademik') }}" class="btn btn-outline-secondary">Batal</a>
                  <button type="submit" class="btn btn-eb">Simpan pengaturan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>

    @include('partials.footer-scripts')
  </body>
</html>
