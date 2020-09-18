@props([
'id' => 'collapse' . random_int(1, 9999999),
'title' => ''
])

<a {{$attributes}} data-toggle="collapse" href="#{{$id}}" role="button"
    aria-expanded="false" aria-controls="{{$id}}" data-target="#{{$id}}">
    {{$title}}
    <i class="tranistion fas fa-arrow-right" style="font-size: smaller"></i>
</a>

<div class="collapse" id="{{$id}}" aria-labelledby="{{$id}}">
    {{$slot}}
</div>