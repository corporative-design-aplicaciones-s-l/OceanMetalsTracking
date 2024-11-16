<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vacation;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminVacationController extends Controller
{
    public function index()
    {
        $pendingVacations = Vacation::where('validated', false)
            ->where('refused', false)
            ->with('user')
            ->get();

        $futureVacations = Vacation::where('validated', true)
            ->where('start_date', '>', Carbon::now())
            ->with('user')
            ->orderBy('start_date')
            ->get();

        $employeesOnVacation = Vacation::where('validated', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->with('user')
            ->get();

        return view('admin.workers.adminvacations', compact('pendingVacations', 'futureVacations', 'employeesOnVacation'));
    }

    public function validateVacations($id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->update(['validated' => true]);

        return response()->json(['status' => 'success']);
    }

    public function declineVacations($id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->update(['refused' => true]);

        return response()->json(['status' => 'success']);
    }
}