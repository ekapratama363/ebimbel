<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen keuangan &amp; akuntansi — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'keuangan'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="card eb-page-head border-0 mb-4">
        <div class="card-body">
          <h1 class="eb-page-title">Manajemen keuangan &amp; akuntansi</h1>
          <p class="eb-page-desc">
            Pembayaran siswa, pengeluaran operasional, dan laporan keuangan.
          </p>
        </div>
      </div>

      <div class="eb-subnav-wrap">
        <ul class="nav eb-subnav" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-pembayaran"
              data-bs-toggle="tab"
              data-bs-target="#pane-pembayaran"
              type="button"
              role="tab"
            >
              <i class="bi bi-receipt me-1"></i> Pembayaran siswa
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-pengeluaran"
              data-bs-toggle="tab"
              data-bs-target="#pane-pengeluaran"
              type="button"
              role="tab"
            >
              <i class="bi bi-wallet me-1"></i> Pengeluaran
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-laporan"
              data-bs-toggle="tab"
              data-bs-target="#pane-laporan"
              type="button"
              role="tab"
            >
              <i class="bi bi-bar-chart-line me-1"></i> Laporan keuangan
            </button>
          </li>
        </ul>
      </div>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="pane-pembayaran" role="tabpanel">
          @if (! $duitkuConfigured)
          <div class="alert alert-warning small mb-3">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Integrasi Duitku belum aktif. Isi <code>DUITKU_MERCHANT_CODE</code> dan <code>DUITKU_API_KEY</code> di file <code>.env</code>.
          </div>
          @endif
          <form method="get" action="{{ route('keuangan') }}" class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
              <label class="form-label small">Jenis pembayaran</label>
              <select class="form-select form-select-sm" name="jenis" onchange="this.form.submit()">
                <option value="">Semua jenis</option>
                @foreach ($paymentTypes as $type)
                  <option value="{{ $type->id }}" @selected($typeFilter == $type->id)>{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small">Status</label>
              <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach (['belum_bayar', 'pending', 'lunas', 'gagal', 'expired', 'dibatalkan'] as $st)
                  <option value="{{ $st }}" @selected($statusFilter === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                @endforeach
              </select>
            </div>
          </form>
          <div
            class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 rounded-3 border"
            style="background: var(--eb-surface); border-color: var(--eb-border) !important"
          >
            <h2 class="h6 fw-bold mb-0 px-1">Daftar pembayaran siswa</h2>
            @perm('keuangan.create')
            <button
              class="btn btn-eb btn-sm"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#modal-tambah-pembayaran"
            >
              <i class="bi bi-plus-lg me-1"></i>Tambah tagihan
            </button>
            @endperm
          </div>
          <div class="card table-card border-0">
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Invoice</th>
                    <th>Jenis</th>
                    <th>Siswa</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($payments as $payment)
                  <tr>
                    <td class="ps-3"><code class="small">{{ $payment->invoice_number ?? '—' }}</code></td>
                    <td class="small">{{ $payment->paymentType?->name ?? '—' }}</td>
                    <td>
                      <div class="fw-semibold">{{ $payment->student->name }}</div>
                      <small class="text-muted">{{ $payment->student->kelompok?->name ?? '—' }}</small>
                    </td>
                    <td class="small">{{ $payment->period_label ?? '—' }}</td>
                    <td class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                      @if ($payment->status === 'lunas')
                        <span class="badge rounded-pill badge-soft-success">Lunas</span>
                      @elseif ($payment->status === 'pending')
                        <span class="badge rounded-pill badge-soft-warning">Pending</span>
                      @elseif ($payment->status === 'belum_bayar')
                        <span class="badge rounded-pill badge-soft-danger">Belum bayar</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">{{ $payment->statusLabel() }}</span>
                      @endif
                    </td>
                    <td>
                      @if ($payment->proofUrl())
                        <a href="{{ $payment->proofUrl() }}" target="_blank" rel="noopener" class="small"><i class="bi bi-paperclip"></i> Ada</a>
                      @else
                        <span class="text-muted small">—</span>
                      @endif
                    </td>
                    <td class="text-center pe-3 text-nowrap">
                      <a href="{{ route('keuangan.payments.show', $payment) }}" class="btn btn-sm btn-light border" title="Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      @perm('keuangan.edit')
                      <button
                        class="btn btn-sm btn-light border"
                        type="button"
                        title="Edit"
                        data-eb-modal="modal-tambah-pembayaran"
                        data-eb-action="{{ route('keuangan.payments.update', $payment) }}"
                        data-eb-title="Ubah tagihan pembayaran"
                        data-eb-edit="{{ json_encode(['student_id' => $payment->student_id, 'payment_type_id' => $payment->payment_type_id, 'amount' => $payment->amount, 'period_label' => $payment->period_label, 'description' => $payment->description, 'due_date' => $payment->due_date?->format('Y-m-d'), 'paid_at' => $payment->paid_at?->format('Y-m-d'), 'notes' => $payment->notes, 'status' => $payment->status]) }}"
                      >
                        <i class="bi bi-pencil"></i>
                      </button>
                      @endperm
                      @if ($payment->status !== 'lunas' && $duitkuConfigured)
                      <a href="{{ route('keuangan.payments.duitku', $payment) }}" class="btn btn-sm btn-eb" title="Bayar Duitku"><i class="bi bi-credit-card"></i></a>
                      @endif
                      @perm('keuangan.delete')
                      <form method="post" action="{{ route('keuangan.payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('Hapus tagihan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light border text-danger" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
                      </form>
                      @endperm
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada pembayaran.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card table-card border-0 mt-4">
            <div class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2">
              <h2 class="h6 fw-bold mb-0">Jenis pembayaran</h2>
              <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-jenis-pembayaran">
                <i class="bi bi-plus-lg"></i> Tambah jenis
              </button>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Nama</th>
                    <th>Kode</th>
                    <th>Default</th>
                    <th>Status</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($allPaymentTypes as $type)
                  <tr>
                    <td class="ps-3">{{ $type->name }}</td>
                    <td><code>{{ $type->code }}</code></td>
                    <td>{{ $type->default_amount ? 'Rp '.number_format($type->default_amount, 0, ',', '.') : '—' }}</td>
                    <td>
                      @if ($type->is_active)
                        <span class="badge badge-eb rounded-pill">Aktif</span>
                      @else
                        <span class="badge rounded-pill text-secondary bg-light">Nonaktif</span>
                      @endif
                    </td>
                    <td class="text-center pe-3">
                      <button type="button" class="btn btn-sm btn-light border" data-eb-modal="modal-jenis-pembayaran" data-eb-action="{{ route('keuangan.payment-types.update', $type) }}" data-eb-title="Ubah jenis pembayaran" data-eb-edit="{{ json_encode(['name' => $type->name, 'code' => $type->code, 'description' => $type->description, 'default_amount' => $type->default_amount, 'sort_order' => $type->sort_order, 'is_active' => $type->is_active ? '1' : '0']) }}"><i class="bi bi-pencil"></i></button>
                      <form method="post" action="{{ route('keuangan.payment-types.destroy', $type) }}" class="d-inline" onsubmit="return confirm('Hapus jenis ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-pengeluaran" role="tabpanel">
          <div
            class="eb-table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 rounded-3 border"
            style="background: var(--eb-surface); border-color: var(--eb-border) !important"
          >
            <h2 class="h6 fw-bold mb-0 px-1">Daftar pengeluaran operasional</h2>
            <button
              class="btn btn-eb btn-sm"
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#modal-tambah-pengeluaran"
            >
              <i class="bi bi-plus-lg me-1"></i>Tambah pengeluaran
            </button>
          </div>
          <div class="card table-card border-0">
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Deskripsi</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th class="text-center pe-3">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($expenses as $expense)
                  <tr>
                    <td class="ps-3">{{ $expense->description }}</td>
                    <td>{{ $expense->category }}</td>
                    <td class="fw-semibold">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->expense_date->locale('id')->translatedFormat('d M Y') }}</td>
                    <td class="text-center pe-3">
                      <button
                        class="btn btn-sm btn-light border me-1"
                        type="button"
                        title="Edit"
                        data-eb-modal="modal-tambah-pengeluaran"
                        data-eb-action="{{ route('keuangan.expenses.update', $expense) }}"
                        data-eb-title="Ubah pengeluaran"
                        data-eb-edit="{{ json_encode(['description' => $expense->description, 'category' => $expense->category, 'amount' => $expense->amount, 'expense_date' => $expense->expense_date->format('Y-m-d')]) }}"
                      ><i class="bi bi-pencil"></i></button>
                      <form
                        method="post"
                        action="{{ route('keuangan.expenses.destroy', $expense) }}"
                        class="d-inline"
                        onsubmit="return confirm('Hapus pengeluaran ini?')"
                      >
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light border text-danger" type="submit" title="Hapus"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada pengeluaran.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="pane-laporan" role="tabpanel">
          <h2 class="h6 fw-bold mb-3">Ringkasan keuangan bulan ini</h2>
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div class="eb-stat-icon text-success bg-success bg-opacity-10">
                    <i class="bi bi-arrow-up-circle-fill" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold text-success mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                  <p class="text-muted small mb-0">Total pemasukan</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div class="eb-stat-icon text-danger bg-danger bg-opacity-10">
                    <i class="bi bi-arrow-down-circle-fill" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold text-danger mb-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                  <p class="text-muted small mb-0">Total pengeluaran</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card eb-stat border-0">
                <div class="card-body">
                  <div
                    class="eb-stat-icon text-primary"
                    style="background: var(--eb-primary-soft)"
                  >
                    <i class="bi bi-cash-stack" aria-hidden="true"></i>
                  </div>
                  <h3 class="h5 fw-bold mb-1" style="color: var(--eb-primary)">Rp {{ number_format($netBalance, 0, ',', '.') }}</h3>
                  <p class="text-muted small mb-0">Saldo bersih</p>
                </div>
              </div>
            </div>
          </div>
          <div class="card table-card border-0">
            <div class="eb-table-toolbar">
              <h6 class="mb-0 fw-bold">Detail transaksi</h6>
            </div>
            <div class="table-responsive eb-table-wrap">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th class="ps-3">Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($transactions as $transaction)
                  <tr>
                    <td class="ps-3">
                      @if ($transaction['date'])
                        {{ \Carbon\Carbon::parse($transaction['date'])->locale('id')->translatedFormat('d M Y') }}
                      @else
                        —
                      @endif
                    </td>
                    <td>{{ $transaction['description'] }}</td>
                    <td>
                      @if ($transaction['type'] === 'pemasukan')
                        <span class="badge badge-soft-success">Pemasukan</span>
                      @else
                        <span class="badge badge-soft-danger">Pengeluaran</span>
                      @endif
                    </td>
                    <td class="{{ $transaction['type'] === 'pemasukan' ? 'text-success' : 'text-danger' }} fw-semibold">
                      {{ $transaction['type'] === 'pemasukan' ? '+' : '−' }}Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="modal fade eb-modal" id="modal-tambah-pembayaran" tabindex="-1" aria-hidden="true" data-default-title="Tambah tagihan pembayaran">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="{{ route('keuangan.payments.store') }}" data-store-action="{{ route('keuangan.payments.store') }}" class="modal-content" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah tagihan pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Siswa</label>
                <select class="form-select" name="student_id" required>
                  <option value="" disabled selected>Pilih siswa…</option>
                  @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->kelompok?->name ?? '—' }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Jenis pembayaran</label>
                <select class="form-select" name="payment_type_id" id="payment-type-select" required>
                  @foreach ($paymentTypes as $type)
                    <option value="{{ $type->id }}" data-default="{{ $type->default_amount }}">{{ $type->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Nominal (Rp)</label>
                <input type="number" class="form-control" name="amount" id="payment-amount" min="0" required />
              </div>
              <div class="col-md-6">
                <label class="form-label">Periode / bulan</label>
                <input type="text" class="form-control" name="period_label" placeholder="Juli 2026" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Jatuh tempo</label>
                <input type="date" class="form-control" name="due_date" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Tanggal lunas</label>
                <input type="date" class="form-control" name="paid_at" />
                <div class="form-text">Isi jika sudah dibayar manual.</div>
              </div>
              <div class="col-12">
                <label class="form-label">Keterangan tagihan</label>
                <textarea class="form-control" name="description" rows="2" placeholder="SPP bulan Juli kelas 2A"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">Catatan internal</label>
                <textarea class="form-control" name="notes" rows="2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                  <option value="belum_bayar">Belum bayar</option>
                  <option value="pending">Pending</option>
                  <option value="lunas">Lunas</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Bukti pembayaran</label>
                <input type="file" class="form-control" name="proof" accept="image/*,.pdf" />
                <div class="form-text">JPG/PNG/PDF, maks. 2 MB.</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan tagihan</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade eb-modal" id="modal-jenis-pembayaran" tabindex="-1" data-default-title="Tambah jenis pembayaran">
      <div class="modal-dialog">
        <form method="post" action="{{ route('keuangan.payment-types.store') }}" data-store-action="{{ route('keuangan.payment-types.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah jenis pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" name="name" required placeholder="SPP Bulanan" />
            </div>
            <div class="mb-3">
              <label class="form-label">Kode</label>
              <input type="text" class="form-control" name="code" required placeholder="spp_bulanan" />
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description" rows="2"></textarea>
            </div>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label">Nominal default</label>
                <input type="number" class="form-control" name="default_amount" min="0" />
              </div>
              <div class="col-6">
                <label class="form-label">Urutan</label>
                <input type="number" class="form-control" name="sort_order" value="0" min="0" />
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

    <div class="modal fade eb-modal" id="modal-tambah-pengeluaran" tabindex="-1" aria-hidden="true" data-default-title="Tambah pengeluaran">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('keuangan.expenses.store') }}" data-store-action="{{ route('keuangan.expenses.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah pengeluaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="deskripsi-input" class="form-label">Deskripsi pengeluaran</label>
              <input
                type="text"
                class="form-control"
                id="deskripsi-input"
                name="description"
                placeholder="Contoh: Sewa ruangan"
                required
              />
            </div>
            <div class="mb-3">
              <label for="kategori-select" class="form-label">Kategori</label>
              <select class="form-select" id="kategori-select" name="category" required>
                <option value="" disabled selected>Pilih kategori…</option>
                <option value="Operasional">Operasional</option>
                <option value="Supplies">Supplies</option>
                <option value="Tenaga pengajar">Tenaga pengajar</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="jumlah-pengeluaran-input" class="form-label">Jumlah pengeluaran</label>
              <div class="input-group">
                <span class="input-group-text bg-light">Rp</span>
                <input type="number" class="form-control" id="jumlah-pengeluaran-input" name="amount" placeholder="2500000" min="0" required />
              </div>
            </div>
            <div class="mb-0">
              <label for="tanggal-pengeluaran-input" class="form-label">Tanggal pengeluaran</label>
              <input type="date" class="form-control" id="tanggal-pengeluaran-input" name="expense_date" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-eb">Simpan pengeluaran</button>
          </div>
        </form>
      </div>
    </div>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-crud.js') }}"></script>
    <script>
      const typeSelect = document.getElementById('payment-type-select');
      const amountInput = document.getElementById('payment-amount');
      if (typeSelect && amountInput) {
        typeSelect.addEventListener('change', () => {
          const def = typeSelect.selectedOptions[0]?.dataset.default;
          if (def && !amountInput.value) amountInput.value = def;
        });
        if (typeSelect.selectedOptions[0]?.dataset.default && !amountInput.value) {
          amountInput.value = typeSelect.selectedOptions[0].dataset.default;
        }
      }
    </script>
  </body>
</html>
