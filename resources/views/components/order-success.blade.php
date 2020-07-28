@props(['vuejs' => true, 'orderId' => 1])

<div class="alert alert-success text-center col-12">
    <p>
        <i
            class="fa fas fa-check border border-dark rounded-circle px-3 py-3 fa-2x animate__animated animate__bounceIn animate__repeat-3"></i>
    </p>
    <strong class="d-block">
        {{__('order.yoursId')}} <span dir="ltr">@if ($vuejs)#@{{h.d.order.id}}@else #{{$orderId}}  @endif</span> <br>
        <p class="my-2">{{__('order.inform')}}</p>
        {{__('order.youCanCheck')}}
        <x-link-loader href="order/track" icon="fa-chart-bar"
            class="btn-clear font-weight-bold" style="color: inherit">
            {{__('order.tracker.txt')}}
        </x-link-loader>
    </strong>
</div>