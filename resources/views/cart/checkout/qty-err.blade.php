<x-errors :msg="__('cart.updatedAmount')" cls="px-4 py-3" type="warning" icon="exclamation"></x-errors>
<div class="col-12 card p-0 mb-3">
    <h5 class="card-header bg-primary text-light">
        {{__('cart.updatedAmountProductsTitle')}}
    </h5>
    @foreach (session()->get('productAmount') as $err)
    <div class="media border-bottom py-2 overflow-hidden card-body">
        <img src="{{$err['item']['product']['images'][0]}}" style="width: 6rem;"
            class="cartImage align-self-center mr-3 img-thumbnail bg-light tranistion"
            alt="{{$err['item']['product']['title']}}">
        <div class="media-body pr-1">
            <h5 class="text-left">
                <x-a :href="'/product/' . $err['item']['product']['slug']">
                    {{$err['item']['product']['title']}}
                </x-a>
            </h5>
            <p>
                <strong>
                    <del>{{$err['from']}}</del>
                    <i
                        class="fas fa-arrow-{{app()->getLocale() === 'ar' ? 'left' : 'right'}} mx-2"></i>
                    {{$err['to']}}
                </strong>
            </p>
        </div>
    </div>
    @endforeach
</div>