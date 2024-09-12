<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dresscode;
use Validator;
use DB;

class DresscodeController extends Controller
{
    /**
     * Add a new dresscode
     * 
     * This endpoint allows adding a new dresscode to the system.
     * 
     * @group Dresscode Management
     * 
     * @bodyParam hari string required The day for the dress code. Example: Monday
     * @bodyParam dresscode string required The dress code for the day. Example: Formal
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Dresscode berhasil dibuat",
     *   "data": {
     *     "id": 1,
     *     "hari": "Monday",
     *     "dresscode": "Formal"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan",
     *   "data": {
     *     "hari": [
     *       "The hari field is required."
     *     ],
     *     "dresscode": [
     *       "The dresscode field is required."
     *     ]
     *   }
     * }
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|string|max:255',
            'dresscode' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ], 422);
        }

        $dresscode = Dresscode::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Dresscode berhasil dibuat',
            'data' => $dresscode
        ], 201);
    }

    /**
     * List all dresscodes
     * 
     * This endpoint retrieves a list of all dresscodes in the system.
     * 
     * @group Dresscode Management
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Daftar Dresscode",
     *   "data": [
     *     {
     *       "id": 1,
     *       "hari": "Monday",
     *       "dresscode": "Formal"
     *     }
     *   ]
     * }
     */
    public function list()
    {
        // $dresscodes = Dresscode::all();
        $dresscodes = DB::table('dresscodes')
             ->select(DB::raw('id,hari, dresscode,created_at,updated_at'))
             
             
             ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Dresscode',
            'data' => $dresscodes
        ], 200);
    }

    /**
     * Get details of a specific dresscode
     * 
     * This endpoint retrieves details of a specific dresscode by its ID.
     * 
     * @group Dresscode Management
     * 
     * @urlParam id integer required The ID of the dresscode. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Detail Dresscode",
     *   "data": {
     *     "id": 1,
     *     "hari": "Monday",
     *     "dresscode": "Formal"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Dresscode tidak ditemukan"
     * }
     */
    public function detail($id)
    {
        $dresscode = Dresscode::find($id);

        if (is_null($dresscode)) {
            return response()->json([
                'success' => false,
                'message' => 'Dresscode tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Dresscode',
            'data' => $dresscode
        ], 200);
    }

    /**
     * Update a specific dresscode
     * 
     * This endpoint allows updating details of a specific dresscode.
     * 
     * @group Dresscode Management
     * 
     * @urlParam id integer required The ID of the dresscode. Example: 1
     * @bodyParam hari string The day for the dress code. Example: Monday
     * @bodyParam dresscode string The dress code for the day. Example: Formal
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Dresscode berhasil diperbarui",
     *   "data": {
     *     "id": 1,
     *     "hari": "Monday",
     *     "dresscode": "Casual"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan",
     *   "data": {
     *     "hari": [
     *       "The hari field is required."
     *     ]
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Dresscode tidak ditemukan"
     * }
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'sometimes|required|string|max:255',
            'dresscode' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ], 422);
        }

        $dresscode = Dresscode::find($id);

        if (is_null($dresscode)) {
            return response()->json([
                'success' => false,
                'message' => 'Dresscode tidak ditemukan',
            ], 404);
        }

        $dresscode->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Dresscode berhasil diperbarui',
            'data' => $dresscode
        ], 200);
    }

    /**
     * Delete a specific dresscode
     * 
     * This endpoint allows deleting a specific dresscode from the system.
     * 
     * @group Dresscode Management
     * 
     * @urlParam id integer required The ID of the dresscode. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Dresscode berhasil dihapus"
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Dresscode tidak ditemukan"
     * }
     */
    public function delete($id)
    {
        $dresscode = Dresscode::find($id);

        if (is_null($dresscode)) {
            return response()->json([
                'success' => false,
                'message' => 'Dresscode tidak ditemukan',
            ], 404);
        }

        $dresscode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dresscode berhasil dihapus',
        ], 200);
    }
}
