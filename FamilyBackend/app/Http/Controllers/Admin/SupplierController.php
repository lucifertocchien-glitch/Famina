<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::query();

        if ($q = $request->query('q')) {
            $query->where('TenNV', 'like', "%{$q}%")
                  ->orWhere('MaNV', 'like', "%{$q}%")
                  ->orWhere('TaiKhoan', 'like', "%{$q}%");
        }

        $suppliers = $query->get()->map(function($s) {
            return [
                'id' => $s->MaNV,
                'name' => $s->TenNV,
                'phone' => $s->SDT,
                'address' => $s->DiaChi,
                'username' => $s->TaiKhoan,
                'role' => $s->MaTL,
                'status' => $s->TrangThai,
            ];
        });

        return response()->json($suppliers);
    }

    public function show($id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return response()->json([
            'id' => $supplier->MaNV,
            'name' => $supplier->TenNV,
            'phone' => $supplier->SDT,
            'address' => $supplier->DiaChi,
            'username' => $supplier->TaiKhoan,
            'role' => $supplier->MaTL,
            'status' => $supplier->TrangThai,
        ]);
    }

    public function update(Request $request, $id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'address' => 'string',
            'username' => 'string|max:100',
            'role' => 'string|max:10',
            'status' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier->update([
            'TenNV' => $request->name ?? $supplier->TenNV,
            'SDT' => $request->phone ?? $supplier->SDT,
            'DiaChi' => $request->address ?? $supplier->DiaChi,
            'TaiKhoan' => $request->username ?? $supplier->TaiKhoan,
            'MaTL' => $request->role ?? $supplier->MaTL,
            'TrangThai' => $request->status ?? $supplier->TrangThai,
        ]);

        return response()->json(['message' => 'Updated']);
    }

    public function destroy($id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $supplier->delete();
        return response()->json(['message' => 'Deleted']);
    }
}