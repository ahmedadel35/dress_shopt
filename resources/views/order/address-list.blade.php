<div class="row mt-3">
    {{-- user is signed in --}}
    @guest
        <div class="col-12" v-if="!h.d.addresses.length">
            <x-errors :msg="__('cart.emptyAddressList')" cls="px-4 py-2" type="warning" icon="exclamation">
                <span class="d-block">
                    {{ __('cart.createAcountToAddress') }}
                </span>
            </x-errors>
        </div>
    @else
        <div class="col-12" v-if="h.d.emptyAddressList">
            <x-errors :msg="__('cart.emptyAddressList')" cls="px-4 py-2" type="warning" icon="exclamation"></x-errors>
        </div>
    @endguest
    <div class="col-12" v-else>
        <div class="card p-0">
            @auth
                <h6 class="card-header bg-primary text-light">
                    {{ __('order.addressList') }}
                </h6>

                <div class="card-body">
                    <div class="row">
                        <div class="address-item pointer transition col-11 col-sm-5 border py-2 m-1"
                            v-for="adr in h.d.addresses" :key="adr.id * Math.random()"
                            :class="{'active bg-primary text-light': h.d.address.id === adr.id}"
                            v-on:click.prevent="h.d.selectAddress(adr)">
                            <h5>
                                @{{ adr.firstName }} @{{ adr.lastName }}
                            </h5>
                            <strong class="d-block">(@{{ adr.dep }})
                                @{{ adr.address }},</strong> @{{ adr.city }}<br>
                            <span>@{{ adr.gov }}, @{{ adr.country }}</span>
                            <div class="d-block">
                                <i class="fas fa-phone mr-1"></i>
                                <span>@{{ adr.phoneNumber }}</span>
                            </div>
                        </div>
                        <div class="col-11 col-sm-5 border py-2 m-1" v-for="adrlod in h.d.addressesLoader"
                            :key="adrlod * Math.random()">
                            <content-loader width="120" height="60" primary-color="#cbcbcd" secondary-color="#02103b">
                                <rect x="0" y="8" rx="0" ry="0" width="100" height="12"></rect>
                                <rect x="0" y="26" rx="0" ry="0" width="75" height="6"></rect>
                                <rect x="-1" y="36" rx="0" ry="0" width="75" height="6"></rect>
                                <rect x="-1" y="49" rx="0" ry="0" width="90" height="8"></rect>
                            </content-loader>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">

                        <div class="col-sm-6" v-if="h.d.address.id">
                            <button class="btn btn-outline-danger" v-on:click="h.d.deleteAddress()"
                                :disabled="h.d.deletingAddress">
                                <x-btn-loader showIf="h.d.deletingAddress">
                                </x-btn-loader>
                                <i class="fas fa-trash-alt mx-1"></i>
                                <span class="after">
                                    {{ __('order.deleteAddress') }}
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            @endauth
        </div>
    </div>
    <div class="col-12">
        <div class="col-sm-6 mb-2 mb-sm-0">
            <button type="button" class="btn btn-success mb-2" v-on:click="h.d.showAddressForm()">
                <x-btn-loader showIf="h.d.addingAddress">
                </x-btn-loader>
                <i class="fas fa-plus mx-1"></i>
                <span class="after">
                    {{ __('cart.addNewAddress') }}
                </span>
            </button>
        </div>
    </div>
    <div class="card col-12 p-0" v-show="h.d.showForm">
        @include('order.form.index')
    </div>
</div>
