<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Http\Resources\TableResource;
use App\Models\Booking;
use App\Models\Table;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return BaseResponse::response(true, TableResource::collection(Table::all()->where('status', 'active')), '', 200);
        } catch (Exception $e) {            
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateTableRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();    
            
            $validatedData['status'] = $validatedData['status'] ?? 'active';

            $table = Table::create($validatedData);
            return BaseResponse::response(true, new TableResource($table), 'Create table successfull', 201);
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
            $table = Table::where('id',$id)->where('status','active')->first();
            if (is_null($table)) {
                return BaseResponse::response(false, $table, 'Table not found', 200);
            }
            return BaseResponse::response(true, new TableResource($table), '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, string $id): JsonResponse
    {
        try {
            $table = Table::find($id);
            // Log::error('error', $table);
            if (is_null($table)) {
                return BaseResponse::response(false, $table, 'Table not found', 200);
            }
            $table->update($request->validated());            
            return BaseResponse::response(true, new TableResource($table), 'Update successfull', 200);
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
            $table = Table::find($id);
            if (is_null($table)) {
                return BaseResponse::response(false, $table, 'Table not found', 200);
            }

            if ($table->status == 'inactive') {
                return BaseResponse::response(false, null, 'Table not found', 200);
            }

            $table->status = 'inactive';
            $table->save();
               
            return BaseResponse::response(true, new TableResource($table), 'Table deleted', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    /**
     * Show the tables availables.
     */
    public function available(): JsonResponse
    {
        try {            
            $formattedDate = Carbon::today()->toDateString();            
            
            $bookedTablesIds = Booking::where('reservationDate',">",$formattedDate)->pluck('tableId')->toArray();            
            $tables_availables = Table::whereNotIn('id', $bookedTablesIds)->where('status','active')->get();            
            $tables_reserved = Table::whereIn('id', $bookedTablesIds)->where('status','active')->get();

            $resp = ['availables' => $tables_availables, 'reserved' => $tables_reserved];
               
            return BaseResponse::response(true, $resp, 'Tables availables', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }
}
