<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NhanVien;
use Illuminate\Support\Facades\Hash;

class WebSupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::query();

        if ($q = $request->query('q')) {
            $query->where('TenNV', 'like', "%{$q}%")
                  ->orWhere('MaNV', 'like', "%{$q}%")
                  ->orWhere('TaiKhoan', 'like', "%{$q}%");
        }

        $suppliers = $query->paginate(20);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenNV' => 'required|string|max:255',
            'SDT' => 'string|max:20',
            'DiaChi' => 'string',
            'TaiKhoan' => 'required|string|max:100|unique:NHAN_VIEN,TaiKhoan',
            'MatKhau' => 'required|string|min:6',
            'MaTL' => 'string|max:10',
            'TrangThai' => 'integer',
        ]);

        NhanVien::create([
            'MaNV' => 'SUP' . time(),
            'TenNV' => $request->TenNV,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
            'TaiKhoan' => $request->TaiKhoan,
            'MatKhau' => Hash::make($request->MatKhau),
            'MaTL' => $request->MaTL ?: 'SUPPLIER',
            'TrangThai' => $request->TrangThai ?? 1,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp được tạo thành công');
    }

    public function edit($id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            abort(404);
        }
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            abort(404);
        }

        $request->validate([
            'TenNV' => 'required|string|max:255',
            'SDT' => 'string|max:20',
            'DiaChi' => 'string',
            'TaiKhoan' => 'required|string|max:100|unique:NHAN_VIEN,TaiKhoan,' . $id . ',MaNV',
            'MaTL' => 'string|max:10',
            'TrangThai' => 'integer',
        ]);

        $updateData = [
            'TenNV' => $request->TenNV,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
            'TaiKhoan' => $request->TaiKhoan,
            'MaTL' => $request->MaTL ?: 'SUPPLIER',
            'TrangThai' => $request->TrangThai ?? 1,
        ];

        if ($request->filled('MatKhau')) {
            $request->validate(['MatKhau' => 'required|string|min:6']);
            $updateData['MatKhau'] = Hash::make($request->MatKhau);
        }

        $supplier->update($updateData);

        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp được cập nhật thành công');
    }

    public function destroy($id)
    {
        $supplier = NhanVien::find($id);
        if (!$supplier) {
            abort(404);
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Nhà cung cấp được xóa thành công');
    }
}
