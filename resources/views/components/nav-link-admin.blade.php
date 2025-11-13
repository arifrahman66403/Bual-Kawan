@props(['href' => '#', 'class' => '', 'icon' => '', 'label' => '', 'active' => false, 'id' => ''])

<a href="{{ $href }}"
   class="{{ $class }} {{ $active ? 'active' : '' }}"
   id="{{ $id }}">
    <i class="bi {{ $icon }}"></i> {{ $label }}
</a>