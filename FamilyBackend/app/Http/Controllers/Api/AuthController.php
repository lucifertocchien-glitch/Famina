<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\KhachHang;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validate chặt chẽ: Email và SĐT phải duy nhất trong bảng KHACH_HANG
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:KHACH_HANG,Email',
            'phone' => 'required|numeric|digits_between:9,11|unique:KHACH_HANG,SDT',
            'password' => 'required|min:6'
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'phone.numeric' => 'Số điện thoại không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // 2. Tạo User
        $maKH = 'KH' . time() . Str::random(3);
        $kh = new KhachHang();
        $kh->MaKH = $maKH;
        $kh->TenKH = $request->name;
        $kh->Email = $request->email;
        $kh->SDT = $request->phone; // Lưu SĐT
        $kh->MatKhau = Hash::make($request->password);
        $kh->api_token = Str::random(60);
        $kh->DiemTichLuy = 0;
        $kh->save();

        return response()->json([
            'message' => 'Đăng ký thành công',
            'token' => $kh->api_token,
            'user' => [
                'name' => $kh->TenKH,
                'email' => $kh->Email,
                'phone' => $kh->SDT,
                'address' => $kh->DiaChi
            ]
        ], 201);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $kh = KhachHang::where('Email', $request->email)->first();
        if (!$kh) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        if (!Hash::check($request->password, $kh->MatKhau)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        $kh->api_token = Str::random(60);
        $kh->save();

        return response()->json(['message' => 'Logged in', 'user' => [
            'MaKH' => $kh->MaKH,
            'name' => $kh->TenKH,
            'email' => $kh->Email,
            'phone' => $kh->SDT
        ], 'token' => $kh->api_token]);
    }
}
