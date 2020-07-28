@props(['msg', 'type' => 'danger', 'cls' => 'px-4 py-3', 'icon' => 'times'])

@if ($errors->any() || isset($msg))
<div class="alert alert-{{$type}} text-center col-12">
    <p class="rounded-circle overflow-hidden mx-auto border border-dark animate__animated animate__bounceIn animate__repeat-2" style="width: 50px;height: 50px;">
        <i
            class="fa fas fa-{{$icon}} fa-2x mt-2"></i>
    </p>
    @isset ($msg)
    <strong class="d-block">
        {{$msg}}
        {{$slot}}
    </strong>
    @else
    @foreach ($errors->all() as $err)
    <strong class="d-block">{{$err}}</strong>
    @endforeach
    @endisset
</div>
@endif