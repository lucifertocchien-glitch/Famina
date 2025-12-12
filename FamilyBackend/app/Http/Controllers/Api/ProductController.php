<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Build query so we can support server-side search and category filters
        $q = $request->query('q');
        $category = $request->query('category');

        $query = SanPham::select(['MaSP','TenSP','GiaBan','HinhAnh','TonKho','MaDanhMuc']);

        if ($q) {
            $like = '%' . $q . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('TenSP', 'like', $like)
                    ->orWhere('MaSP', 'like', $like);
            });
        }

        if ($category) {
            $query->where('MaDanhMuc', $category);
        }

        $products = $query->get();

        $result = $products->map(function($p){
            // Normalize image path: stored values may include 'storage/' prefix or just 'products/...'
            $imagePath = (string) $p->HinhAnh;
            if (strpos($imagePath, 'storage/') === 0) {
                $imagePath = substr($imagePath, 8);
            }

            // Ensure we return path relative to storage (frontend will prefix with /storage/)
            return [
                'product_code' => $p->MaSP,
                'name' => $p->TenSP,
                'price' => (float) $p->GiaBan,
                'image' => $imagePath,
                'stock' => (int) $p->TonKho,
                'category_id' => $p->MaDanhMuc
            ];
        });

        return response()->json($result);
    }
}
