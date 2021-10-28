<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CustomerResource::collection(Customer::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CustomerRequest $request)
    {
        // create customer first
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'utr' => $request->utr,
            'dob' => $request->dob,
            'phone' => $request->phone
        ]);

        // handle profile pic file if it is in the request
        if ($request->hasFile('profile_pic')) {
            // store the profile pic
            $customer->storeProfilePic($request->file('profile_pic'));
        }

        return response()->json(new CustomerResource($customer), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return CustomerResource
     */
    public function show(Customer $customer)
    {
        // Assume no related incomes are needed
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerRequest $request
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        // Update customer first
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'utr' => $request->utr,
            'dob' => $request->dob,
            'phone' => $request->phone
        ]);

        // Assume if there is no new profile pic uploaded,
        // then there is no need to delete the original profile pic

        // handle profile pic file if it is in the request
        if ($request->hasFile('profile_pic')) {
            // store the profile pic
            $customer->storeProfilePic($request->file('profile_pic'));
        }

        return response()->json(new CustomerResource($customer), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Customer $customer)
    {
        $customer->incomes()->delete();
        $customer->delete();

        // Assume there is no need to delete profile pics and income files

        return response()->json(null, 204);
    }
}
