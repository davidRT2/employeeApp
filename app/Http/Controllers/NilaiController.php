<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    //
    public function index(Request $request)
    {
        // Query untuk mengambil data dari tabel 'nilai'
        $query = DB::table('nilai')
            ->select(
                'nama',
                'nisn',
                DB::raw('SUM(nilaiRt->realistic) as realistic'),
                DB::raw('SUM(nilaiRt->investigative) as investigative'),
                DB::raw('SUM(nilaiRt->artistic) as artistic'),
                DB::raw('SUM(nilaiRt->social) as social'),
                DB::raw('SUM(nilaiRt->enterprising) as enterprising'),
                DB::raw('SUM(nilaiRt->conventional) as conventional')
            )
            ->groupBy('nama', 'nisn');

        // Filtering based on request parameters if needed
        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->input('nama') . '%');
        }

        if ($request->has('nisn')) {
            $query->where('nisn', $request->input('nisn'));
        }

        // Get the results
        $data = $query->get();

        // Check if data is empty
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No data found',
                'data' => [],
            ], 200);
        }

        // Return the response
        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Bulk insert data into the `nilai` table.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkInsert(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.id_status' => 'nullable|integer',
            'data.*.profil_tes_id' => 'nullable|integer',
            'data.*.id_siswa' => 'nullable|integer',
            'data.*.soal_bank_paket_id' => 'nullable|integer',
            'data.*.nama' => 'nullable|string|max:500',
            'data.*.nisn' => 'nullable|string|max:500',
            'data.*.jk' => 'nullable|in:L,P', // Assuming L for Laki-laki and P for Perempuan
            'data.*.skor' => 'nullable|numeric',
            'data.*.soal_benar' => 'nullable|integer',
            'data.*.nama_pelajaran' => 'nullable|string|max:500',
            'data.*.pelajaran_id' => 'nullable|integer',
            'data.*.materi_uji_id' => 'nullable|integer',
            'data.*.sesi' => 'nullable|integer',
            'data.*.id_pelaksanaan' => 'nullable|integer',
            'data.*.nama_sekolah' => 'nullable|string|max:500',
            'data.*.total_soal' => 'nullable|integer',
            'data.*.urutan' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Insert data into the `nilai` table
        try {
            DB::table('nilai')->insert($request->input('data'));

            return response()->json([
                'status' => 'success',
                'message' => 'Data inserted successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to insert data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
