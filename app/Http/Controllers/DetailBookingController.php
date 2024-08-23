<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Resources\BookingResource;
use App\Http\Resources\DetailBookingResource;
use App\Models\Booking;
use App\Models\DetailBooking;
use App\Models\Shift;
use App\Models\Table;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetailBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $date = $request->query('date');
            $persons = $request->query('persons');
            $month = Carbon::parse($date)->month;
            $year = Carbon::parse($date)->year;

            $detailBookings = DetailBooking::with(['booking', 'table'])->whereHas('booking', function ($query) use ($month, $year) {
                $query->whereMonth('reservationDate', $month)->whereYear('reservationDate', $year);
            })->orderBy(Booking::select('reservationDate')->whereColumn('bookings.id', 'detail_bookings.booking_id'), 'asc')->get();
            $groupedByShift = $detailBookings->groupBy(function ($detailBooking) {
                return $detailBooking->booking->shift->name;
            });

            $returnBookings = [];
            $capacity = Table::where('status', 'active')->sum('size');

            foreach ($groupedByShift as $shiftName => $details) {

                $date = date('Y-m-d', strtotime($details[0]->booking->reservationDate));
                $time = date('H:i:s', strtotime($details[0]->booking->shift->started_at));
                $endTime = date('H:i:s', strtotime($details[0]->booking->shift->finish_at));

                $shiftArray = [
                    'id' => $date,
                    'turno' => $shiftName
                ];

                $start = new DateTime($date);
                $start->setTime(
                    (int) date('H', strtotime($time)),
                    (int) date('i', strtotime($time)),
                    (int) date('s', strtotime($time))
                );
                $end = new DateTime($date);
                $end->setTime(
                    (int) date('H', strtotime($endTime)),
                    (int) date('i', strtotime($endTime)),
                    (int) date('s', strtotime($endTime))
                );
                $shiftArray['start'] = $start->format('Y-m-d H:i:s');
                $shiftArray['end'] = $end->format('Y-m-d H:i:s');

                $unavailable = 0;
                foreach ($details as $item) {
                    $unavailable += $item->booking->persons;
                }
                $diffPersons = $capacity - $unavailable;
                $shiftArray['status'] = ($diffPersons >= $persons) ? 'available' : 'full';
                $returnBookings[] = $shiftArray;

            }

            $startDate = new DateTime("$year-$month-01");
            $daysInMonth = $startDate->format('t');
            $retArray = [];
            $turnos = Shift::all();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = new DateTime("$year-$month-$day");
                foreach ($turnos as $turno) {
                    $found = [];
                    foreach ($returnBookings as $obj) {
                        $formCurrentDate = $currentDate->format('Y-m-d');
                        $formStartDate = new DateTime($obj['start']);
                        $formattedStartDate = $formStartDate->format('Y-m-d');

                        if ($formattedStartDate === $formCurrentDate && $obj['turno'] === $turno->name) {
                            $found[] = $obj;
                        }
                    }

                    if (count($found) > 0) {
                        ($currentDate < now()) && $found[0]['status'] = 'expired';
                        $retArray[] = $found[0];
                    } else {
                        $time = date('H:i:s', strtotime($turno->started_at));
                        $endTime = date('H:i:s', strtotime($turno->finish_at));

                        $start = $currentDate;
                        $start->setTime(
                            (int) date('H', strtotime($time)),
                            (int) date('i', strtotime($time)),
                            (int) date('s', strtotime($time))
                        );
                        $end = $currentDate; // new DateTime($date);
                        $end->setTime(
                            (int) date('H', strtotime($endTime)),
                            (int) date('i', strtotime($endTime)),
                            (int) date('s', strtotime($endTime))
                        );
                        $shiftArray['start'] = $start->format('Y-m-d H:i:s');
                        $shiftArray['end'] = $end->format('Y-m-d H:i:s');
                        $retArray[] = [
                            'id' => $currentDate->format('Y-m-d H:i:s'),
                            'title' => $turno->name,
                            'start' => $start->format('Y-m-d\TH:i:s'),
                            'end' => $end->format('Y-m-d\TH:i:s'),
                            'status' => ($currentDate < now()) ? 'expired' : $shiftArray['status']
                        ];
                    }
                }
            }

            return BaseResponse::response(true, $retArray, '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }
}
