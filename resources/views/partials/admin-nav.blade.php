@php
    $activeModule = $activeModule ?? '';
    $userName = auth()->user()?->name ?? 'Admin';
@endphp
<nav class="navbar navbar-expand-lg navbar-dark navbar-eb shadow-sm">
  <div class="container-fluid px-3 px-md-4">
    <a class="navbar-brand py-1" href="{{ route($activeModule && $activeModule !== 'pengaturan' ? $activeModule : 'akademik') }}" aria-label="{{ $site->admin_brand }}">
      <img class="eb-logo" src="{{ $site->logoUrl() }}" alt="{{ $site->site_name }}" width="220" height="66" decoding="async" />
    </a>
    <div class="navbar-collapse d-flex flex-grow-1 justify-content-between align-items-center flex-wrap gap-2 py-2 py-lg-0">
      <div class="d-flex flex-wrap gap-1">
        <a class="nav-module {{ $activeModule === 'akademik' ? 'active' : '' }}" href="{{ route('akademik') }}" @if($activeModule === 'akademik') aria-current="page" @endif><i class="bi bi-journal-bookmark me-1"></i>Akademik</a>
        <a class="nav-module {{ $activeModule === 'kesiswaan' ? 'active' : '' }}" href="{{ route('kesiswaan') }}" @if($activeModule === 'kesiswaan') aria-current="page" @endif><i class="bi bi-people me-1"></i>Kesiswaan</a>
        <a class="nav-module {{ $activeModule === 'keuangan' ? 'active' : '' }}" href="{{ route('keuangan') }}" @if($activeModule === 'keuangan') aria-current="page" @endif><i class="bi bi-cash-coin me-1"></i>Keuangan</a>
        <a class="nav-module {{ $activeModule === 'kepegawaian' ? 'active' : '' }}" href="{{ route('kepegawaian') }}" @if($activeModule === 'kepegawaian') aria-current="page" @endif><i class="bi bi-person-badge me-1"></i>Kepegawaian</a>
        <a class="nav-module {{ $activeModule === 'konten-landing' ? 'active' : '' }}" href="{{ route('konten-landing') }}" @if($activeModule === 'konten-landing') aria-current="page" @endif><i class="bi bi-window-desktop me-1"></i>Konten landing</a>
        <a class="nav-module {{ $activeModule === 'pengaturan' ? 'active' : '' }}" href="{{ route('pengaturan') }}" @if($activeModule === 'pengaturan') aria-current="page" @endif><i class="bi bi-gear me-1"></i>Pengaturan</a>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="eb-user-pill d-none d-sm-inline-flex align-items-center gap-1 mb-0">
          <i class="bi bi-person-circle" aria-hidden="true"></i> {{ $userName }}
        </span>
        <form method="post" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
        </form>
      </div>
    </div>
  </div>
</nav>
