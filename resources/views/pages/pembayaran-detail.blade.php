<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail pembayaran {{ $payment->invoice_number }} — {{ $site->admin_brand }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-app">
    @include('partials.admin-nav', ['activeModule' => 'keuangan'])

    <main class="eb-main container-fluid px-3 px-md-4 py-4">
      @include('partials.flash')

      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
          <a href="{{ route('keuangan') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke keuangan
          </a>
          <h1 class="eb-page-title mb-1">Detail pembayaran</h1>
          <p class="eb-page-desc mb-0">
            <code>{{ $payment->invoice_number }}</code> · {{ $payment->paymentType?->name ?? '—' }}
          </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          @if ($payment->status !== 'lunas')
            @if ($duitkuConfigured)
            <form method="post" action="{{ route('keuangan.payments.link', $payment) }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-link-45deg me-1"></i> Buat link pembayaran
              </button>
            </form>
            <a class="btn btn-eb btn-sm" href="{{ route('keuangan.payments.duitku', $payment) }}">
              <i class="bi bi-credit-card me-1"></i> Buka halaman bayar
            </a>
            @endif
            <form method="post" action="{{ route('keuangan.payments.confirm', $payment) }}" class="d-inline">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Konfirmasi pembayaran lunas?')">
                <i class="bi bi-check2-circle me-1"></i> Konfirmasi lunas
              </button>
            </form>
          @endif
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-5">
          <div class="card table-card border-0 h-100">
            <div class="card-body">
              <h2 class="h6 fw-bold mb-3">Informasi tagihan</h2>
              <dl class="row small mb-0">
                <dt class="col-5 text-muted">Siswa</dt>
                <dd class="col-7 fw-semibold">{{ $payment->student->name }} <span class="text-muted">({{ $payment->student->nis }})</span></dd>
                <dt class="col-5 text-muted">Kelas</dt>
                <dd class="col-7">{{ $payment->student->kelompok?->name ?? '—' }}</dd>
                <dt class="col-5 text-muted">Jenis pembayaran</dt>
                <dd class="col-7">{{ $payment->paymentType?->name ?? '—' }}</dd>
                <dt class="col-5 text-muted">Periode</dt>
                <dd class="col-7">{{ $payment->period_label ?? '—' }}</dd>
                <dt class="col-5 text-muted">Nominal</dt>
                <dd class="col-7 fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                <dt class="col-5 text-muted">Jatuh tempo</dt>
                <dd class="col-7">{{ $payment->due_date?->locale('id')->translatedFormat('d M Y') ?? '—' }}</dd>
                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                  @if ($payment->status === 'lunas')
                    <span class="badge rounded-pill badge-soft-success">{{ $payment->statusLabel() }}</span>
                  @elseif ($payment->status === 'pending')
                    <span class="badge rounded-pill badge-soft-warning">{{ $payment->statusLabel() }}</span>
                  @elseif ($payment->status === 'belum_bayar')
                    <span class="badge rounded-pill badge-soft-danger">{{ $payment->statusLabel() }}</span>
                  @else
                    <span class="badge rounded-pill text-secondary bg-light">{{ $payment->statusLabel() }}</span>
                  @endif
                </dd>
                <dt class="col-5 text-muted">Kanal bayar</dt>
                <dd class="col-7">{{ $payment->payment_channel === 'duitku' ? 'Duitku' : 'Manual / transfer' }}</dd>
                <dt class="col-5 text-muted">Tanggal lunas</dt>
                <dd class="col-7">{{ $payment->paid_at?->locale('id')->translatedFormat('d M Y') ?? '—' }}</dd>
                <dt class="col-5 text-muted">Duitku ref</dt>
                <dd class="col-7"><code class="small">{{ $payment->duitku_reference ?? '—' }}</code></dd>
                <dt class="col-5 text-muted">Order ID</dt>
                <dd class="col-7"><code class="small">{{ $payment->merchant_order_id ?? '—' }}</code></dd>
              </dl>
              <hr />
              <h3 class="h6 fw-bold mb-2">Link pembayaran (untuk wali)</h3>
              @php
                $guardian = $payment->student->guardians->first();
                $waPhone = $guardian?->phone ? preg_replace('/[^0-9]/', '', $guardian->phone) : null;
                if ($waPhone && str_starts_with($waPhone, '0')) $waPhone = '62'.substr($waPhone, 1);
                $payLink = $payment->duitku_payment_url;
                $waText = $payLink ? rawurlencode("Halo {$guardian?->name}, ini link pembayaran {$payment->invoice_number} untuk {$payment->student->name}: {$payLink}") : null;
              @endphp
              <div class="input-group input-group-sm mb-2">
                <input id="payLink" type="text" class="form-control" value="{{ $payLink ?? '' }}" readonly placeholder="Klik 'Buat link pembayaran' untuk generate link" />
                <button class="btn btn-outline-secondary" type="button" id="btnCopyLink" @disabled(! $payLink)>Salin</button>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @if ($waPhone && $waText)
                  <a class="btn btn-sm btn-outline-success" href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" rel="noopener">
                    <i class="bi bi-whatsapp me-1"></i> Kirim via WhatsApp
                  </a>
                @endif
                @if ($payLink)
                  <a class="btn btn-sm btn-outline-primary" href="{{ $payLink }}" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka link
                  </a>
                @endif
              </div>
              @if ($payment->description)
              <hr />
              <p class="small text-muted mb-1">Keterangan</p>
              <p class="mb-0">{{ $payment->description }}</p>
              @endif
              @if ($payment->notes)
              <p class="small text-muted mb-1 mt-3">Catatan internal</p>
              <p class="mb-0 small">{{ $payment->notes }}</p>
              @endif
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="card table-card border-0 mb-4">
            <div class="card-body">
              <h2 class="h6 fw-bold mb-3">Bukti pembayaran</h2>
              @if ($payment->proofUrl())
                @if (str_ends_with(strtolower($payment->proof_path), '.pdf'))
                  <a href="{{ $payment->proofUrl() }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mb-3">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat bukti PDF
                  </a>
                @else
                  <a href="{{ $payment->proofUrl() }}" target="_blank" rel="noopener">
                    <img src="{{ $payment->proofUrl() }}" alt="Bukti pembayaran" class="img-fluid rounded border mb-3" style="max-height:280px;object-fit:contain" />
                  </a>
                @endif
              @else
                <p class="text-muted small mb-3">Belum ada bukti transfer diunggah.</p>
              @endif
              <form method="post" action="{{ route('keuangan.payments.proof', $payment) }}" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-8">
                  <label class="form-label small">Unggah / ganti bukti</label>
                  <input type="file" class="form-control form-control-sm" name="proof" accept="image/*,.pdf" required />
                </div>
                <div class="col-md-4">
                  <button type="submit" class="btn btn-eb btn-sm w-100">Simpan bukti</button>
                </div>
              </form>
            </div>
          </div>

          <div class="card table-card border-0">
            <div class="card-body">
              <h2 class="h6 fw-bold mb-3">Riwayat &amp; audit trail</h2>
              <div class="timeline">
                @forelse ($payment->histories as $history)
                <div class="border-start border-2 ps-3 pb-3 ms-2" style="border-color: var(--eb-primary) !important">
                  <p class="small text-muted mb-1">
                    {{ $history->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                    @if ($history->user)
                      · {{ $history->user->name ?? 'Staf' }}
                    @endif
                  </p>
                  <p class="fw-semibold mb-1">{{ str_replace('_', ' ', ucfirst($history->event)) }}</p>
                  <p class="small mb-1">{{ $history->message }}</p>
                  @if ($history->status_from || $history->status_to)
                  <p class="small text-muted mb-0">
                    Status: {{ $history->status_from ?? '—' }} → <strong>{{ $history->status_to ?? '—' }}</strong>
                  </p>
                  @endif
                </div>
                @empty
                <p class="text-muted small mb-0">Belum ada riwayat.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    @include('partials.footer-scripts')
    <script>
      (function () {
        const btn = document.getElementById('btnCopyLink');
        const input = document.getElementById('payLink');
        if (!btn || !input) return;

        btn.addEventListener('click', async () => {
          try {
            await navigator.clipboard.writeText(input.value);
            btn.textContent = 'Tersalin';
            setTimeout(() => (btn.textContent = 'Salin'), 1200);
          } catch (e) {
            input.select();
            document.execCommand('copy');
            btn.textContent = 'Tersalin';
            setTimeout(() => (btn.textContent = 'Salin'), 1200);
          }
        });
      })();
    </script>
  </body>
</html>
