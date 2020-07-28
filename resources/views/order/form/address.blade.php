<div class="form-group row mb-2 pl-0">
    <div class="col-12 col-sm-6 mb-2">
        <input type="text" name="firstName" v-model="h.d.form.firstName"
            class="form-control ml-1" placeholder="{{__('order.firstName')}}"
            required />
        <div class="invalid-feedback">
            <span v-if="h.d.error.firstName">
                @{{h.d.error.firstName[0]}}
            </span>
            <span v-else>
                {{__('validation.required', ['attribute' => __('order.firstName')])}}
            </span>
        </div>
    </div>
    <div class="col-12 col-sm-6 mb-2 pl-0">
        <input type="text" name="lastName" v-model="h.d.form.lastName"
            class="form-control ml-1" placeholder="{{__('order.lastName')}}"
            required />
        <div class="invalid-feedback">
            <span v-if="h.d.error.lastName">
                @{{h.d.error.lastName[0]}}
            </span>
            <span v-else>
                {{__('validation.required', ['attribute' => __('order.lastName')])}}
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <input type="text" name="address" v-model="h.d.form.address"
        class="form-control mx-1 mb-2" placeholder="{{__('order.address.txt')}}"
        v-on:keypress="h.d.validateOnChange()" required />
    <div class="invalid-feedback">
        <span v-if="h.d.error.address">
            @{{h.d.error.address[0]}}
        </span>
        <span v-else>
            {{__('validation.required', ['attribute' => __('order.address.txt')])}}
        </span>
    </div>
</div>
<div class="form-group">
    <input type="text" name="dep" v-model="h.d.form.dep" class="form-control"
        placeholder="{{__('order.dep')}}" />
</div>
<div class="form-group">
    <input type="text" name="city" v-model="h.d.form.city" class="form-control"
        placeholder="{{__('order.city')}}" required />
    <div class="invalid-feedback">
        <span v-if="h.d.error.city">
            @{{h.d.error.city[0]}}
        </span>
        <span v-else>
            {{__('validation.required', ['attribute' => __('order.city')])}}
        </span>
    </div>
</div>
<div class="form-group row">
    <div class="col-6 col-sm-4 mb-2">
        <select type="text" name="country" v-model="h.d.form.country"
            class="form-control custom-select"
            placeholder="{{__('order.country')}}" required>
            <option value="eg" selected>
                {{__('countries.eg')}}
            </option>
        </select>
        <div class="invalid-feedback">
            <span v-if="h.d.error.country">
                @{{h.d.error.country[0]}}
            </span>
            <span v-else>
                {{__('validation.required', ['attribute' => __('order.country')])}}
            </span>
        </div>
    </div>
    <div class="col-6 col-sm-4 mb-2">
        <select type="text" name="gov" v-model="h.d.form.gov"
            class="form-control custom-select" placeholder="{{__('order.gov')}}"
            required>
            @foreach(Lang::get('gov.eg') as $k => $v)
            <option value="{{$k}}" selected>
                {{$v}}
            </option>
            @endforeach
        </select>
        <div class="invalid-feedback">
            <span v-if="h.d.error.gov">
                @{{h.d.error.gov[0]}}
            </span>
            <span v-else>
                {{__('validation.required', ['attribute' => __('order.gov')])}}
            </span>
        </div>
    </div>
    <div class="col-6 col-sm-4 mb-2">
        <input type="number" name="postCode" v-model="h.d.form.postCode"
            dir="ltr" class="form-control "
            placeholder="{{__('order.postCode')}}" required />
        <div class="invalid-feedback">
            <span v-if="h.d.error.postCode">
                @{{h.d.error.postCode[0]}}
            </span>
            <span v-else>
                {{__('validation.required', ['attribute' => __('order.postCode')])}}
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <input type="text" name="phoneNumber" v-model="h.d.form.phoneNumber"
        dir="ltr" class="form-control" placeholder="{{__('order.phoneNumber')}}" pattern="\d+"
        v-on:keypress="h.d.validateOnChange()" maxlength="11" minlength="11" required />
    <div class="invalid-feedback">
        <span v-if="h.d.error.phoneNumber">
            @{{h.d.error.phoneNumber[0]}}
        </span>
        <span v-else>
            {{__('validation.required', ['attribute' => __('order.phoneNumber')])}}
        </span>
    </div>
</div>
<div class="form-group">
    <textarea name="notes" v-model="h.d.form.notes" class="form-control"
        placeholder="{{__('order.notes')}}"></textarea>
</div>