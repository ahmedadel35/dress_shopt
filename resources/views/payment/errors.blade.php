<x-errors :msg='__("payment.$obj->data_message")'></x-errors>
{{-- Unspecified Failure || Declined || Transaction was blocked by the Payment Server because it did not pass all risk checks. --}}
@switch($err)
    @case(1)
        <x-errors :msg="__('payment.errorProcessing')"></x-errors>
        @break
    @case(2)
        <x-errors :msg="__('payment.cardBank')"></x-errors>
        @break
    @case(4)
        <x-errors :msg="__('payment.expired')"></x-errors>
        @break
    @case(5)
        <x-errors :msg="__('payment.insufficient')"></x-errors>
        @break
    @case(6)
        <x-errors :msg="__('payment.aleradyproccessed')"></x-errors>
        @break
    @default
    <x-errors :msg="__('payment.anErrorOccured')"></x-errors>
@endswitch