<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\DetailBooking;
use App\Models\Status;
use App\Models\Table;
use App\NotFoundResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return BaseResponse::response(true, BookingResource::collection(Booking::with(['user', 'status'])->get()), '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBookingRequest $request
     * @return JsonResponse
     */
    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $date = $validatedData['reservationDate'];
            $shiftId = $validatedData['shift_id'];

            $freeTables = Table::whereNotIn('id', function ($query) use ($date, $shiftId) {
                $query->select('detail_bookings.table_id')
                    ->from('detail_bookings')
                    ->join('bookings', 'bookings.id', '=', 'detail_bookings.booking_id')
                    ->where('bookings.reservationDate', '=', $date)
                    ->where('bookings.shift_id', '=', $shiftId);
            })
                ->orderBy('size', 'desc')
                ->get()
                ->toArray();

            $freeTablesSum = array_reduce($freeTables, function ($carry, $table) {
                return $carry + $table['size'];
            }, 0);

            $nPersons = $validatedData['persons'];

            if ($freeTablesSum < $nPersons) {
                throw new Exception('Not enough tables for the number of persons');
            }

            $bookingTables = [];

            while ($nPersons > 0) {
                for ($i = 0; $i < count($freeTables); $i++) {
                    if ($nPersons >= $freeTables[$i]['size']) {
                        $nPersons -= $freeTables[$i]['size'];
                        $bookingTables[] = $freeTables[$i]['id'];
                    }
                }
                if ($nPersons > 0) {
                    $bookingTables[] = $freeTables[count($freeTables) - 1]['id'];
                    $nPersons -= $freeTables[count($freeTables) - 1]['size'];
                }
            }

            $booking = Booking::create([
                'reservationDate' => $date,
                'shift_id' => $shiftId,
                'user_id' => Auth::user()->id,
                'additional_info' => $validatedData['additional_info'] ?? null,
                'allergens' => $validatedData['allergens'] ?? false,
                'persons' => $validatedData['persons'],
                'statusId' => 1
            ]);

            $booking->detailBookings()->createMany(array_map(function ($table) use ($booking) {
                return ['table_id' => $table, 'booking_id' => $booking->id];
            }, $bookingTables));


            return BaseResponse::response(true, new BookingResource($booking), 'Booking created', 201);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $formattedDate = Carbon::today()->toDateString();
            // $booking = Booking::where('reservationDate', '>=', $formattedDate)->with(['user', 'status'])->get();
            // // $booking = Booking::where('reservationDate', '>=', $formattedDate)->where('number', $id)->with(['user', 'status'])->get();
            // if (!$booking->isEmpty()) {
            //     return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves found', 200);
            // }
            $booking = Booking::where('reservationDate', '>=', $formattedDate)->whereHas('user', function ($q) use ($id) {
                $q->where('phone', 'like', '%' . $id . '%')->orWhere('dni', 'like', '%' . $id . '%');
            })->with(['user', 'status'])->get();    //where('id',$id)->where('status','active')->first();
            if (!$booking->isEmpty()) {
                return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves found', 200);
            }
            return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves not found', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, string $id): JsonResponse
    {
        try {
            $booking = Booking::find($id);
            if (is_null($booking)) {
                return BaseResponse::response(false, $booking, 'Reserve not found', 200);
            }

            $booking->update($request->validated());
            return BaseResponse::response(true, new BookingResource($booking), 'Update successfull', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $booking = Booking::with('status')->find($id);
            if (is_null($booking)) {
                return BaseResponse::response(false, $booking, 'Reserve not found', 200);
            }

            if ($booking->status->name == 'inactive') {
                return BaseResponse::response(false, null, 'Reserve not found', 200);
            }

            $status = Status::where('name', 'inactive')->first();

            if (is_null($status)) {
                return BaseResponse::response(false, null, 'Status "inactive" not found', 200);
            }

            $booking->statusId = $status->id;
            $booking->save();

            return BaseResponse::response(true, new BookingResource($booking), 'Reserve deleted', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Get the user reserves.
     */
    public function mybookings(Request $request, string $id): JsonResponse
    {
        try {
            $filter = $request->query('filter');
            $active = $request->query('active');
            $table = $request->query('table');
            $number = $request->query('number');
            $query = Booking::query();
            $query->whereHas('user', function ($q) use ($id) {
                $q->where('id', $id);
            });

            ($filter) && $query->where('reservationDate', 'like', '%' . $filter . '%');
            ($number) && $query->where('number', $number);
            ($active) && $query->whereHas('status', function ($q) use ($active) {
                $q->where('name', $active);
            });
            // ($table) && $query->whereHas('table', function ($q) use ($table) {
            //     $q->where('number', $table);
            // });

            $query->with(['user', 'status']);
            $booking = $query->get();

            if (!$booking->isEmpty()) {
                return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves found', 200);
            }
            return BaseResponse::response(true, $booking, 'Reserves not found', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function myBookings2(Request $request): JsonResponse
    {
        try {
            $userId = auth()->user()->id;
            $bookings = Booking::where('user_id', $userId)->where('statusId', '1')->get();

            if ($request->query('active')) {
                $status = $request->query('active');
                if ($status == 'active') {
                    $bookings = $bookings->where('reservationDate', '>=', date('Y-m-d'));
                } elseif ($status == 'inactive') {
                    $bookings = $bookings->where('reservationDate', '<=', Carbon::today());
                }
            }

            if ($request->query('filter')) {
                $date = $request->query('filter');

                $bookings = $bookings->where('reservationDate', $date);
            }
            if ($request->query('persons')) {
                $persons = $request->query('persons');

                $bookings = $bookings->where('persons', $persons);
            }
            if ($bookings->isEmpty()) {
                return BaseResponse::response(true, BookingResource::collection($bookings), 'Reserves not found', 200);
            }
            return BaseResponse::response(true, BookingResource::collection($bookings), 'Reserves found', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }


    /**
     * Get the today reserves.
     */
    public function todaybookings(): JsonResponse
    {
        try {
            $active = 'active';
            $query = Booking::query();
            $query->whereDate('reservationDate', Carbon::today());
            $query->whereHas('status', function ($q) use ($active) {
                $q->where('name', $active);
            });

            $query->with(['user', 'status']);
            $booking = $query->get();

            if (!$booking->isEmpty()) {
                return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves found', 200);
            }
            return BaseResponse::response(true, $booking, 'Reserves not found', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }
}
