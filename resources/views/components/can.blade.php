@props(['permission'])

@if ($can($permission))
  {{ $slot }}
@endif
