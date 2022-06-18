<footer class="footer pt-5 mt-5 bg-dark">
    <div class="container text-light">
        <div class="row">
            <div class="col-sm-6 mb-5">
                <h4 class="text-light">{{__('t.footer.followUs')}}</h4>
                @include('footer.social')
                <ul class="list-group list-group-flush pt-4">
                    <h6 class="text-light">
                        {{-- Any questions? Let us know in store --}}
                        {{__('footer.anyQues')}}
                    </h6>
                    @include('footer.contact')
                </ul>
            </div>
            <div class="col-sm-6">
                <h4>{{__('t.footer.myAccount')}}</h4>
                @include('footer.user-links')
            </div>
        </div>
        <div class="row border-top mt-3 border-secondary">
            <div class="col-sm-6 pt-2 mt-2 text-center copy">
                <h5 class="pb-0 mb-0">
                    Copyright <span class="text-danger">Dress</span>&copy; 2020
                    {{date('Y') > 2020 ? '- ' . date('Y') : ''}}. All rights
                    reserved
                </h5>
            </div>
            <div class="col-sm-6 pt-2 mt-2 text-center copy text-light">
                <div class="row">
                    <div class="col-6 col-sm-12 col-md-6 mb-2 rounded">
                        <div class="p-2 bg-light text-dark mx-2">
                            <strong>
                                {{__('order.cashOnDelivery')}}
                            </strong>
                        </div>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6 mb-2 text-left">
                        <i
                            class="fab fa-cc-visa fa-3x text-primary mx-1 bg-light px-1 rounded"></i>
                        <i
                            class="fab fa-cc-mastercard fa-3x text-danger mx-1 bg-light px-1 rounded"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mt-3 border-secondary text-right">
            <div class="col-12 pt-2 text-center copy">
                <p class="pb-0 mb-2">
                    Website Built With <span class="text-danger">&hearts;</span>
                    by <a class="text-danger" target="_blank"
                        href="https://abo3adel.github.io/">Ahmed Adel</a> &copy;
                    2020 {{date('Y') > 2020 ? '- ' . date('Y') : ''}}
                </p>
            </div>
        </div>
    </div>
</footer>