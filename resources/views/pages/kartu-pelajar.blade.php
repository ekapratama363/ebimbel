<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kartu pelajar — {{ $student->name }}</title>
    @include('partials.head-assets')
    <style>
      .id-wrap { max-width: 980px; }
      .id-sheet {
        min-height: calc(297mm - 20mm);
        display: grid;
        place-items: center;
      }
      .id-card {
        width: 85.6mm;
        height: 54mm;
        border: 1px solid var(--eb-border);
        border-radius: 6mm;
        overflow: hidden;
        box-shadow: var(--eb-shadow);
        background: linear-gradient(145deg, rgba(37, 99, 235, 0.07), rgba(245, 158, 11, 0.06));
        position: relative;
        display: flex;
        flex-direction: column;
      }
      .id-card::before {
        content: "";
        position: absolute;
        inset: -10mm;
        background:
          radial-gradient(circle at 25% 30%, rgba(37, 99, 235, 0.18), transparent 55%),
          radial-gradient(circle at 75% 60%, rgba(245, 158, 11, 0.14), transparent 55%),
          radial-gradient(circle at 55% 20%, rgba(255, 255, 255, 0.7), transparent 55%);
        pointer-events: none;
      }
      .id-head {
        background: linear-gradient(120deg, var(--eb-primary), #1d4ed8);
        color: #fff;
        padding: 3.2mm 4mm;
        position: relative;
        z-index: 1;
      }
      .id-body {
        padding: 3.2mm 4mm 2.8mm;
        background: #fff;
        position: relative;
        z-index: 1;
        flex: 1;
        min-height: 0;
      }
      .id-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.28);
        font-weight: 700;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
      }
      .id-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2.2mm; }
      .id-field { border: 1px solid var(--eb-border); border-radius: 3.5mm; padding: 2.2mm 2.4mm; }
      .id-label { font-size: 0.6rem; color: var(--eb-text-muted); margin-bottom: 1mm; }
      .id-value { font-weight: 700; }
      .id-front { display: grid; grid-template-columns: 18mm 1fr; gap: 3mm; }
      .id-photo {
        width: 18mm;
        height: 22mm;
        border-radius: 3mm;
        border: 1px solid var(--eb-border);
        background: #f3f4f6;
        overflow: hidden;
        display: grid;
        place-items: center;
      }
      .id-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
      .id-photo-fallback {
        font-weight: 900;
        color: #64748b;
        letter-spacing: 0.08em;
        font-size: 0.8rem;
      }
      .id-brand {
        display: flex;
        align-items: center;
        gap: 2mm;
      }
      .id-logo {
        width: 10mm;
        height: 10mm;
        object-fit: contain;
        filter: drop-shadow(0 3px 10px rgba(0, 0, 0, 0.15));
      }
      .id-school { font-weight: 800; font-size: 0.9rem; line-height: 1.05; margin: 0; }
      .id-sub { margin: 0; font-size: 0.65rem; opacity: 0.85; }
      .id-name { font-weight: 900; font-size: 0.92rem; line-height: 1.05; margin: 0; }
      .id-note { font-size: 0.6rem; color: var(--eb-text-muted); margin: 0; }
      .id-table { width: 100%; border-collapse: collapse; font-size: 0.68rem; }
      .id-table td { padding: 0.55mm 0; vertical-align: top; line-height: 1.15; }
      .id-k { width: 26mm; color: var(--eb-text-muted); }
      .id-sep { width: 3mm; color: var(--eb-text-muted); }
      .id-v { font-weight: 800; }
      .id-barcode-wrap {
        margin-top: 1.6mm;
        border: 1px solid var(--eb-border);
        border-radius: 3mm;
        padding: 1mm 1.6mm;
        background: #fff;
      }
      .id-barcode {
        width: 100%;
        height: 8mm;
        object-fit: contain;
        display: block;
      }
      .id-footer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 6mm;
        background: linear-gradient(120deg, #312e81, #4c1d95);
        color: rgba(255,255,255,0.92);
        font-weight: 700;
        font-size: 0.62rem;
        display: grid;
        place-items: center;
        letter-spacing: 0.02em;
      }
      .id-footer + * { padding-bottom: 6mm; }
      .id-v--address {
        font-weight: 700;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      @media print {
        @page { size: A4; margin: 10mm; }
        * {
          -webkit-print-color-adjust: exact !important;
          print-color-adjust: exact !important;
        }
        body { background: #fff !important; }
        .no-print { display: none !important; }
        .id-wrap { max-width: none; }
        .id-card { box-shadow: none; }
        .id-sheet { min-height: auto; }
      }
    </style>
  </head>
  <body class="eb-app">
    <main class="container id-wrap px-3 px-md-4 py-4">
      <div class="d-flex justify-content-between align-items-center gap-2 no-print mb-3">
        <a href="{{ route('kesiswaan') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <button class="btn btn-sm btn-eb" onclick="window.print()">
          <i class="bi bi-printer me-1"></i> Cetak
        </button>
      </div>

      <div class="id-sheet">
        <section class="id-card">
          <div class="id-head d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="id-brand">
              <img class="id-logo" src="{{ $site->logoUrl() }}" alt="" />
              <div>
                <p class="id-school">{{ $site->site_name }}</p>
                <p class="id-sub">Kartu Pelajar</p>
              </div>
            </div>
            <div class="id-pill">
              <i class="bi bi-person-badge"></i>
              <span>{{ $student->card_number }}</span>
            </div>
          </div>
          <div class="id-body">
            @php
              $initials = collect(explode(' ', $student->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->join('');
            @endphp
            <div class="id-front">
              <div>
                <div class="id-photo mb-2">
                  @if ($student->photoUrl())
                    <img src="{{ $student->photoUrl() }}" alt="Foto {{ $student->name }}" />
                  @else
                    <div class="id-photo-fallback">{{ strtoupper($initials) }}</div>
                  @endif
                </div>
                <p class="id-note">ID: <strong class="text-body">{{ $student->card_number }}</strong></p>
              </div>
              <div>
                <p class="id-name mb-2">{{ $student->name }}</p>
                <table class="id-table">
                  <tr>
                    <td class="id-k">NIS</td><td class="id-sep">:</td><td class="id-v">{{ $student->nis }}</td>
                  </tr>
                  <tr>
                    <td class="id-k">T.T. Lahir</td><td class="id-sep">:</td>
                    <td class="id-v">
                      {{ $student->birth_place ? strtoupper($student->birth_place).',' : '—' }}
                      {{ $student->birth_date?->locale('id')->translatedFormat('d F Y') ?? '' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="id-k">Jns. Kelamin</td><td class="id-sep">:</td><td class="id-v">{{ $student->gender ?? '—' }}</td>
                  </tr>
                  <tr>
                    <td class="id-k">Agama</td><td class="id-sep">:</td><td class="id-v">{{ $student->religion ?? '—' }}</td>
                  </tr>
                  <tr>
                    <td class="id-k">Alamat</td><td class="id-sep">:</td><td class="id-v id-v--address">{{ $student->address ?? '—' }}</td>
                  </tr>
                </table>

                <div class="id-barcode-wrap">
                  <img class="id-barcode" alt="Barcode" src="data:image/png;base64,{{ $barcodePngBase64 }}" />
                  <div class="text-center text-muted" style="font-size:.58rem; margin-top: 0.5mm">{{ $student->nis }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="id-footer">Berlaku selama menjadi siswa {{ $site->site_name }}</div>
        </section>
      </div>
    </main>

    @include('partials.footer-scripts')
  </body>
</html>

