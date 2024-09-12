<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Validator;
use Illuminate\Support\Facades\Log;
use DB;



class AgendaController extends Controller
{
    /**
     * Add a new agenda
     * 
     * This endpoint allows a user to add a new agenda item to the system.
     * 
     * @group Agenda Management
     * 
     * @bodyParam tanggal date required The date of the agenda. Example: 2024-08-27
     * @bodyParam waktu string required The time of the agenda. Example: 09:00
     * @bodyParam kegiatan string required The activity of the agenda. Example: Meeting with client
     * @bodyParam tipe_acara string required The type of event. Example: Offline
     * @bodyParam tempat string required The location of the agenda. Example: Jakarta
     * @bodyParam delegasi string required The delegation for the agenda. Example: Andra
     * @bodyParam drescode string required The dress code for the agenda. Example: Business Casual
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Agenda berhasil ditambahkan",
     *   "data": {
     *     "id": 1,
     *     "tanggal": "2024-08-27",
     *     "waktu": "09:00",
     *     "kegiatan": "Meeting with client",
     *     "tipe_acara": "Offline",
     *     "tempat": "Jakarta",
     *     "delegasi": "Andra",
     *     "drescode": "Business Casual"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan dalam validasi input",
     *   "data": {
     *     "tanggal": ["The tanggal field is required."],
     *     "waktu": ["The waktu field is required."],
     *     "kegiatan": ["The kegiatan field is required."]
     *   }
     * }
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'kegiatan' => 'required|string',
            'tipe_acara' => 'required|string',
            'tempat' => 'required|string',
            'delegasi' => 'required',
            'drescode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan dalam validasi input',
                'data' => $validator->errors()
            ], 422);
        }

        // $agenda = Agenda::create($request->all());
        if ($request->filled ('tanggal')) $tanggal = $request->input('tanggal');
        if ($request->filled ('waktu')) $waktu = $request->input('waktu');
        if ($request->filled ('kegiatan')) $kegiatan = $request->input('kegiatan');
        if ($request->filled ('tipe_acara')) $tipe_acara = $request->input('tipe_acara');
        if ($request->filled ('tempat')) $tempat = $request->input('tempat');
        if ($request->filled ('delegasi')) $delegasi = $request->input('delegasi');
        if ($request->filled ('drescode')) $drescode = $request->input('drescode');


    //     $file = $request->file('file');
    //     $original_file=$file->getClientOriginalName();
    //    $file_name = time().'.'.$file->getClientOriginalExtension();
    //     $file->move(public_path('/upload'),$file_name);
    // kalo baris pertama kosong jangan jalanin 3 baris ke bawah tapi kalo ada isinya baru jalanin 

    
    $file = $request->file('file');
    $original_file = null;
    $file_name = null;
    
    if ($file !== null) {
        $original_file = $file->getClientOriginalName();
        $file_name = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('/upload'), $file_name);
    }

        $agenda = DB::transaction(function() use (
            $tanggal,
            $waktu,
            $kegiatan,
            $tipe_acara,
            $tempat,
            $delegasi,
            $drescode,
            $original_file,
            $file_name)
        {
          $data = DB::table ('agendas')->insert([
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'kegiatan' => $kegiatan,
            'tipe_acara' => $tipe_acara,
            'tempat' => $tempat,
            'delegasi' => $delegasi,
            'drescode' => $drescode,
            'original_file' => $original_file,
            'file_name' => $file_name
          ]);
            




        });
        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil ditambahkan',
            'data' => $agenda
        ], 201);


	}







    /**
     * List all agendas
     * 
     * This endpoint retrieves a list of all agenda items in the system.
     * 
     * @group Agenda Management
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Daftar agenda berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "tanggal": "2024-08-27",
     *       "waktu": "09:00",
     *       "kegiatan": "Meeting with client",
     *       "tipe_acara": "Offline",
     *       "tempat": "Jakarta",
     *       "delegasi": "Andra",
     *       "drescode": "Business Casual"
     *     }
     *   ]
     * }
     */
    public function list()
    {
        $agendas = Agenda::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar agenda berhasil diambil',
            'data' => $agendas
        ], 200);
    }

    /**
     * Get details of a specific agenda
     * 
     * This endpoint retrieves details of a specific agenda item by its ID.
     * 
     * @group Agenda Management
     * 
     * @urlParam id integer required The ID of the agenda item. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Detail agenda berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "tanggal": "2024-08-27",
     *     "waktu": "09:00",
     *     "kegiatan": "Meeting with client",
     *     "tipe_acara": "Offline",
     *     "tempat": "Jakarta",
     *     "delegasi": "Andra",
     *     "drescode": "Business Casual"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Agenda tidak ditemukan"
     * }
     */
    public function detail($id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail agenda berhasil diambil',
            'data' => $agenda
        ], 200);
    }

    /**
     * Update a specific agenda
     * 
     * This endpoint allows a user to update the details of a specific agenda item.
     * 
     * @group Agenda Management
     * 
     * @urlParam id integer required The ID of the agenda item. Example: 1
     * @bodyParam tanggal date required The date of the agenda. Example: 2024-08-27
     * @bodyParam waktu string required The time of the agenda. Example: 09:00
     * @bodyParam kegiatan string required The activity of the agenda. Example: Meeting with client
     * @bodyParam tipe_acara string required The type of event. Example: Offline
     * @bodyParam tempat string required The location of the agenda. Example: Jakarta
     * @bodyParam delegasi string required The delegation for the agenda. Example: Andra
     * @bodyParam drescode string required The dress code for the agenda. Example: Business Casual
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Agenda berhasil diperbarui",
     *   "data": {
     *     "id": 1,
     *     "tanggal": "2024-08-27",
     *     "waktu": "09:00",
     *     "kegiatan": "Meeting with client",
     *     "tipe_acara": "Offline",
     *     "tempat": "Jakarta",
     *     "delegasi": "Andra",
     *     "drescode": "Business Casual"
     *   }
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "Ada kesalahan dalam validasi input",
     *   "data": {
     *     "tanggal": ["The tanggal field is required."],
     *     "waktu": ["The waktu field is required."],
     *     "kegiatan": ["The kegiatan field is required."]
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Agenda tidak ditemukan"
     * }
     */
    public function edit(Request $request, $id)
    {
        printf($request);
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            // 'tanggal' => 'required|date',
            // 'waktu' => 'required',
            // 'kegiatan' => 'required|string',
            // 'tipe_acara' => 'required|string',
            // 'tempat' => 'required|string',
            // 'delegasi' => 'required|string',
            // 'drescode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan dalam validasi input',
                'data' => $validator->errors()
            ], 422);
        }

        
        if ($request->filled ('tanggal')) $tanggal = $request->input('tanggal');
        if ($request->filled ('waktu')) $waktu = $request->input('waktu');
        if ($request->filled ('kegiatan')) $kegiatan = $request->input('kegiatan');
        if ($request->filled ('tipe_acara')) $tipe_acara = $request->input('tipe_acara');
        if ($request->filled ('tempat')) $tempat = $request->input('tempat');
        if ($request->filled ('delegasi')) $delegasi = $request->input('delegasi');
        if ($request->filled ('drescode')) $drescode = $request->input('drescode');
 

    
    $file = $request->file('file');
    $original_file = null;
    $file_name = null;
    
    if ($file !== null) {
        $original_file = $file->getClientOriginalName();
        $file_name = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('/upload'), $file_name);
    }

        $agenda = DB::transaction(function() use (
            $tanggal,
            $waktu,
            $kegiatan,
            $tipe_acara,
            $tempat,
            $delegasi,
            $drescode,
            $original_file,
            $file_name,
            $id)
        {
          $data = DB::table ('agendas')->where('id',$id)->update([
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'kegiatan' => $kegiatan,
            'tipe_acara' => $tipe_acara,
            'tempat' => $tempat,
            'delegasi' => $delegasi,
            'drescode' => $drescode,
            'original_file' => $original_file,
            'file_name' => $file_name
          ]);
            




        });
        

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil diperbarui',
            'data' => $agenda
        ], 200);
    }

    /**
     * Delete a specific agenda
     * 
     * This endpoint allows a user to delete a specific agenda item from the system.
     * 
     * @group Agenda Management
     * 
     * @urlParam id integer required The ID of the agenda item. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Agenda berhasil dihapus"
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Agenda tidak ditemukan"
     * }
     */
    public function delete($id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json([
                'success' => false,
                'message' => 'Agenda tidak ditemukan',
            ], 404);
        }

        $agenda->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agenda berhasil dihapus',
        ], 200);
    }
