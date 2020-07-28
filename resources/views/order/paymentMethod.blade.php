<div class="row mt-3">
    <div class="col-12" v-show="!h.d.hasErrors && h.d.address.address">
        <div class="form-check my-2 text-left">
            <input class="form-check-input" type="radio" name="paymentMethod"
                v-model="h.d.paymentMethod" id="cashondeliveryId"
                value="onDelivery" v-on:click="h.d.paymentMethod = 'onDelivery'"
                checked>
            <label class="form-check-label btn btn-light btn-block pointer transition"
                :class="{'border-success': h.d.paymentMethod === 'onDelivery'}"
                for="cashondeliveryId">
                {{__('order.cashOnDelivery')}}
            </label>
        </div>
        <div class="form-check my-2 ">
            <input class="form-check-input" type="radio" name="paymentMethod"
                v-model="h.d.paymentMethod" id="accept" value="accept"
                v-on:click="h.d.paymentMethod = 'accept'">
            <label class="form-check-label btn btn-light btn-block pointer transition"
                :class="{'border-success': h.d.paymentMethod === 'accept'}"
                for="accept">
                {{-- {{__('order.cashOnAccept')}} --}}
                <div class="row">
                    <div class="col-sm-6 pb-2 text-left">
                        <img src="/storage/site/accept-card.png" width="200" height="40" />
                    </div>
                    <div class="col-sm-6 pb-2 text-right">
                        <i class="fab fa-cc-visa fa-3x text-primary"></i>
                        <i class="fab fa-cc-mastercard fa-3x text-danger"></i>
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>