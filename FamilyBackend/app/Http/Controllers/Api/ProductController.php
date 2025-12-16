<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = SanPham::query();

        // Xử lý tìm kiếm
        if ($q = $request->query('q')) {
            $query->where('TenSP', 'like', "%{$q}%")
                  ->orWhere('MaSP', 'like', "%{$q}%");
        }

        // Map dữ liệu Legacy (Tiếng Việt) sang chuẩn JSON cho Frontend
        $products = $query->get()->map(function($p) {
            return [
                'id' => $p->MaSP,            // ID duy nhất
                'product_code' => $p->MaSP,  // Frontend cần key này
                'name' => $p->TenSP,
                'price' => (float)$p->GiaBan,
                'image' => $p->HinhAnh,
                'category_id' => $p->MaDanhMuc,
                'in_stock' => (int)$p->TonKho
            ];
        });

        return response()->json($products);
    }
}