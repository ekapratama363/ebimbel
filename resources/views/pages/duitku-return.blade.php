<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hasil pembayaran — {{ $site->site_name }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-landing">
    <main class="container px-3 py-5" style="max-width: 520px">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center p-4 p-md-5">
          @if ($payment && $payment->status === 'lunas')
            <i class="bi bi-check-circle-fill text-success display-4 mb-3"></i>
            <h1 class="h4 fw-bold mb-2">Pembayaran berhasil</h1>
            <p class="text-muted mb-4">Terima kasih. Pembayaran untuk <strong>{{ $payment->student->name }}</strong> telah kami terima.</p>
          @elseif ($payment && $payment->status === 'pending')
            <i class="bi bi-hourglass-split text-warning display-4 mb-3"></i>
            <h1 class="h4 fw-bold mb-2">Menunggu konfirmasi</h1>
            <p class="text-muted mb-4">Pembayaran sedang diproses. Status akan diperbarui setelah Duitku mengonfirmasi.</p>
          @else
            <i class="bi bi-x-circle-fill text-danger display-4 mb-3"></i>
            <h1 class="h4 fw-bold mb-2">Pembayaran belum selesai</h1>
            <p class="text-muted mb-4">Transaksi dibatalkan, gagal, atau belum dikonfirmasi.</p>
          @endif

          @if ($payment)
          <dl class="text-start small mb-4">
            <dt class="text-muted">Invoice</dt>
            <dd class="fw-semibold">{{ $payment->invoice_number }}</dd>
            <dt class="text-muted">Jenis</dt>
            <dd>{{ $payment->paymentType?->name }}</dd>
            <dt class="text-muted">Nominal</dt>
            <dd>Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
            <dt class="text-muted">Status</dt>
            <dd>{{ $payment->statusLabel() }}</dd>
          </dl>
          @endif

          <a href="{{ route('landing') }}" class="btn btn-eb">Kembali ke beranda</a>
        </div>
      </div>
    </main>
    @include('partials.footer-scripts')
  </body>
</html>
