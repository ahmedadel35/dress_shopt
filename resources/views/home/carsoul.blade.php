<div id="homeImagesCarousel" class="col-12 px-0 mx-0 carousel slide"
    data-ride="carousel" data-interval="5000" data-touch="true"
    data-pause="hover">
    <ol class="carousel-indicators">
        @foreach ($carImgs as $cimg)
        <li data-target="#homeImagesCarousel" data-slide-to="{{$loop->index}}"
            @if($loop->first)class="active" @endif></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach ($carImgs as $cimg)
        <div class="carousel-item @if($loop->first) active @endif bg-dark" v-lazy-container="{ selector: 'img', loading: 'rings-dark.svg' }">
            <img class="img-fluid" data-src="{{$cimg->img}}"
                alt="{{$cimg->title}}">
            <div class="carousel-caption">
                <h3>{{$cimg->title}}</h3>
                <x-link-loader outside="a" href="{{$cimg->url}}"
                    class="btn btn-outline-light transition">
                    {{__('home.shopNow')}}
                </x-link-loader>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#homeImagesCarousel" role="button"
        data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Prev</span>
    </a>
    <a class="carousel-control-next" href="#homeImagesCarousel" role="button"
        data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>