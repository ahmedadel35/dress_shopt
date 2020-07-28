@js(user_id, auth()->id() ?? 0)

<div class="col-12">
    <h3 id='revs'>{{__('t.show.rev')}}</h3>
    <div>
        <div class="d-block text-center">
            <span
                class="text-primary border border-dark rounded-circle px-3 py-2">
                <strong>@{{h.d.product.rate_avg}}</strong>
            </span>
            <div class="d-block pt-3">
                <star-rate :percent="h.d.product.rate_avg"></star-rate>
            </div>
            <p class="text-muted mt-1">
                @{{h.d.revData.length || 0}} {{__('t.show.ratings')}}
            </p>
        </div>
        <div class="rateform">
            <h3>{{__('t.show.rateThis') }}:</h3>
            <form class="needs-validation"
                :class="h.d.userRev.message.length > 0 && h.d.userRev.message.length < 5 ? 'was-validated' : ''">
                <div class="form-group">
                    <star-rate :percent="h.d.userRev.rate"
                        :run="@auth true @else false @endauth"
                        v-on:rated="h.d.userRev.rate = $event"></star-rate>
                </div>
                @auth
                <div class="form-group mt-2">
                    <label
                        class="form-label">{{__('t.show.rateMessage') }}</label>
                    <textarea type="text" class="form-control"
                        v-model="h.d.userRev.message"
                        :required="h.d.userRev.message.length > 0 && h.d.userRev.message.length < 5"
                        minlength="5"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-success"
                        v-on:click.prevent.stop="h.d.addRev()"
                        :disabled="h.d.userRev.message.length > 0 && h.d.userRev.message.length < 5 || h.d.savingRev || !h.d.userRev.rate">
                        <x-btn-loader showIf="h.d.savingRev"></x-btn-loader>
                        <span class="after">
                            <span v-if="h.d.userRev.alreadyReved">
                                {{__('t.show.upRateBtn') }}
                            </span>
                            <span v-else>
                                {{__('t.show.rateBtn') }}
                            </span>
                        </span>
                    </button>
                </div>
                @else
                {{__('t.show.signMess') }}
                <a class="btn btn-outline-primary" href="{{ route('login') }}">
                    <span class="after">
                        {{ __('Login') }}
                    </span>
                </a>
                @endauth
            </form>
        </div>
        <hr />
        <div class="revS mt-4">
            <div class="alert alert-warning text-center" v-if="h.d.emptyRates">
                <strong>
                    {{__('product.noRates_available')}}
                </strong>
            </div>
            <div v-for="(r, rteinx) in h.d.revData"
                :key="Math.random() + r.owner.name" class="media">
                <x-profile-img src="'/' + r.owner.image" width="90" height="90">
                </x-profile-img>
                <div class="media-body col-12">
                    <div class="m-0 row">
                        <div class="col-8 pl-0">
                            <star-rate :percent="r.rate"></star-rate>
                        </div>
                        <div class="col-4 pr-0"
                            v-if="h.d.userCanUpdate || r.user_id === h.d.userRev.userId">
                            <button type="button"
                                class="btn btn-danger close px-1 bg-danger text-light"
                                v-on:click="h.d.deleteRate(r.id, rteinx)">
                                <span :ref="'deleteRefBtn' + r.id"
                                    class="d-none mr-2">
                                    <x-btn-loader>
                                    </x-btn-loader>
                                </span>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <p class="m-0">
                        @lang('t.show.by') <strong>@{{r.owner.name}}</strong>
                        <span class="mx-2 badge badge-primary p-2">
                            <strong>@{{r.updated}}</strong>
                        </span>
                    </p>
                    <p class="text-muted">
                        @{{r.message}}
                    </p>
                    <hr class="mb-3" v-if="rteinx !== h.d.revData.length-1" />
                </div>
            </div>
            <div v-for="rlod in h.d.revDataLoader" :key="Math.random() * rlod"
                class="col-12 col-md-10 px-0 mb-2 border-top">
                <content-loader width="350" height="90" primary-color="#cbcbcd"
                    secondary-color="#02103b">
                    <rect x="4" y="1" rx="4" ry="4" width="50" height="37">
                    </rect>
                    <rect x="63" y="9" rx="0" ry="0" width="65" height="6">
                    </rect>
                    <rect x="63" y="22" rx="0" ry="0" width="65" height="6">
                    </rect>
                    <rect x="139" y="22" rx="0" ry="0" width="40" height="6">
                    </rect>
                    <rect x="63" y="37" rx="0" ry="0" width="270" height="5">
                    </rect>
                    <rect x="63" y="49" rx="0" ry="0" width="270" height="5">
                    </rect>
                    <rect x="63" y="61" rx="0" ry="0" width="270" height="5">
                    </rect>
                    <rect x="63" y="72" rx="0" ry="0" width="270" height="5">
                    </rect>
                </content-loader>
            </div>
            <div class="mt-2" v-show="h.d.revData.length">
                <button type='button' class="btn btn-primary btn-block btn-lg"
                    v-on:click="h.d.loadMoreRevs()"
                    :disabled="h.d.nextRevUrl === ''">
                    <x-btn-loader showIf="h.d.loadingRates"></x-btn-loader>
                    @lang('t.show.loadMore')
                </button>
            </div>
        </div>
    </div>
</div>