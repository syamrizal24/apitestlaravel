<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostPajaksController extends Controller
{
    public function index()
    {
        $pajak = Pajak::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Pajak',
            'data'    => $pajak
        ], 200);
    }

    public function show($id)
    {
        $pajak = Pajak::findOrfail($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Pajak',
            'data'    => $pajak
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'rate' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pajak = Pajak::create([
            'nama'     => $request->nama,
            'rate'     => $request->rate
        ]);

        if ($pajak) {
            return response()->json([
                'success' => true,
                'message' => 'Pajak Created',
                'data'    => $pajak
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pajak Failed to Save',
        ], 409);
    }

    public function update(Request $request, Pajak $pajak)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'rate' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data_pajak = Pajak::findOrFail($pajak->id);

        if ($data_pajak) {
            $pajak->update([
                'nama'     => $request->nama,
                'rate'     => $request->rate
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pajak Updated',
                'data'    => $pajak
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pajak Not Found',
        ], 404);
    }

    public function destroy($id)
    {
        $pajak = Pajak::findOrfail($id);

        if ($pajak) {
            $pajak->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pajak Deleted',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Pajak Not Found',
        ], 404);
    }
}
