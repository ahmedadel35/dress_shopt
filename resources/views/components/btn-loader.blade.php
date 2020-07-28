@props(['showIf', 'id'])

<span @isset($showIf) :class="{{$showIf}} ? '' : 'd-none'" @endisset
    class="@isset($id)d-none @endisset spinner-border spinner-border-sm align-middle pr-1 mx-1" role="status" aria-hidden="true"
    id="{{$id ?? ''}}"></span>