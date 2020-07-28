<ul class="nav nav-tabs text-center justify-content-center font-weight-bold"
    id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="product-description-tab"
            data-toggle="tab" href="#product-description" role="tab"
            aria-controls="product-description" aria-selected="true">
            {{__('product.description')}}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="prod-more-info-tab" data-toggle="tab"
            href="#prod-more-info" role="tab" aria-controls="prod-more-info"
            aria-selected="false">
            {{-- moreInfo => Additional information --}}
            {{__('product.moreInfo')}}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="rates-tab" data-toggle="tab" href="#rates"
            role="tab" aria-controls="rates" aria-selected="false">
            {{__('product.rates')}}
        </a>
    </li>
</ul>
<div class="tab-content my-3 px-3" id="myTabContent">
    <div class="tab-pane fade show active" id="product-description"
        role="tabpanel" aria-labelledby="product-description-tab">
        <span v-if="h.d.product.id">
            @{{h.d.product.info}}
        </span>
        <span v-else>
            <content-loader :height="50" primary-color="#cbcbcd"
                secondary-color="#02103b">
                <rect x="1" y="5" rx="0" ry="0" width="300" height="6"></rect>
                <rect x="1" y="17" rx="0" ry="0" width="300" height="6"></rect>
                <rect x="1" y="30" rx="0" ry="0" width="300" height="6"></rect>
                <rect x="1" y="42" rx="0" ry="0" width="300" height="6"></rect>
            </content-loader>
        </span>
    </div>
    <div class="tab-pane fade" id="prod-more-info" role="tabpanel"
        aria-labelledby="prod-more-info-tab">
        <div v-if="h.d.product.id">
            <p v-for="[k, v] in Object.entries(h.d.product.pi.more)"
                :key="Math.random()" class="row">
                <span class="col-6">
                    @{{k}}
                </span>
                <strong class="col-6">
                    <template v-if="v===true || v===false">
                        <i class="fas fa-check text-success mx-2" v-if="v"></i>
                        <i class="fas fa-times text-danger mx-2" v-else></i>
                    </template>
                    <template v-else>
                        @{{v}}
                    </template>
                </strong>
            </p>
        </div>
        <div v-else>
            <content-loader :height="160" primary-color="#cbcbcd"
                secondary-color="#02103b">
                <rect x="-1" y="7" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="-1" y="30" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="-1" y="54" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="-1" y="77" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="-1" y="99" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="-1" y="121" rx="0" ry="0" width="85" height="8">
                </rect>
                <rect x="200" y="7" rx="0" ry="0" width="85" height="8"></rect>
                <rect x="200" y="29" rx="0" ry="0" width="85" height="8">
                </rect>
                <rect x="200" y="54" rx="0" ry="0" width="85" height="8">
                </rect>
                <rect x="200" y="75" rx="0" ry="0" width="85" height="8">
                </rect>
                <rect x="200" y="98" rx="0" ry="0" width="85" height="8">
                </rect>
                <rect x="200" y="120" rx="0" ry="0" width="85" height="8">
                </rect>
            </content-loader>
        </div>
    </div>
    <div class="tab-pane fade" id="rates" role="tabpanel"
        aria-labelledby="rates-tab">
        @include('products.show.rates')
    </div>
</div>