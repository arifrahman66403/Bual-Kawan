@props(['href' => '#', 'icon' => '', 'label' => '', 'active' => false, 'id' => ''])

<a href="{{ $href }}"
   class="nav-link-icon {{ $active ? 'active' : '' }}"
   id="nav-{{ $id }}">
    <i class="bi {{ $icon }}"></i> {{ $label }}
</a>