/**
 * Get agendas within a specific date range
 * 
 * This endpoint retrieves all agenda items that fall within a specified date range.
 * 
 * @group Agenda Management
 * 
 * @queryParam start_date date required The start date of the range. Example: 2024-08-01
 * @queryParam end_date date required The end date of the range. Example: 2024-08-31
 * 
 * @response 200 {
 *   "success": true,
 *   "message": "Agenda dalam rentang tanggal berhasil diambil",
 *   "data": [
 *     {
 *       "id": 1,
 *       "tanggal": "2024-08-05",
 *       "waktu": "10:00",
 *       "kegiatan": "Rapat Bulanan",
 *       "tipe_acara": "Offline",
 *       "tempat": "Ruang Meeting A",
 *       "delegasi": "Tim A",
 *       "drescode": "Batik"
 *     }
 *   ]
 * }
 * 
 * @response 404 {
 *   "success": false,
 *   "message": "Tidak ada agenda yang ditemukan dalam rentang tanggal ini"
 * }
 */
public function getAgendaByDateRange(Request $request)
{
    // Validasi input tanggal
    $validator = Validator::make($request->all(), [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Ada kesalahan dalam validasi input',
            'data' => $validator->errors()
        ], 422);
    }

    // Ambil tanggal awal dan akhir dari input
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query untuk mendapatkan agenda dalam rentang tanggal
    $agendas = Agenda::whereBetween('tanggal', [$startDate, $endDate])->get();

    if ($agendas->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada agenda yang ditemukan dalam rentang tanggal ini',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Agenda dalam rentang tanggal berhasil diambil',
        'data' => $agendas
    ], 200);
}
/**
 * Get agendas for today
 * 
 * This endpoint retrieves all agenda items for the current date.
 * 
 * @group Agenda Management
 * 
 * @response 200 {
 *   "success": true,
 *   "message": "Agenda hari ini berhasil diambil",
 *   "data": [
 *     {
 *       "id": 1,
 *       "tanggal": "2024-08-29",
 *       "waktu": "10:00",
 *       "kegiatan": "Rapat Bulanan",
 *       "tipe_acara": "Offline",
 *       "tempat": "Ruang Meeting A",
 *       "delegasi": "Tim A",
 *       "drescode": "Batik"
 *     }
 *   ]
 * }
 * 
 * @response 404 {
 *   "success": false,
 *   "message": "Tidak ada agenda yang ditemukan untuk hari ini"
 * }
 */
