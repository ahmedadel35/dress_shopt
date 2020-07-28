<x-collapse :title="__('order.qty'). ': '. $order->qty">
    <p>
        <strong >
            {{__('order.status')}}:
            @switch($order->status)
            @case('processing')
            <i id="icon{{$order->enc_id}}" class="fas fa-truck-loading mx-1 text-primary"></i>
            @break
            @case('completed')
            <i id="icon{{$order->enc_id}}" class="fas fa-check text-success"></i>
            @break
            @default
            <i id="icon{{$order->enc_id}}" class="fas fa-truck-loading mx-1 text-primary"></i>
            @endswitch
            {{__('order.'.$order->status)}}
        </strong>
    </p>
    <p>
        {{__('order.paymentMethod')}}:
        @if($order->paymentMethod === 'accept')
        <span class="align-top">
            Accept-Pay
        </span>
        @else
        {{__('order.cashOnDelivery')}}
        @endif
    </p>
    <p>
        {{__('order.mail.payStat')}}
        @if ($order->paymentStatus)
        <span class="text-success">
            <i class="fas fa-check mx-1"></i>
            {{__('order.mail.payDone')}}
        </span>
        @else
        <span class="text-danger">
            <i class="fas fa-times mx-1"></i>
            {{__('order.mail.payFail')}}
        </span>
        @endif
    </p>
</x-collapse>