<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\SanPham;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('khachhang');
        $items = GioHang::where('MaKH', $user->MaKH)->get();

        $result = $items->map(function($it){
            $p = SanPham::find($it->MaSP);
            return [
                'id' => $it->id,
                'product_code' => $it->MaSP,
                'name' => $p ? $p->TenSP : null,
                'price' => $p ? (float)$p->GiaBan : 0,
                'quantity' => (int)$it->SoLuong,
                'image' => $p ? $p->HinhAnh : null
            ];
        });

        return response()->json($result);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->attributes->get('khachhang');
        $maSP = $request->product_code;

        $existing = GioHang::where('MaKH', $user->MaKH)->where('MaSP', $maSP)->first();
        if ($existing) {
            $existing->SoLuong += $request->quantity;
            $existing->save();
        } else {
            GioHang::create([
                'MaKH' => $user->MaKH,
                'MaSP' => $maSP,
                'SoLuong' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Added to cart']);
    }

    public function remove(Request $request, $id)
    {
        $user = $request->attributes->get('khachhang');
        $item = GioHang::where('id', $id)->where('MaKH', $user->MaKH)->first();
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        $item->delete();
        return response()->json(['message' => 'Removed']);
    }
}
