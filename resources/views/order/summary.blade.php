<div class="row mt-3">
    <div class="col-12"
        v-if="!h.d.hasErrors && h.d.address.address && h.d.paymentMethod">
        <div class="alert alert-info text-center">
            <strong>
                {{-- Please check your order --}}
                {{__('order.summary.note')}}
            </strong>
        </div>
        <div class="row col-12">
            <div class="card p-0 col-md-6 mb-2">
                <div class="card-header bg-primary text-light">
                    <h6>
                        {{__('order.bilingAddress')}}
                    </h6>
                </div>
                <div class="card-body">
                    <address>
                        <h5>
                            @{{h.d.address.firstName}} @{{h.d.address.lastName}}
                        </h5>
                        <strong class="d-block">(@{{h.d.address.dep}})
                            @{{h.d.address.address}}</strong>
                        <span>@{{h.d.address.city}},
                            {{-- @{{h.d.address.country}} --}}
                            {{__('countries.eg')}}
                        </span>
                        <div class="d-block">
                            <i class="fas fa-phone mr-1"></i>
                            <span>@{{h.d.address.phoneNumber}}</span>
                        </div>
                    </address>
                </div>
            </div>
            <div class="card p-0 col-md-6 mb-2">
                <div class="card-header bg-primary text-light">
                    <h6>
                        {{__('order.paymentMethod')}}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="ondelivery card card-body border-primary"
                        v-if="h.d.paymentMethod === 'onDelivery'">
                        {{__('order.cashOnDelivery')}}
                    </div>
                    <div class="accept card card-body border-primary"
                        v-else>
                        {{-- {{__('order.cashOnAccept')}} --}}
                        <img src="/storage/site/accept-card.png" width="200" height="40" />
                        <p class="text-center pt-2">
                            {{__('order.youWillBeRedirect')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 text-center">
            {{-- TODO save user order --}}
            <div class="col-12">
                <button class="btn btn-success btn-block" v-on:click="h.d.storeOrder()" :disabled="h.d.savingOrder">
                    <x-btn-loader showIf="h.d.savingOrder"></x-btn-loader>
                    <i class="fas fa-save mx-1" v-show="!h.d.savingOrder"></i>
                    <span class="after">
                        {{__('order.save')}}
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>