public function getAgendaForToday()
{
    // Ambil tanggal hari ini
    $today = now()->format('Y-m-d');

    // Query untuk mendapatkan agenda pada tanggal hari ini
    // $agendas = Agenda::whereDate('tanggal', $today)->get();
    $agendas = DB::select(DB::raw("select agendas.file_name,agendas.original_file,agendas.id,agendas.drescode,agendas.tanggal,agendas.waktu,agendas.kegiatan,agendas.tipe_acara,agendas.tempat,agendas.delegasi,agendas.id_dresscode,dresscodes.dresscode from agendas
left join dresscodes 
on dresscodes.id = agendas.id_dresscode
where agendas.tanggal = 'now'"))
             
             
             ;

    // if ($agendas != null) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Tidak ada agenda yang ditemukan untuk hari ini',
    //     ], 404);
    // }

    return response()->json([
        'success' => true,
        'message' => 'Agenda hari ini berhasil diambil',
        'data' => $agendas
    ], 200);
}

public function copyText($id)
{
    // Ambil data agenda berdasarkan ID
    $agenda = Agenda::find($id);

    if (!$agenda) {
        return response()->json(['message' => 'Agenda not found'], 404);
    }

    // Format teks yang akan disalin
    $text = "Agenda Kegiatan: " . $agenda->kegiatan . "\n" .
            "Tanggal: " . $agenda->tanggal . "\n" .
            "Waktu: " . $agenda->waktu . "\n" .
            "Tempat: " . $agenda->tempat . "\n" .
            "Tipe Acara: " . $agenda->tipe_acara . "\n" .
            "Delegasi: " . $agenda->delegasi . "\n" .
            "Dresscode: " . $agenda->drescode;

    return response()->json(['text' => $text]);
}

public function copyTextToday()
{
    // Ambil tanggal hari ini
    $today = now()->format('Y-m-d');

    // Query untuk mendapatkan agenda pada tanggal hari ini
    $agenda = Agenda::whereDate('tanggal', $today)->first();

    if (!$agenda) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada agenda yang ditemukan untuk hari ini',
        ], 404);
    }

    // Format teks yang akan disalin
    $text = "Agenda Kegiatan: " . $agenda->kegiatan . "\n" .
            "Tanggal: " . $agenda->tanggal . "\n" .
            "Waktu: " . $agenda->waktu . "\n" .
            "Tempat: " . $agenda->tempat . "\n" .
            "Tipe Acara: " . $agenda->tipe_acara . "\n" .
            "Delegasi: " . $agenda->delegasi . "\n" .
            "Dresscode: " . $agenda->drescode;

    return response()->json([
        'success' => true,
        'message' => 'Teks agenda hari ini berhasil disalin',
        'text' => $text
    ], 200);
}


}
