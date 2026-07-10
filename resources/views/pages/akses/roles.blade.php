<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Role & izin — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'akses'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Role & izin akses</h1>
          <p class="eb-page-desc">
            Kelola role dinamis dan tentukan modul serta aksi yang boleh diakses setiap role.
          </p>
        </div>
      </div>

      @include('partials.akses-subnav', ['aksesTab' => 'roles'])

      <div class="card table-card border-0">
        <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
          <span class="fw-semibold">Daftar role</span>
          @if ($can('akses.create'))
          <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalRole">
            <i class="bi bi-plus-lg"></i> Tambah role
          </button>
          @endif
        </div>
        <div class="table-responsive eb-table-wrap">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Nama role</th>
                <th>Slug</th>
                <th>Pengguna</th>
                <th>Tipe</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($roles as $role)
              <tr>
                <td>
                  <div class="fw-semibold">{{ $role->name }}</div>
                  @if ($role->description)
                    <div class="small text-muted">{{ $role->description }}</div>
                  @endif
                </td>
                <td><code>{{ $role->slug }}</code></td>
                <td>{{ $role->users_count }}</td>
                <td>
                  @if ($role->is_system)
                    <span class="badge rounded-pill text-secondary bg-light">Sistem</span>
                  @else
                    <span class="badge badge-eb rounded-pill">Kustom</span>
                  @endif
                </td>
                <td class="text-end">
                  @if ($can('akses.edit'))
                  <button
                    type="button"
                    class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                    data-eb-modal="modalRole"
                    data-eb-action="{{ route('akses.roles.update', $role) }}"
                    data-eb-title="Ubah role"
                    data-eb-edit="{{ json_encode(['name' => $role->name, 'description' => $role->description, 'permissions' => $role->permissions->map(fn($p) => $p->key)->values()]) }}"
                  >Ubah</button>
                  @endif
                  @if ($can('akses.delete') && ! $role->is_system)
                  <form method="post" action="{{ route('akses.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Hapus role ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">Hapus</button>
                  </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Belum ada role.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card border-0 mt-4">
        <div class="card-body">
          <h2 class="h6 fw-semibold mb-3">Referensi modul & aksi</h2>
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr>
                  <th>Modul</th>
                  @foreach ($actionLabels as $label)
                    <th class="text-center">{{ $label }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach ($modules as $moduleKey => $module)
                <tr>
                  <td class="fw-semibold">{{ $module['label'] }}</td>
                  @foreach ($actionLabels as $actionKey => $label)
                    <td class="text-center">
                      @if (in_array($actionKey, $module['actions'], true))
                        <i class="bi bi-check2 text-success"></i>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                  @endforeach
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modalRole" tabindex="-1" aria-hidden="true" data-default-title="Tambah role">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="post" action="{{ route('akses.roles.store') }}" data-store-action="{{ route('akses.roles.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah role</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="role-name">Nama role</label>
              <input type="text" class="form-control" id="role-name" name="name" placeholder="Staff keuangan" required />
            </div>
            <div class="mb-4">
              <label class="form-label" for="role-description">Deskripsi</label>
              <textarea class="form-control" id="role-description" name="description" rows="2" placeholder="Keterangan singkat role ini"></textarea>
            </div>

            <p class="fw-semibold mb-2">Izin akses per modul</p>
            <div class="table-responsive border rounded">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Modul</th>
                    @foreach ($actionLabels as $label)
                      <th class="text-center text-nowrap">{{ $label }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach ($modules as $moduleKey => $module)
                  <tr>
                    <td class="fw-semibold text-nowrap">{{ $module['label'] }}</td>
                    @foreach ($actionLabels as $actionKey => $label)
                      <td class="text-center">
                        @if (in_array($actionKey, $module['actions'], true))
                          <input
                            type="checkbox"
                            class="form-check-input"
                            name="permissions[]"
                            value="{{ $moduleKey }}.{{ $actionKey }}"
                            aria-label="{{ $label }} {{ $module['label'] }}"
                          />
                        @else
                          <span class="text-muted">—</span>
                        @endif
                      </td>
                    @endforeach
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="form-text mt-2">Centang aksi yang boleh dilakukan role ini pada setiap modul.</div>
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
