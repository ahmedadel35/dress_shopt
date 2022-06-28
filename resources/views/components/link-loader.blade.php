@props([
'spinnerId' => bin2hex(random_bytes(6)),
'href',
'class' => 'btn-primary',
'icon' => '',
'outside' => false
])

<a {{$attributes}} @if(isset($outside) && $outside) href="{{$href}}" @else
    href="/{{app()->getLocale()}}/{{$href}}" @endif class="btn {{$class}}"
    onclick="document.getElementById('{{$spinnerId}}').classList.remove('d-none')" aria-label="{{$href}}">
    <span class="d-none spinner-border spinner-border-sm align-middle pr-1 mx-1"
        role="status" aria-hidden="true" id="{{$spinnerId}}"></span>
    @if (strlen($icon))
    <i class="fas {{$icon}} mx-1"></i>
    @endif
    <span class="after">
        {{$slot}}
    </span>
</a>