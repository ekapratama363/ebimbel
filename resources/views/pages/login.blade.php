<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Masuk — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-login">
    <div class="container-fluid g-0">
      <div class="row g-0 eb-login-split">
        <div class="col-lg-5 d-none d-lg-flex eb-login-hero">
          <div class="eb-login-hero-inner">
            <p class="small text-white-50 fw-semibold text-uppercase letter-spacing mb-2">{{ $site->site_name }}</p>
            <h2>Panel pengelolaan bimbel dalam satu alur</h2>
            <p>
              Akademik, kesiswaan, keuangan, dan kepegawaian dirancang supaya tim operasional cepat
              menemukan data yang relevan.
            </p>
            <ul class="eb-login-points" aria-label="Ringkasan">
              <li>
                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                <span>Akses modul akademik, kesiswaan, keuangan, dan kepegawaian.</span>
              </li>
              <li>
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                <span>Tampilan siap untuk iterasi desain sistem &amp; SSO produksi.</span>
              </li>
              <li>
                <i class="bi bi-phone" aria-hidden="true"></i>
                <span>Layout responsif untuk laptop dan ponsel.</span>
              </li>
              <li>
                <i class="bi bi-send-check" aria-hidden="true"></i>
                <span>Laporan harian guru dapat diterbitkan ke portal orang tua (wali).</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-7 eb-login-panel">
          <div class="w-100" style="max-width: 440px">
            <p class="mb-3">
              <a href="{{ route('landing') }}" class="eb-login-back d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke beranda
              </a>
            </p>

            <div class="card eb-login-card border-0">
              <div class="card-body">
                <header class="eb-login-brand mb-4">
                  <img
                    class="eb-brand-mark-lg"
                    src="{{ $site->logoUrl() }}"
                    alt="{{ $site->site_name }}"
                    width="1024"
                    height="1024"
                    decoding="async"
                  />
                  <h1 class="h6 text-muted fw-semibold mb-0 mt-2">Masuk ke panel pengelolaan</h1>
                </header>

                <!-- <div
                  class="alert alert-light border small py-2 px-3 mb-4"
                  role="note"
                  style="border-color: var(--eb-border) !important; background: var(--eb-surface-2)"
                >
                  <i class="bi bi-info-circle me-1 text-primary"></i>
                  Akun demo: <strong>admin@bimbel.contoh</strong> / <strong>password</strong>
                </div> -->

                <form id="form-login" method="post" action="{{ route('login.store') }}" novalidate>
                  @csrf
                  <div class="mb-3">
                    <label for="email" class="form-label">Email atau nama pengguna</label>
                    <div class="eb-input-icon-wrap">
                      <i class="bi bi-person eb-input-icon" aria-hidden="true"></i>
                      <input
                        type="text"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        placeholder="Masukkan email"
                      />
                    </div>
                    @error('email')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <div class="eb-input-icon-wrap">
                      <i class="bi bi-key eb-input-icon" aria-hidden="true"></i>
                      <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        placeholder="••••••••"
                      />
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                      <label class="form-check-label small" for="remember"> Ingat saya </label>
                    </div>
                    <a href="#" class="small fw-semibold text-decoration-none" style="color: var(--eb-primary)" onclick="return false;">Lupa kata sandi?</a>
                  </div>
                  <button type="submit" class="btn btn-eb-primary w-100 py-2 fw-semibold rounded-3">
                    Masuk
                  </button>
                </form>

                <hr class="my-4 text-muted opacity-25" />
                <p class="small text-muted text-center mb-2">Orang tua / wali siswa</p>
                <a
                  href="{{ route('orang-tua') }}"
                  class="btn btn-outline-secondary w-100 py-2 fw-semibold rounded-3 d-inline-flex align-items-center justify-content-center gap-2"
                >
                  <i class="bi bi-people" aria-hidden="true"></i>
                  Lihat laporan harian guru
                </a>
                <p class="text-center text-muted small mt-2 mb-0">
                  Tanpa kata sandi — pratinjau portal wali.
                </p>
              </div>
            </div>
            <p class="text-center text-muted small mt-3 mb-0">© {{ $site->admin_brand }}</p>
          </div>
        </div>
      </div>
    </div>

    @include('partials.footer-scripts')
  </body>
</html>
