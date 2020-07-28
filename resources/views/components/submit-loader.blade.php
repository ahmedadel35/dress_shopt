@props([
'cls' => 'btn-primary',
'icon' => 'save',
'id' => bin2hex(random_bytes(5)),
'type' => 'button'
])

<button type="{{$type}}" class="btn {{$cls}}"
    onclick="document.getElementById('{{$id}}').classList.remove('d-none')">
    <x-btn-loader id="{{$id}}">
    </x-btn-loader>
    <span class="after">
        <i class="fas fa-{{$icon}} mx-1"></i>
        {{$slot}}
    </span>
</button>