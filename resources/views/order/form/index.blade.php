<form class="form needs-validation" :class="{'was-validated': h.d.hasErrors}"
    v-on:submit.prevent.stop="h.d.addAddress()" novalidate>
    @csrf
    @guest
    <h6 class="card-header bg-primary text-light">
        {{__('order.userInfo')}}
    </h6>
    <div class="card-body">
        <div class="form-group">
            <input type="email" name="email" v-model="h.d.form.userMail"
                placeholder="{{__('order.userMail')}}" class="form-control"
                v-on:keypress="h.d.validateOnChange()" required />
            <div class="invalid-feedback">
                <span v-if="h.d.error.userMail">
                    @{{h.d.error.userMail[0]}}
                </span>
                <span v-else>
                    {{__('validation.email', ['attribute' => __('order.userMail')])}}
                </span>
            </div>
        </div>

    </div>
    @endguest
    <h6 class="card-header bg-primary text-light">
        {{__('order.addressInfo')}}
    </h6>
    <div class="card-body">
        @include('order.form.address')
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-success">
                    <x-btn-loader showIf="h.d.continue"></x-btn-loader>
                    <span class="after">
                        {{__('cart.saveAddress')}}
                    </span>
                </button>
            </div>
        </div>
    </div>
</form>