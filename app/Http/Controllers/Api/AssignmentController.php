<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Get assignment details untuk auto-fill form
     */
    public function show($id)
    {
        $assignment = Assignment::with(['product', 'assignedUser'])->find($id);

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'lokasi_tujuan' => $assignment->lokasi_tujuan,
                'qty_target' => $assignment->qty_target,
                'product' => [
                    'id' => $assignment->product->id,
                    'nama_barang' => $assignment->product->nama_barang,
                ],
                'assigned_user' => [
                    'id' => $assignment->assignedUser->id,
                    'name' => $assignment->assignedUser->name,
                ]
            ]
        ]);
    }
}
