<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = StudentRegistration::count();

        $registrationStatuses = StudentRegistration::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $paymentStatuses = Payment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $totalPayments = Payment::where('status', 'diterima')->count();

        $totalAmount = Payment::where('status', 'diterima')->sum('amount');

        $paymentsPerMonth = Payment::select(
            DB::raw("MONTH(created_at) as month"),
            DB::raw("SUM(amount) as total")
        )
            ->where('status', 'diterima')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0);
        foreach ($paymentsPerMonth as $data) {
            $monthlyData[$data->month] = (float) $data->total;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'registrationStatuses',
            'paymentStatuses',
            'totalPayments',
            'totalAmount',
            'monthlyData'
        ));
    }
}
