@if (session('status'))
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    {{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
@endif
@if ($errors->any())
  <div class="alert alert-danger mb-4" role="alert">
    <ul class="mb-0 ps-3">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
