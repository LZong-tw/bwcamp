<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Camp;

class ApiController extends Controller
{
    //
    public function sendCampData(Request $request)
    {
        // Key: AAAAC3NzaC1lZDI1NTE5AAAAIK0wmN/Cr3JXqmLW7u+g9pTh+wyqDHpSQEIQczXkVx9q
        if($request->key != "AAAAC3NzaC1lZDI1NTE5AAAAIK0wmN/Cr3JXqmLW7u+g9pTh+wyqDHpSQEIQczXkVx9q") {
            return response()->json(['error' => '401'], 401);
        }
        $camp = $request->camp;
        $year = $request->year;

        $data = Camp::with('batchs', 'batchs.applicants', 'batchs.applicants.checkInData', 'batchs.applicants.signData')
                        ->where('table', $camp)
                        ->where(function($query) use ($year) {
                            $query->where('fullName', 'like', '%' . $year . '%')
                                ->orWhere('registration_start', 'like', '%' . $year . '%');
                        })->first();

        return response()->json([
            'camp' => $camp,
            'year' => $year,
            'data' => $data
        ]);
    }
}
