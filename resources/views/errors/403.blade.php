<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Akses ditolak — {{ $site->admin_brand ?? 'eBimbel' }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @auth
      @include('partials.admin-nav', ['activeModule' => ''])
    @endauth
    <main class="eb-main container-fluid px-3 px-md-4 py-5">
      <div class="card border-0 shadow-sm mx-auto" style="max-width: 32rem">
        <div class="card-body text-center py-5">
          <div class="display-6 text-danger mb-3"><i class="bi bi-shield-exclamation"></i></div>
          <h1 class="h4 fw-semibold mb-2">Akses ditolak</h1>
          <p class="text-muted mb-4">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
          <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('login') }}" class="btn btn-eb">Kembali</a>
        </div>
      </div>
    </main>
    @include('partials.footer-scripts')
  </body>
</html>
