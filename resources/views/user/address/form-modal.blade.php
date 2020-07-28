<form method="POST" v-on:submit.prevent="h.d.saveAddress()"
    class="needs-validation" :class="{'was-validated': h.d.hasErrors}"
    novalidate>
    <x-modal id="address-form-modal">
        <x-slot name='header'>
            {{__('order.address.createTitle')}}
        </x-slot>

        @include ('order.form.address')
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

        <x-slot name='footer'>
            <button type="reset" class="btn btn-danger" data-dismiss="modal"
                aria-label="Close">
                <span class="after">
                    {{__('order.address.cancel')}}
                </span>
            </button>
            <button type="submit" class="btn btn-success">
                <x-btn-loader show-if="h.d.creating"></x-btn-loader>
                <i class="fas fa-plus mx-1"></i>
                <span class="after">
                    {{__('order.address.save')}}
                </span>
            </button>
        </x-slot>
    </x-modal>
</form>