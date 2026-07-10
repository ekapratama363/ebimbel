<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengguna — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'akses'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Pengguna</h1>
          <p class="eb-page-desc">Kelola akun pengguna dan tetapkan role akses masing-masing.</p>
        </div>
      </div>

      @include('partials.akses-subnav', ['aksesTab' => 'users'])

      <div class="card table-card border-0">
        <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
          <span class="fw-semibold">Daftar pengguna</span>
          @if ($can('akses.create'))
          <button type="button" class="btn btn-sm btn-eb" data-bs-toggle="modal" data-bs-target="#modalUser">
            <i class="bi bi-plus-lg"></i> Tambah pengguna
          </button>
          @endif
        </div>
        <div class="table-responsive eb-table-wrap">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @if ($user->role)
                    <span class="badge badge-eb rounded-pill">{{ $user->role->name }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-end">
                  @if ($can('akses.edit'))
                  <button
                    type="button"
                    class="btn btn-link btn-sm p-0 me-2 fw-semibold"
                    data-eb-modal="modalUser"
                    data-eb-action="{{ route('akses.users.update', $user) }}"
                    data-eb-title="Ubah pengguna"
                    data-eb-edit="{{ json_encode(['name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id]) }}"
                  >Ubah</button>
                  @endif
                  @if ($can('akses.delete') && $user->id !== auth()->id())
                  <form method="post" action="{{ route('akses.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 fw-semibold">Hapus</button>
                  </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Belum ada pengguna.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modalUser" tabindex="-1" aria-hidden="true" data-default-title="Tambah pengguna">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('akses.users.store') }}" data-store-action="{{ route('akses.users.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h2 class="modal-title">Tambah pengguna</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="user-name">Nama</label>
              <input type="text" class="form-control" id="user-name" name="name" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="user-email">Email</label>
              <input type="email" class="form-control" id="user-email" name="email" required />
            </div>
            <div class="mb-3">
              <label class="form-label" for="user-role">Role</label>
              <select class="form-select" id="user-role" name="role_id" required>
                <option value="" disabled selected>Pilih role</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="user-password">Kata sandi</label>
              <input type="password" class="form-control" id="user-password" name="password" data-required-on-create="true" />
            </div>
            <div class="mb-0">
              <label class="form-label" for="user-password-confirm">Konfirmasi kata sandi</label>
              <input type="password" class="form-control" id="user-password-confirm" name="password_confirmation" data-required-on-create="true" />
              <div class="form-text" id="user-password-hint">Wajib diisi saat menambah pengguna. Kosongkan saat ubah jika tidak ingin mengganti.</div>
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
    <script>
      document.getElementById('modalUser')?.addEventListener('show.bs.modal', (event) => {
        const form = event.target.querySelector('form');
        const isEdit = !!form.querySelector('input[name="_method"]');
        form.querySelectorAll('[data-required-on-create]').forEach((field) => {
          field.required = !isEdit;
        });
        document.getElementById('user-password-hint').textContent = isEdit
          ? 'Kosongkan jika tidak ingin mengganti kata sandi.'
          : 'Wajib diisi saat menambah pengguna.';
      });
    </script>
  </body>
</html>
