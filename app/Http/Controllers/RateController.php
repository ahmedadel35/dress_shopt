<?php

namespace App\Http\Controllers;

use App\Product;
use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RateController extends Controller
{
    private static $vRules = [
        'rate' => 'bail|required|numeric|min:0|max:5',
        'message' => 'sometimes|bail|string|min:5|max:255'
    ];
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        return response()->json(
            Rate::with('owner')
                ->where('product_id', $id)
                ->latest()
                ->paginate()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $r = request()->validate(self::$vRules + [
            'product_id' => 'bail|numeric|exists:products,id',
        ]);

        $r = Rate::create($r + [
            'user_id' => auth()->id()
        ]);

        $r->loadMissing('owner');

        return response()->json($r);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        $res = (object)request()->validate(self::$vRules);

        Gate::authorize('update-model', $rate);

        $rate->rate = $res->rate;
        $rate->message = $res->message ?? '';

        $updated = $rate->save() ? $rate->updated : false;

        return response()->json(compact('updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        Gate::authorize('update-model', $rate);

        $rate->delete();

        return response()->json([], 204);
    }
}
