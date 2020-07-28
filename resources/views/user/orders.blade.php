<div class="row">
    @forelse ($orders as $order)
    <div class="ordercard col-sm-6 col-md-4 mb-3">
        <div class="card p-0">
            <div class="card-header bg-primary text-light" dir="ltr">
                #{{$order->enc_id}}
            </div>
            <div class="row mx-0">
                @foreach ($order->items as $item)
                @if($loop->index < 3) <div class="card p-0 col-4 border-0">
                    <img src="{{$item->product->images[0]}}"
                        class="card-img-top" style="max-height:150px"
                        alt="#{{$item->product->title}}">
            </div>
            @endif
            @endforeach
        </div>
        <div class="card-body">
            <h5 class="text-primary">
                LE {{$order->total}}
            </h5>
            @include('user.orders-list.details')
            <p>
                @include('user.orders-list.address')
            </p>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary mb-2"
                v-on:click.prevent="h.d.openItemsModal('{{$order->items->toJson()}}', '{{$order->enc_id}}')">
                <span class="after">
                    {{__('order.orders.showItems')}}
                </span>
            </button>
            @if ($order->status !== 'completed' && Gate::allows('delivery'))
            <button type="button" id="tocompl{{$order->enc_id}}" class="btn btn-success"
                v-on:click.prevent="h.d.completeOrder('{{$order->enc_id}}')">
                <x-btn-loader id="spinord{{$order->enc_id}}"></x-btn-loader>
                <span class="after">
                    {{__('order.orders.complete')}}
                </span>
            </button>
            @endif
        </div>
    </div>
</div>
@include('user.orders-list.items-modal')
@empty
<div class="col-12">
    <x-errors :msg="__('order.orders.empty')"></x-errors>
</div>
@endforelse
</div>