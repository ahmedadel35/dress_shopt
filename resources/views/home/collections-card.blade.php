@foreach ($cats as $c)
<div class="collection-card col-sm-4 col-md-3 px-1 cat-card">
    <div class="card p-0 text-white overflow-hidden" v-lazy-container="{ selector: 'img' }">
        <img data-src="/storage/collection/{{$c->id}}.jpg"
            class="card-img transition" alt="{{$c->title}}">
        <div class="card-img-overlay text-center transition">
            <div class="mt-2">
                <h4 class="card-title">{{$c->title}}</h4>
                <div class="card-text">
                    <x-link-loader :href="'products/' . $c->slug"
                        class="btn-outline-primary animate__animated animate__backInUp text-light">
                        {{__('home.shopNow')}}
                    </x-link-loader>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach