<nav class="eb-subnav-wrap mb-4">
  <ul class="nav eb-subnav">
    <li class="nav-item">
      <a class="nav-link {{ ($aksesTab ?? '') === 'roles' ? 'active' : '' }}" href="{{ route('akses.roles') }}">
        <i class="bi bi-shield-lock me-1"></i> Role & izin
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ ($aksesTab ?? '') === 'users' ? 'active' : '' }}" href="{{ route('akses.users') }}">
        <i class="bi bi-people me-1"></i> Pengguna
      </a>
    </li>
  </ul>
</nav>
