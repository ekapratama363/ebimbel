@php
    $activeModule = $activeModule ?? '';
    $userName = auth()->user()?->name ?? 'Admin';
    $navModules = [
        ['key' => 'akademik', 'route' => 'akademik', 'label' => 'Akademik', 'icon' => 'bi-journal-bookmark'],
        ['key' => 'kesiswaan', 'route' => 'kesiswaan', 'label' => 'Kesiswaan', 'icon' => 'bi-people'],
        ['key' => 'keuangan', 'route' => 'keuangan', 'label' => 'Keuangan', 'icon' => 'bi-cash-coin'],
        ['key' => 'kepegawaian', 'route' => 'kepegawaian', 'label' => 'Kepegawaian', 'icon' => 'bi-person-badge'],
        ['key' => 'konten-landing', 'route' => 'konten-landing', 'label' => 'Konten landing', 'icon' => 'bi-window-desktop'],
        ['key' => 'pengaturan', 'route' => 'pengaturan', 'label' => 'Pengaturan', 'icon' => 'bi-gear'],
        ['key' => 'akses', 'route' => 'akses.roles', 'label' => 'Role & akses', 'icon' => 'bi-shield-lock'],
    ];
    $homeRoute = collect($navModules)->first(fn ($nav) => $can($nav['key'].'.view'))['route'] ?? 'akademik';
@endphp
<nav class="navbar navbar-expand-lg navbar-dark navbar-eb shadow-sm">
  <div class="container-fluid px-3 px-md-4">
    <a class="navbar-brand py-1" href="{{ route($homeRoute) }}" aria-label="{{ $site->admin_brand }}">
      <img class="eb-logo" src="{{ $site->logoUrl() }}" alt="{{ $site->site_name }}" width="220" height="66" decoding="async" />
    </a>
    <div class="navbar-collapse d-flex flex-grow-1 justify-content-between align-items-center flex-wrap gap-2 py-2 py-lg-0">
      <div class="d-flex flex-wrap gap-1">
        @foreach ($navModules as $nav)
          @if ($can($nav['key'].'.view'))
            <a
              class="nav-module {{ $activeModule === $nav['key'] ? 'active' : '' }}"
              href="{{ route($nav['route']) }}"
              @if ($activeModule === $nav['key']) aria-current="page" @endif
            ><i class="bi {{ $nav['icon'] }} me-1"></i>{{ $nav['label'] }}</a>
          @endif
        @endforeach
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="eb-user-pill d-none d-sm-inline-flex align-items-center gap-1 mb-0">
          <i class="bi bi-person-circle" aria-hidden="true"></i> {{ $userName }}
          @if (auth()->user()?->role)
            <span class="opacity-75">· {{ auth()->user()->role->name }}</span>
          @endif
        </span>
        <form method="post" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
        </form>
      </div>
    </div>
  </div>
</nav>
