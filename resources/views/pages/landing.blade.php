<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="description"
      content="{{ $site->meta_description }}"
    />
    <title>{{ $site->landing_title }}</title>
    @include('partials.head-assets')
  </head>
  <body class="eb-landing">
    @php
      $hero = $sections->get('hero');
      $heroCard = $sections->get('hero_card');
      $strip = $sections->get('strip');
      $about = $sections->get('about');
      $aboutSidebar = $sections->get('about_sidebar');
      $programIntro = $sections->get('program_intro');
      $mengapaIntro = $sections->get('mengapa_intro');
      $orangTua = $sections->get('orang_tua');
      $ortuPreview = $sections->get('ortu_preview');
      $kemitraan = $sections->get('kemitraan');
      $cta = $sections->get('cta');
      $footerTagline = $sections->get('footer_tagline');
      $landIcon = fn (?string $icon) => $icon ? preg_replace('/^bi-/', '', $icon) : null;
    @endphp
    <header class="eb-land-header">
      <nav class="navbar navbar-expand-lg navbar-dark navbar-eb shadow-sm">
        <div class="container px-3 px-md-4">
          <a class="navbar-brand py-1" href="{{ route('landing') }}" aria-label="{{ $site->site_name }} — beranda">
            <img
              class="eb-logo"
              src="{{ $site->logoUrl() }}"
              alt="{{ $site->site_name }}"
              width="220"
              height="66"
              decoding="async"
            />
          </a>
          <button
            class="navbar-toggler border-0 shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#landNav"
            aria-controls="landNav"
            aria-expanded="false"
            aria-label="Buka menu"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="landNav">
            <ul class="navbar-nav ms-lg-auto align-items-lg-center gap-lg-1 mb-3 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link eb-land-nav-link" href="#tentang">Tentang</a>
              </li>
              <li class="nav-item">
                <a class="nav-link eb-land-nav-link" href="#program">Program</a>
              </li>
              <li class="nav-item">
                <a class="nav-link eb-land-nav-link" href="#mengapa">Mengapa kami</a>
              </li>
              <li class="nav-item">
                <a class="nav-link eb-land-nav-link" href="#orang-tua">Orang tua</a>
              </li>
              <li class="nav-item">
                <a class="nav-link eb-land-nav-link" href="#kemitraan">Buka cabang</a>
              </li>
            </ul>
            <div class="d-flex flex-column flex-lg-row gap-2 ms-lg-3">
              <a class="btn btn-sm btn-outline-light" href="{{ route('orang-tua') }}">Portal wali</a>
              <a class="btn btn-sm btn-eb" href="{{ route('login') }}">Masuk staf</a>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <!-- Banner slider -->
      <section class="eb-land-banner" aria-label="Banner utama">
        <div
          id="landBanner"
          class="carousel slide carousel-fade eb-land-carousel"
          data-bs-ride="carousel"
          data-bs-interval="5500"
          data-bs-pause="hover"
        >
          <div class="carousel-indicators eb-land-carousel-indicators">
            @foreach ($slides as $slide)
              <button
                type="button"
                data-bs-target="#landBanner"
                data-bs-slide-to="{{ $loop->index }}"
                class="{{ $loop->first ? 'active' : '' }}"
                @if ($loop->first) aria-current="true" @endif
                aria-label="Slide {{ $loop->iteration }}"
              ></button>
            @endforeach
          </div>
          <div class="carousel-inner">
            @foreach ($slides as $slide)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
              @php $slideImage = $slide->imageUrl(); @endphp
              <div class="eb-land-slide eb-land-slide--{{ $slide->slide_style }}">
                <div class="eb-land-slide-shapes" aria-hidden="true">
                  <span class="eb-land-shape eb-land-shape--a"></span>
                  <span class="eb-land-shape eb-land-shape--b"></span>
                  @if ($slide->slide_style == 1 || $slide->slide_style == 3)
                    <span class="eb-land-shape eb-land-shape--c"></span>
                  @endif
                </div>
                <div class="container px-3 px-md-4">
                  <div class="row align-items-center min-vh-50 g-4">
                    <div class="{{ ($slide->slide_style == 1 || $slideImage) ? 'col-lg-7' : 'col-lg-8' }} eb-land-slide-content">
                      <p class="eb-land-slide-tag">{{ $slide->tag }}</p>
                      <h2 class="eb-land-slide-title">{{ $slide->title }}</h2>
                      <p class="eb-land-slide-desc">{{ $slide->description }}</p>
                      @if ($slide->cta_text && $slide->cta_link)
                        <a class="btn btn-light btn-lg rounded-pill px-4 fw-semibold" href="{{ $slide->cta_link }}">
                          {{ $slide->cta_text }}
                        </a>
                      @endif
                    </div>
                    @if ($slide->slide_style == 1 || $slideImage)
                    <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                      <img
                        class="eb-land-slide-logo eb-float{{ $slideImage ? ' eb-land-slide-photo' : '' }}"
                        src="{{ $slideImage ?? $site->logoUrl() }}"
                        alt=""
                        width="400"
                        height="400"
                        decoding="async"
                      />
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <button class="carousel-control-prev eb-land-carousel-control" type="button" data-bs-target="#landBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
          </button>
          <button class="carousel-control-next eb-land-carousel-control" type="button" data-bs-target="#landBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Berikutnya</span>
          </button>
        </div>
      </section>

      <section class="eb-land-hero" aria-labelledby="land-hero-title">
        <div class="container px-3 px-md-4">
          <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7 eb-reveal">
              <p class="eb-land-kicker mb-3">
                <span class="eb-land-kicker-dot" aria-hidden="true"></span>
                {{ $hero?->kicker ?? 'Bimbingan Minat Belajar Anak (BiMBA)' }}
              </p>
              <h1 id="land-hero-title" class="eb-land-title">
                {{ $hero?->title ?? 'Belajar seru, tumbuh' }}
                <span class="eb-land-title-accent">{{ $hero?->subtitle ?? 'percaya diri' }}</span>
                bersama {{ $site->site_name }}
              </h1>
              <p class="eb-land-lead">
                <strong>{{ $site->site_name }}</strong>
                {{ $hero?->body ?? 'adalah lembaga bimbingan belajar anak dengan pendekatan bermain sambil belajar — seperti sekolah BiMBA: hangat, terstruktur, dan dekat dengan orang tua. Kami menemani anak membangun fondasi akademik sekaligus minat belajar sejak dini.' }}
              </p>
              <div class="d-flex flex-wrap gap-2 mb-4">
                @if ($hero?->cta_primary_text)
                <a class="btn btn-eb btn-lg px-4 rounded-pill" href="{{ $hero->cta_primary_link ?? '#program' }}">
                  <i class="bi bi-mortarboard me-1"></i> {{ $hero->cta_primary_text }}
                </a>
                @endif
                @if ($hero?->cta_secondary_text)
                <a
                  class="btn btn-outline-secondary btn-lg px-4 rounded-pill eb-land-btn-outline"
                  href="{{ $hero->cta_secondary_link ?? '#daftar' }}"
                >
                  {{ $hero->cta_secondary_text }}
                </a>
                @endif
              </div>
              <ul class="eb-land-hero-points list-unstyled mb-0" aria-label="Keunggulan {{ $site->site_name }}">
                @foreach ($heroPoints as $point)
                <li><i class="bi bi-{{ $landIcon($point->icon) }}" aria-hidden="true"></i> {{ $point->title }}</li>
                @endforeach
              </ul>
            </div>
            <div class="col-lg-5 eb-reveal eb-reveal--delay-2">
              <div class="eb-land-hero-card">
                <div class="eb-land-hero-card-head">
                  <img
                    class="eb-land-hero-logo"
                    src="{{ $site->logoUrl() }}"
                    alt=""
                    width="1024"
                    height="1024"
                    decoding="async"
                  />
                  <p class="small text-muted mb-0 mt-2 text-center fw-semibold">
                    {{ $heroCard?->title ?? 'Where fun meets learning' }}
                  </p>
                </div>
                <div class="eb-land-hero-card-body">
                  <p class="small fw-semibold text-uppercase text-muted mb-3" style="letter-spacing: 0.06em">
                    {{ $heroCard?->kicker ?? 'Usia & jenjang' }}
                  </p>
                  <div class="d-grid gap-2">
                    @foreach ($quickLinks as $link)
                    <div class="eb-land-quick-link eb-land-quick-link--static">
                      <i class="bi bi-{{ $landIcon($link->icon) }}"></i>
                      <span>
                        <strong>{{ $link->title }}</strong>
                        @if ($link->description)<small>{{ $link->description }}</small>@endif
                      </span>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="eb-land-strip eb-reveal" aria-label="Tentang singkat">
        <div class="container px-3 px-md-4">
          <div
            class="eb-land-strip-inner d-flex flex-column flex-md-row align-items-md-center gap-3 justify-content-between"
          >
            <p class="mb-0 small">
              <i class="bi bi-building me-1 text-primary"></i>
              {{ $site->site_name }} dikelola di bawah <strong>{{ $site->organization_name }}</strong>
              {{ $strip?->body ?? '— mitra belajar terpercaya keluarga yang menginginkan pendidikan anak yang hangat dan bermakna.' }}
            </p>
            @if ($strip?->cta_primary_text)
            <a class="btn btn-sm btn-outline-primary flex-shrink-0 rounded-pill" href="{{ $strip->cta_primary_link ?? '#tentang' }}">
              {{ $strip->cta_primary_text }}
            </a>
            @endif
          </div>
        </div>
      </section>

      <section id="tentang" class="eb-land-section" aria-labelledby="land-tentang-title">
        <div class="container px-3 px-md-4">
          <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6 eb-reveal">
              <p class="eb-land-section-kicker">Tentang {{ $site->site_name }}</p>
              <h2 id="land-tentang-title" class="eb-land-section-title mb-3">
                {{ $about?->title ?? 'Bimbel anak yang dekat dengan keluarga' }}
              </h2>
              <p class="text-muted mb-3">
                Seperti lembaga BiMBA pada umumnya, {{ $site->site_name }}
                {{ $about?->body ?? 'fokus pada bimbingan minat belajar anak — bukan sekadar mengajar materi, tetapi membantu anak menemukan cara belajar yang cocok, menyenangkan, dan berkelanjutan.' }}
              </p>
              @if ($about?->body_secondary)
              <p class="text-muted mb-4">{{ $about->body_secondary }}</p>
              @endif
              <div class="row g-3">
                @foreach ($stats as $stat)
                <div class="col-sm-4">
                  <div class="eb-land-stat eb-land-stat--animated">
                    @php
                      $count = ($loop->first && $stat->suffix === '+') ? $programCount : ($stat->value ?? 0);
                    @endphp
                    <p class="eb-land-stat-num"><span data-count="{{ $count }}" data-suffix="{{ $stat->suffix }}">0</span></p>
                    <p class="small text-muted mb-0">{{ $stat->title }}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="col-lg-6 eb-reveal eb-reveal--delay-2">
              <div class="eb-land-about-card">
                <h3 class="h5 fw-bold mb-3">{{ $aboutSidebar?->title ?? 'Visi kami' }}</h3>
                <p class="text-muted mb-4">{{ $aboutSidebar?->body }}</p>
                <h3 class="h6 fw-bold mb-2">{{ $aboutSidebar?->subtitle ?? 'Nilai yang kami bawa ke kelas' }}</h3>
                <ul class="eb-land-ortu-list list-unstyled mb-0">
                  @foreach ($aboutValues as $value)
                  <li><i class="bi bi-{{ $landIcon($value->icon) }}" aria-hidden="true"></i> {{ $value->title }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="program" class="eb-land-section eb-land-section--alt" aria-labelledby="land-program-title">
        <div class="container px-3 px-md-4">
          <div class="eb-land-section-head text-center mx-auto eb-reveal">
            <p class="eb-land-section-kicker">{{ $programIntro?->kicker ?? 'Program belajar' }}</p>
            <h2 id="land-program-title" class="eb-land-section-title">{{ $programIntro?->title ?? 'Pilih program sesuai usia anak' }}</h2>
            <p class="eb-land-section-desc">{{ $programIntro?->body }}</p>
          </div>
          <div class="row g-3 g-lg-4">
            @foreach ($landingPrograms as $program)
            <div class="col-md-6 col-xl-3 eb-reveal eb-reveal--delay-{{ min($loop->iteration, 4) }}">
              <article class="eb-land-module-card h-100">
                <div class="eb-land-module-icon eb-land-module-icon--{{ $program->color_variant }}">
                  <i class="bi bi-{{ $landIcon($program->icon) }}" aria-hidden="true"></i>
                </div>
                <h3 class="h5 fw-bold mb-2">{{ $program->title }}</h3>
                <p class="small text-muted mb-0">{{ $program->description }}</p>
              </article>
            </div>
            @endforeach
          </div>
        </div>
      </section>

      <section id="mengapa" class="eb-land-section" aria-labelledby="land-mengapa-title">
        <div class="container px-3 px-md-4">
          <div class="eb-land-section-head text-center mx-auto eb-reveal">
            <p class="eb-land-section-kicker">Mengapa {{ $site->site_name }}</p>
            <h2 id="land-mengapa-title" class="eb-land-section-title">{{ $mengapaIntro?->title ?? 'Lebih dari sekadar les tambahan' }}</h2>
            <p class="eb-land-section-desc">{{ $mengapaIntro?->body }}</p>
          </div>
          <div class="row g-3">
            @foreach ($features as $feature)
            <div class="col-sm-6 col-lg-4 eb-reveal">
              <div class="eb-land-feature">
                <span class="eb-land-feature-code"><i class="bi bi-{{ $landIcon($feature->icon) }}"></i></span>
                <h3 class="h6 fw-bold mb-1">{{ $feature->title }}</h3>
                <p class="small text-muted mb-0">{{ $feature->description }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </section>

      <section id="orang-tua" class="eb-land-section eb-land-section--alt" aria-labelledby="land-ortu-title">
        <div class="container px-3 px-md-4">
          <div class="eb-land-ortu row align-items-center g-4">
            <div class="col-lg-6 eb-reveal">
              <p class="eb-land-section-kicker">{{ $orangTua?->kicker ?? 'Untuk orang tua' }}</p>
              <h2 id="land-ortu-title" class="eb-land-section-title mb-3">
                {{ $orangTua?->title ?? 'Tetap dekat dengan perjalanan belajar anak' }}
              </h2>
              <p class="text-muted mb-4">
                Setelah setiap sesi, guru {{ $site->site_name }} {{ $orangTua?->body }}
              </p>
              <ul class="eb-land-ortu-list list-unstyled mb-4">
                @foreach ($ortuPoints as $point)
                <li><i class="bi bi-{{ $landIcon($point->icon) }}" aria-hidden="true"></i> {{ $point->title }}</li>
                @endforeach
              </ul>
              @if ($orangTua?->cta_primary_text)
              <a class="btn btn-eb rounded-pill px-4" href="{{ $orangTua->cta_primary_link ?? route('orang-tua') }}">
                <i class="bi bi-person-hearts me-1"></i> {{ $orangTua->cta_primary_text }}
              </a>
              @endif
            </div>
            <div class="col-lg-6 eb-reveal eb-reveal--delay-2">
              <div class="eb-land-ortu-preview eb-land-ortu-preview--pulse">
                <div class="eb-land-ortu-preview-head">
                  <span class="badge badge-eb rounded-pill">{{ $ortuPreview?->kicker ?? 'Laporan harian' }}</span>
                  <span class="small text-muted">{{ $ortuPreview?->extra_text }}</span>
                </div>
                <p class="mb-2 fw-semibold">{{ $ortuPreview?->title }}</p>
                <p class="small text-muted mb-3">{{ $ortuPreview?->body }}</p>
                <div class="d-flex flex-wrap gap-2 small">
                  <span class="eb-land-media-chip"><i class="bi bi-image me-1"></i>2 foto kegiatan</span>
                  <span class="eb-land-media-chip"><i class="bi bi-play-btn me-1"></i>1 video singkat</span>
                  <span class="badge rounded-pill badge-soft-success">Sudah dibaca wali</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="kemitraan" class="eb-land-section" aria-labelledby="land-kemitraan-title">
        <div class="container px-3 px-md-4">
          <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-lg-5 eb-reveal">
              <p class="eb-land-section-kicker">{{ $kemitraan?->kicker ?? 'Kemitraan cabang' }}</p>
              <h2 id="land-kemitraan-title" class="eb-land-section-title mb-3">
                Bawa {{ $site->site_name }} {{ $kemitraan?->title ?? 'ke kota Anda' }}
              </h2>
              <p class="text-muted mb-4">{{ $kemitraan?->body }}</p>
              <ul class="eb-land-ortu-list list-unstyled mb-4">
                @foreach ($kemitraanPoints as $point)
                <li><i class="bi bi-{{ $landIcon($point->icon) }}" aria-hidden="true"></i> {{ $point->title }}</li>
                @endforeach
              </ul>
              @if ($kemitraan?->contact_email)
              <a class="btn btn-eb rounded-pill px-4" href="{{ $kemitraan->cta_primary_link ?? 'mailto:'.$kemitraan->contact_email }}">
                <i class="bi bi-envelope-heart me-1"></i> {{ $kemitraan->cta_primary_text ?? $kemitraan->contact_email }}
              </a>
              @endif
            </div>
            <div class="col-lg-7 eb-reveal eb-reveal--delay-2">
              <p class="small fw-bold text-uppercase text-muted mb-3" style="letter-spacing: 0.06em">
                Alur membuka cabang
              </p>
              <div class="eb-land-steps">
                @foreach ($steps as $step)
                <article class="eb-land-step">
                  <span class="eb-land-step-num">{{ $loop->iteration }}</span>
                  <div>
                    <h3 class="h6 fw-bold mb-1">{{ $step->title }}</h3>
                    <p class="small text-muted mb-0">{{ $step->description }}</p>
                  </div>
                </article>
                @endforeach
              </div>
              <div class="eb-land-partner-card mt-4">
                <div class="row g-3 align-items-center">
                  <div class="col-md-8">
                    <h3 class="h6 fw-bold mb-1">{{ $kemitraan?->subtitle ?? 'Siap diskusi lebih lanjut?' }}</h3>
                    <p class="small text-muted mb-0">{{ $kemitraan?->body_secondary }}</p>
                  </div>
                  @if ($kemitraan?->contact_phone)
                  <div class="col-md-4 text-md-end">
                    <a class="btn btn-outline-primary rounded-pill w-100 w-md-auto" href="{{ $kemitraan->cta_secondary_link ?? 'tel:'.$kemitraan->contact_phone }}">
                      <i class="bi bi-whatsapp me-1"></i> {{ $kemitraan->cta_secondary_text ?? 'WhatsApp kemitraan' }}
                    </a>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="daftar" class="eb-land-cta eb-reveal" aria-labelledby="land-cta-title">
        <div class="container px-3 px-md-4 text-center">
          <h2 id="land-cta-title" class="h3 fw-bold text-white mb-2">
            {{ ($cta?->title ?? 'Siap kenalan').' dengan '.$site->site_name }}?
          </h2>
          <p class="text-white-50 mb-4 mx-auto" style="max-width: 46ch">{{ $cta?->body }}</p>
          <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
            @if ($cta?->contact_email)
            <a class="btn btn-light btn-lg rounded-pill px-4 fw-semibold" href="mailto:{{ $cta->contact_email }}">
              <i class="bi bi-envelope me-1"></i> {{ $cta->contact_email }}
            </a>
            @endif
            @if ($cta?->contact_phone)
            <a class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold" href="tel:{{ $cta->contact_phone }}">
              <i class="bi bi-whatsapp me-1"></i> Hubungi via WhatsApp
            </a>
            @endif
          </div>
          @if ($cta?->extra_text)
          <p class="small text-white-50 mb-0">{{ $cta->extra_text }}</p>
          @endif
        </div>
      </section>
    </main>

    <footer class="eb-land-footer">
      <div class="container px-3 px-md-4">
        <div class="row g-4">
          <div class="col-md-5">
            <img
              class="eb-land-footer-logo mb-3"
              src="{{ $site->logoUrl() }}"
              alt=""
              width="180"
              height="54"
              decoding="async"
            />
            <p class="small text-muted mb-0">
              <strong>{{ $site->site_name }}</strong> — Bimbingan Minat Belajar Anak (BiMBA)<br />
              @if ($site->organization_name){{ $site->organization_name }}<br />@endif
              @if ($footerTagline?->extra_text)<em>{{ $footerTagline->extra_text }}</em>@endif
            </p>
          </div>
          <div class="col-md-4">
            <p class="small fw-bold mb-2">Tautan</p>
            <ul class="eb-land-footer-links list-unstyled mb-0 small">
              <li><a href="#tentang">Tentang kami</a></li>
              <li><a href="#program">Program belajar</a></li>
              <li><a href="#orang-tua">Informasi orang tua</a></li>
              <li><a href="#kemitraan">Buka cabang</a></li>
              <li><a href="{{ route('orang-tua') }}">Portal wali</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <p class="small fw-bold mb-2">Internal</p>
            <ul class="eb-land-footer-links list-unstyled mb-0 small">
              <li><a href="{{ route('login') }}">Masuk staf</a></li>
            </ul>
          </div>
        </div>
        <hr class="my-4 opacity-10" />
        <p class="small text-muted text-center mb-0">
          © {{ $site->site_name }}@if ($site->organization_name) · {{ $site->organization_name }}@endif
        </p>
      </div>
    </footer>

    @include('partials.footer-scripts')
    <script src="{{ asset('ebimbel-landing.js') }}"></script>
  </body>
</html>
