<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CustomerResource::collection(Customer::all());
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param CustomerRequest $request
     * @return JsonResponse
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        // create customer first
        $customer = Customer::create($request->validated());

        // handle profile pic file if it is in the request
        if ($request->hasFile('profile_pic')) {
            // store the profile pic
            $customer->storeProfilePic($request->file('profile_pic'));
        }

        return response()->json(new CustomerResource($customer), 201);
    }

    /**
     * Display the specified customer.
     *
     * @param Customer $customer
     * @return CustomerResource
     */
    public function show(Customer $customer): CustomerResource
    {
        // Assume no related incomes are needed
        return new CustomerResource($customer);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param CustomerRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        // Update customer first
        $customer->update($request->validated());

        // Assume if there is no new profile pic uploaded,
        // then there is no need to delete the original profile pic

        // handle profile pic file if it is in the request
        if ($request->hasFile('profile_pic')) {
            // store the profile pic
            $customer->storeProfilePic($request->file('profile_pic'));
        }

        return response()->json(new CustomerResource($customer));
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param Customer $customer
     * @return Response
     */
    public function destroy(Customer $customer): Response
    {
        DB::transaction(function () use ($customer) {
            $customer->incomes()->delete();
            $customer->delete();
        });

        // Assume there is no need to delete profile pics and income files
        return response()->noContent();
    }
}
