<div id="prod{{$p->id}}" class="col-sm-6 col-md-3 mb-2">
    <div class="card p-0">
        <img src="{{$p->images[0]}}" alt="{{$p->title}}" class="card-img-top"
            style="max-height: 250px" />
        @if ($p->save)
        <span class="save badge badge-danger position-absolute">
            {{__('t.offTxt')}} {{$p->save}} %
        </span>
        @endif
        <div class="card-body">
            <h5>
                <a href="/{{app()->getLocale()}}/product/{{$p->slug}}">
                    {{$p->title}}
                </a>
            </h5>
            <strong class="text-primary" data-toggle="tooltip"
                data-placement="top"
                title="LE {{\number_format($p->saved_price, 2)}}">
                LE {{\App\Http\Controllers\Helper::shortNum($p->saved_price)}}
            </strong>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-{{$p->featured  ? 'success' : 'warning'}} m-1" v-on:click.prevent="h.d.starProd({{$p->id}}, '{{$p->slug}}')">
                <x-btn-loader id="spinnerStar{{$p->id}}"></x-btn-loader>
                <span class="after">
                    <i class="fas fa-star mx-1"></i>
                </span>
            </button>
            <button type="button" class="btn btn-info m-1" v-on:click.prevent="h.d.edit({{$p->id}}, '{{$p->slug}}')">
                <x-btn-loader id="spinnerEdit{{$p->id}}"></x-btn-loader>
                <span class="after">
                    <i class="fas fa-edit mx-1"></i>
                </span>
            </button>
            <button type="button" class="btn btn-primary m-1"
                v-on:click="h.d.openModal('{{$p->slug}}')">
                <span class="after">
                    <i class="fas fa-search-plus mx-1"></i>
                </span>
            </button>
            <button type="button" class="btn btn-danger m-1"
                v-on:click.prevent="h.d.delete('{{$p->slug}}', {{$p->id}})">
                <x-btn-loader :id="'spinnerDel' . $p->id"></x-btn-loader>
                <span class="after">
                    <i class="fas fa-trash-alt mx-1"></i>
                </span>
            </button>
        </div>
    </div>
</div>