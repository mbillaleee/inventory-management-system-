<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function Dashboard(){
        $total_sales = DB::table('invoices')->count();
        $total_purchases = DB::table('purchases')->count();
        $suppliers = DB::table('suppliers')->count();
        $customers = DB::table('customers')->count();
        return view('admin.index', compact('total_sales', 'total_purchases', 'suppliers', 'customers'));
    }
}
