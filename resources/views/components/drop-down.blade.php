@props([
'id' => 'dropdown' . random_int(1, 5555555),
'class' => ''
])

<li class="nav-item dropdown float-left d-inline text-light">
    <a id="{{$id}}" class="nav-link dropdown-toggle text-light {{$class}}"
        href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false" {{$attributes}}>
        {{$link}}
    </a>

    {{$ndlink ?? ''}}
    <div class="cart-drop dropdown-menu dropdown-menu-right" aria-labelledby="{{$id}}">
        {{$slot}}
    </div>
</li>