<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(AddressRequest $request)
    {
        $res = (object) $request->validated();

        if (request()->get('check', false)) {
            return response()->json($res);
        }

        $address = Address::create([
            'user_id' => auth()->id(),
            'firstName' => $res->firstName,
            'lastName' => $res->lastName,
            'address' => $res->address,
            'city' => $res->city,
            'country' =>  __('countries.' . $res->country),
            'gov' => __('gov.eg.' . $res->gov),
            'dep' => $res->dep,
            'postCode' => $res->postCode,
            'phoneNumber' => $res->phoneNumber,
            'notes' => $res->notes,
            'userMail' => $res->userMail
        ]);

        if (request()->get('withTrans', false)) {
            $address->gov = __('gov.eg.' . $address->gov);
            $address->country = __('countries.eg');
        }

        return response()->json($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(int $userId)
    {
        return response()->json([
            'ads' => Address::whereUserId($userId)->get(),
            'userMail' => auth()->user()->email
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Address $address)
    {
        // abort_if(auth()->id() !== $address->user_id, 403);

        $res = $request->validated();
        unset($res['userMail']);

        $res['country'] =  __('countries.' . $res['country']);
        $res['gov'] = __('gov.eg.' . $res['gov']);

        $updated = $address->update($res);

        return response()->json(['updated' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        if ($address->delete()) {
            return response()->json([], 204);
        }

        return response()->json(['error' => true], 404);
    }
}
