<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostItemsController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        foreach ($items as $row) {
            $data["id"] = $row->id;
            $data["nama"] = $row->nama;
            $data["pajak"] = $row->pajak;
            $list[$row->id] = $data;
        }
        return response()->json([
            'success' => true,
            'message' => 'List Item',
            'data'    => $list
        ], 200);
    }

    public function show($id)
    {
        $items = Item::findOrfail($id);
        $data["id"] = $items->id;
        $data["nama"] = $items->nama;
        $data["pajak"] = $items->pajak;
        $list[$items->id] = $data;
        return response()->json([
            'success' => true,
            'message' => 'List Item',
            'data'    => $list
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            "id_pajak"    => [
                'required',
                'array',
                'min:2'
            ],
            "id_pajak.*"  => [
                'required',
                'integer'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $item = Item::create([
            'nama'     => $request->nama
        ]);

        foreach ($request->id_pajak as $row) {
            $itempajak = ItemPajak::create([
                'item_id'     => $item->id,
                'pajak_id'     => $row
            ]);
        }

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Item Created',
                'data'    => $item
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item Failed to Save',
        ], 409);
    }

    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            "id_pajak"    => [
                'required',
                'array',
                'min:2'
            ],
            "id_pajak.*"  => [
                'required',
                'integer'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data_item = Item::findOrFail($item->id);


        if ($data_item) {
            $item->update([
                'nama'     => $request->nama
            ]);
            $item_pajak = ItemPajak::where('item_id', $item->id)->delete();
            foreach ($request->id_pajak as $row) {
                $itempajak = ItemPajak::create([
                    'item_id'     => $item->id,
                    'pajak_id'     => $row
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item Updated',
                'data'    => $item
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item Not Found',
        ], 404);
    }

    public function destroy($id)
    {
        $item = Item::findOrfail($id);
        if ($item) {
            $item->delete();
            $item_pajak = ItemPajak::where('item_id', $id)->delete();


            return response()->json([
                'success' => true,
                'message' => 'Item Deleted',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Item Not Found',
        ], 404);
    }
}
