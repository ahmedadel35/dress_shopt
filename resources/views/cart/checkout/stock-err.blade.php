<x-errors :msg="__('cart.outRemoved')" cls="px-4 py-3" type="warning" icon="exclamation"></x-errors>
<div class="col-12 card p-0 mb-3">
    <h5 class="card-header bg-primary text-light">
        {{__('cart.outRemovedProductsTitle')}}
    </h5>
    @foreach (session()->get('productOut') as $err)
    <div class="media border-bottom py-2 overflow-hidden card-body">
        <img src="{{$err['product']['images'][0]}}" style="width: 6rem;"
            class="cartImage align-self-center mr-3 img-thumbnail bg-light tranistion"
            alt="{{$err['product']['title']}}">
        <div class="media-body pr-1">
            <h5>
                <x-a :href="'/product/' . $err['product']['slug']">
                    {{$err['product']['title']}}
                </x-a>
            </h5>
        </div>
    </div>
    @endforeach
</div>