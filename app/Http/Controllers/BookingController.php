<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Status;
use App\NotFoundResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return BaseResponse::response(true, BookingResource::collection(Booking::with(['user','table','status'])->get()), '', 200);
        } catch (Exception $e) {            
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();                           
            $booking = Booking::create($validatedData);
            return BaseResponse::response(true, new BookingResource($booking), 'Create booking successfull', 201);
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
            $booking = Booking::where('reservationDate','>',$formattedDate)->where('number',$id)->whereHas('table', function($q) use ($id) { $q->where('number', $id); })->with(['user','table','status'])->get();             
            if (!$booking->isEmpty()) {
                return BaseResponse::response(true, BookingResource::collection($booking), 'Reserves found', 200);
            }
            $booking = Booking::whereHas('user', function($q) use ($id) { $q->where('phone','like', '%'.$id.'%')->orWhere('dni','like','%'.$id.'%'); })->with(['user','table','status'])->get();    //where('id',$id)->where('status','active')->first();            
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

            $status = Status::where('name','inactive')->first();

            if(is_null($status)){
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
            $query->whereHas('user', function($q) use($id){ $q->where('sup_id','like','%'.$id.'%'); });
                     
            ($filter) && $query->where('reservationDate', 'like', '%' . $filter . '%');            
            ($number) && $query->where('number', $number);
            ($active) && $query->whereHas('status', function($q) use ($active) {
                $q->where('name', $active);
            });
            ($table) && $query->whereHas('table', function($q) use ($table) {
                $q->where('number', $table);
            });            

            $query->with(['user', 'table', 'status']);
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
