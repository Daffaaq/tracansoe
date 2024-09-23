<?php

namespace App\Http\Controllers;

use App\Models\advice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdviceController extends Controller
{

    public function postAdvice(Request $request)
    {
        // Ubah email menjadi lowercase
        $request->merge(['email' => strtolower($request->email)]);

        // Periksa apakah request adalah AJAX
        if ($request->ajax()) {
            // Validasi request
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', // regex untuk huruf kecil
                ],
                'advice' => 'required|string|min:10|max:5000'
            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal, Silahkan Cek Lagi Input Data anda',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Jika validasi berhasil, simpan data
            $advice = new advice();
            $advice->nama = $request->nama;
            $advice->email = $request->email;
            $advice->advice = $request->advice;
            $advice->save();

            return response()->json(['message' => 'Terima kasih telah memberikan Saran/kritik kepada kami. Kritik/Saran Anda dapat berkontribusi terhadap pelayanan kami.'], 200);
        }

        // Jika bukan request AJAX, kembalikan respons Method Not Allowed
        return response()->json(['message' => 'Method not allowed'], 405);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
