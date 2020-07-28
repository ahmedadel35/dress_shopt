<x-modal id="items-modal">
    <x-slot name='header'>
        # @{{h.d.orderId}}
        {{__('order.orders.showItems')}}
    </x-slot name='header'>
    <ul class="list-unstyled">
        <x-cart-list :nocart="true" :has-remove="false">
        </x-cart-list>
    </ul>
    <x-slot name='footer'>
        <button type="button" class="btn btn-danger" data-dismiss="modal">
            <span class="after">
                {{__('order.closeBtn')}}
            </span>
        </button>
    </x-slot name='footer'>
</x-modal>