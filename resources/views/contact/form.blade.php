@auth
@js(userMail, auth()->user()->email)
@js(userName, auth()->user()->name)
@endauth
<form class="form needs-validation"
    :class="{'was-validated': h.d.form.sending || h.d.form.hasErrors}"
    method="POST" novalidate v-on:submit.prevent.stop="h.d.sendMail()">
    @csrf
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="name-addon">
                <i class="fas fa-user mx-1"></i>
                {{__('contact.userName')}}
            </span>
        </div>
        <input type="text" class="form-control"
            placeholder="{{__('contact.userName')}}" aria-label="Username"
            name="userName" v-model="h.d.form.userName"
            aria-describedby="name-addon">
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="email-addon">
                <i class="fas fa-envelope mx-1"></i>
                {{__('auth.E-Mail-Address')}}
            </span>
        </div>
        <input type="email" class="form-control"
            placeholder="{{__('auth.E-Mail-Address')}}" aria-label="userMail"
            name="userMail" v-model="h.d.form.userMail"
            v-on:keypress="h.d.validateOnKeyPress()"
            aria-describedby="email-addon" required>
        <div class="invalid-feedback">
            <span v-if="h.d.errors.userMail">
                @{{h.d.errors.userMail[0]}}
            </span>
            <span v-else>
                {{__('validation.email', ['attribute' => __('contact.userMail')])}}
            </span>
        </div>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="message-addon">
                <i class="fas fa-comment-dots mx-1"></i>
                {{__('contact.message')}}
            </span>
        </div>
        <textarea class="form-control" placeholder="{{__('contact.message')}}"
            aria-label="userMessage" name="userMessage"
            v-model="h.d.form.userMessage"
            v-on:keypress="h.d.validateOnKeyPress()"
            aria-describedby="message-addon" minlength="5" required></textarea>
        <div class="invalid-feedback">
            <span v-if="h.d.errors.userMessage">
                @{{h.d.errors.userMessage[0]}}
            </span>
            <span v-else>
                {{__('validation.gt.string', [
                    'attribute' => __('contact.userMessage'), 
                    'value' => 5
                ])}}
            </span>
        </div>
    </div>
    <div class="form-group ">
        <button type="submit" class="btn btn-success">
            <x-btn-loader showIf="h.d.form.sending"></x-btn-loader>
            <span class="after">
                {{__('contact.send')}}
            </span>
        </button>
        <button type="reset" class="btn btn-outline-danger ml-3">
            <span class="after">
                {{__('contact.reset')}}
            </span>
        </button>
    </div>
</form>