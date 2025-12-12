<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\KhachHang;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $exists = KhachHang::where('Email', $request->email)->first();
        if ($exists) {
            return response()->json(['message' => 'Email đã được sử dụng'], 422);
        }

        $maKH = 'KH'.time().Str::random(3);

        $kh = new KhachHang();
        $kh->MaKH = $maKH;
        $kh->TenKH = $request->name;
        $kh->Email = $request->email;
        $kh->SDT = $request->phone ?? null;
        $kh->MatKhau = Hash::make($request->password);
        $kh->DiemTichLuy = 0;
        $kh->api_token = Str::random(60);
        $kh->save();

        return response()->json(['message' => 'Registered', 'user' => [
            'MaKH' => $kh->MaKH,
            'name' => $kh->TenKH,
            'email' => $kh->Email,
            'phone' => $kh->SDT
        ], 'token' => $kh->api_token], 201);
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
