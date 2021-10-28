<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Customer;
use App\Models\Income;
use App\Models\TaxYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class IncomeController extends Controller
{
    /**
     * Display a listing of the incomes.
     *
     * @param Customer $customer
     * @return AnonymousResourceCollection
     */
    public function index(Customer $customer): AnonymousResourceCollection
    {
        return IncomeResource::collection($customer->incomes);
    }

    /**
     * Store a newly created income in storage.
     *
     * @param Customer $customer
     * @param IncomeRequest $request
     * @return JsonResponse
     */
    public function store(Customer $customer, IncomeRequest $request): JsonResponse
    {
        $income = new Income;
        $income->description = $request->description;
        $income->amount = $request->amount;
        $income->income_date = $request->income_date;
        $income->tax_year()->associate(TaxYear::find($request->tax_year_id));
        $income->customer()->associate($customer);
        $income->save();

        // handle income file if it is in the request
        if ($request->hasFile('income_file')) {
            // store the income file
            $income->storeIncomeFile($request->file('income_file'));
        }

        return response()->json(new IncomeResource($income), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Income $income
     * @return IncomeResource
     */
    public function show(Income $income): IncomeResource
    {
        // Assume no related customer is needed
        return new IncomeResource($income);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param IncomeRequest $request
     * @param Income $income
     * @return JsonResponse
     */
    public function update(IncomeRequest $request, Income $income): JsonResponse
    {
        // Update income first
        $income->description = $request->description;
        $income->amount = $request->amount;
        $income->income_date = $request->income_date;
        $income->tax_year()->associate(TaxYear::find($request->tax_year_id));
        $income->save();

        // handle income file if it is in the request
        if ($request->hasFile('income_file')) {
            // store the income file
            $income->storeIncomeFile($request->file('income_file'));
        }

        return response()->json(new IncomeResource($income));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Income $income
     * @return Response
     */
    public function destroy(Income $income): Response
    {
        $income->delete();

        // Assume there is no need to delete income files
        return response()->noContent();
    }
}
