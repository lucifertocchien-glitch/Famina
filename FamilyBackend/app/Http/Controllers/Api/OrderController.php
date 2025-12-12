<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\KhachHang;
use App\Models\SanPham;
use App\Models\DonBanHang;
use App\Models\DonBanHangChiTiet;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'cart' => 'required|array|min:1'
        ]);

        $user = KhachHang::where('Email', $request->email)->first();
        if (!$user) return response()->json(['message' => 'Customer not found'], 404);

        $cart = $request->cart;

        DB::beginTransaction();
        try {
            $maDon = 'DON'.time().Str::random(4);

            $tongHang = 0;
            // create order header
            $order = DonBanHang::create([
                'MaDon' => $maDon,
                'NgayDat' => now(),
                'TongTienHang' => 0,
                'TongThueVAT' => 0,
                'TongChietKhau' => 0,
                'TongThanhToan' => 0,
                'HinhThucTT' => 'COD',
                'TrangThai' => 'ChoXuLy',
                'LoaiDon' => 'BanLe',
                'MaKH' => $user->MaKH,
                'NguoiBan' => 'CUSTOMER'
            ]);

            foreach ($cart as $item) {
                $code = $item['product_code'];
                $qty = intval($item['quantity']);
                $product = SanPham::find($code);
                if (!$product) {
                    throw new \Exception("Product $code not found");
                }
                if ($product->TonKho < $qty) {
                    throw new \Exception("Insufficient stock for $product->TenSP");
                }

                $donGia = (float)$product->GiaBan;
                $thanhTien = $donGia * $qty;
                $tongHang += $thanhTien;

                // insert detail using raw query for composite key
                DB::table('CT_DON_BAN')->insert([
                    'MaDon' => $maDon,
                    'MaSP' => $product->MaSP,
                    'SoLuong' => $qty,
                    'DonGia' => $donGia,
                    'ThueVAT' => 0,
                    'ChietKhau' => 0,
                    'ThanhTien' => $thanhTien
                ]);

                // decrement stock
                DB::table('SAN_PHAM')
                    ->where('MaSP', $product->MaSP)
                    ->decrement('TonKho', $qty);
            }

            // finalize order totals
            $order->TongTienHang = $tongHang;
            $order->TongThanhToan = $tongHang; // no taxes in this simple flow
            $order->save();

            DB::commit();

            return response()->json(['message' => 'Order placed', 'order_id' => $maDon]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage() ?: 'Error creating order', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $order = DonBanHang::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);
        $order->TrangThai = $request->status;
        $order->save();
        return response()->json(['message' => 'Status updated']);
    }

    public function myOrders(Request $request)
    {
        try {
            $user = $request->attributes->get('khachhang');
            if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

            // Get orders for this customer
            $orders = DonBanHang::where('MaKH', $user->MaKH)
                ->orderBy('NgayDat', 'desc')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['orders' => []]);
            }

            // Get details for each order
            $ordersWithDetails = $orders->map(function($order) {
                $items = DB::table('CT_DON_BAN')
                    ->join('SAN_PHAM', 'CT_DON_BAN.MaSP', '=', 'SAN_PHAM.MaSP')
                    ->where('CT_DON_BAN.MaDon', $order->MaDon)
                    ->select(
                        'CT_DON_BAN.MaSP',
                        'SAN_PHAM.TenSP as TenSanPham',
                        'CT_DON_BAN.SoLuong',
                        'CT_DON_BAN.DonGia',
                        'CT_DON_BAN.ThanhTien'
                    )
                    ->get();

                return [
                    'MaDon' => $order->MaDon,
                    'NgayDat' => $order->NgayDat,
                    'TongTien' => $order->TongThanhToan,
                    'TrangThai' => $order->TrangThai,
                    'items' => $items
                ];
            });

            return response()->json(['orders' => $ordersWithDetails]);
        } catch (\Exception $e) {
            \Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching orders', 'error' => $e->getMessage()], 500);
        }
    }
}

