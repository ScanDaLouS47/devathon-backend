<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Models\Booking;
use App\Models\Shift;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function barchart(): JsonResponse
    {
        try {
            $startDate = Carbon::now()->subMonths(3)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            // $bookingsByMonth = Booking::all();
            $bookingsByMonth = Booking::select(
                DB::raw('DATE_FORMAT(reservationDate, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('reservationDate', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $bookingsByMonth->transform(function ($item) {
                $item->month = Carbon::createFromFormat('Y-m', $item->month)->format('F');
                return $item;
            });

            return BaseResponse::response(true, $bookingsByMonth, '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function donutchart(): JsonResponse
    {
        try {
            $bookingsByShift = Shift::select('shifts.name as shift_name')
            ->leftJoin('bookings', 'shifts.id', '=', 'bookings.shift_id')
            ->groupBy('shifts.id', 'shifts.name')
            ->selectRaw('count(bookings.id) as total')
            ->orderBy('shift_name')
            ->get();

            return BaseResponse::response(true, $bookingsByShift, '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }
}
