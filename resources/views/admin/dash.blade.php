@extends('layouts.admin')

@section('title')
{{__('admin.dash.title')}}
@endsection

@section('data')
<div class="row">
    @foreach ($count as $trans => $val)
    <div class="col-sm-6 col-md-3 mb-3">
        <div class="card p-0">
            <div class="card-header bg-primary text-light">
                <strong>
                    {{__("order.dash.$trans")}}
                </strong>
            </div>
            <div class="card-body text-center">
                <strong class="fa-2x" data-toggle="tooltip" data-placement="top"
                    title="{{$trans === 'totalPaid' ? \number_format($val->c ?? 0, 2) : \number_format($val->c ?? 0, 0)}}">
                    @if ($trans === 'totalPaid')
                    LE
                    @endif
                    {{\App\Http\Controllers\Helper::shortNum($val->c ?? 0)}}
                </strong>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection