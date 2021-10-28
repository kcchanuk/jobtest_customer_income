<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(ReportRequest $request)
    {
        // For all queries, it must be soft-deleted so "deleted_at" column is null

        // Incomes subquery
        $incomes = DB::table('incomes')
            ->select(DB::raw('customer_id, incomes.id as income_id, description, amount, income_date,
            concat(tax_years.start_year, "/", tax_years.start_year + 1) as tax_year'))
            ->leftJoin('tax_years', 'incomes.tax_year_id', '=', 'tax_years.id')
            ->whereNull('incomes.deleted_at');

        // For each row, it contains fields from the customers table, incomes subquery and
        // sum of all incomes of each customer.
        if ($request->filled('income_date')) {
            // Filter by income date if it is in the request
            $incomes = $incomes->whereDate('income_date', $request->income_date);

            // Assume customers with no income are not needed
            $customers = DB::table('customers')
                ->select(DB::raw('
            customers.id as customer_id, name, email, utr, dob, phone,
            income_id, description, amount, income_date, tax_year,
            (select sum(amount) from incomes
            where customers.id = incomes.customer_id and
                incomes.deleted_at is null and
                incomes.income_date = "' . $request->income_date . '") as customer_total_amount
            '))
                ->joinSub($incomes, 'customer_incomes', function ($join) {
                    $join->on('customers.id', '=', 'customer_incomes.customer_id');
                })
                ->whereNull('customers.deleted_at')
                ->get();
        } else {
            // Assume customers with no income are also needed (i.e. left join with subquery)
            $customers = DB::table('customers')
                ->select(DB::raw('
            customers.id as customer_id, name, email, utr, dob, phone,
            income_id, description, amount, income_date, tax_year,
            (select sum(amount) from incomes where customers.id = incomes.customer_id and incomes.deleted_at is null) as customer_total_amount
            '))
                ->leftJoinSub($incomes, 'customer_incomes', function ($join) {
                    $join->on('customers.id', '=', 'customer_incomes.customer_id');
                })
                ->whereNull('customers.deleted_at')
                ->get();
        }

        return response()->json($customers, 200);
    }
}
