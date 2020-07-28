@props(['address'])

<address>
    <h5 id="name">{{$address->firstName}}
        {{$address->firstName}}</h5>
    @if (is_int($address->dep))
    <span id="deb">{{$address->dep}}</span>
    @endif
    <span id="addr">{{$address->address}}</span>,
    <span id="city">{{$address->city}}</span><br>
    <strong>
        <span id="gov">{{$address->gov}}</span> &nbsp;
        {{$address->country}}
    </strong><br>
</address>
<a class="mt-2" href="tel:{{$address->phoneNumber}}">
    <i class="fas fa-phone-alt mx-1"></i>
    {{$address->phoneNumber}}
</a>