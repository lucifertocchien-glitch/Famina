<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1 class="mb-4">Đơn hàng {{ $don->MaDon }}</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <p><strong>Mã khách hàng:</strong> {{ $don->MaKH }}</p>
            <p><strong>Nhân viên bán:</strong> {{ $don->NguoiBan }}</p>
            <p><strong>Ngày đặt:</strong> {{ $don->NgayDat }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Trạng thái:</strong> <span class="badge bg-info">{{ $don->TrangThai }}</span></p>
            <p><strong>Hình thức TT:</strong> {{ $don->HinhThucTT }}</p>
        </div>
    </div>

    <h5>Chi tiết sản phẩm</h5>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Mã SP</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thuế VAT</th>
            <th>Chiết khấu</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        @foreach($chi_tiet as $ct)
            <tr>
                <td>{{ $ct->MaSP }}</td>
                <td>{{ $ct->SoLuong }}</td>
                <td>{{ number_format($ct->DonGia, 0, ',', '.') }}</td>
                <td>{{ number_format($ct->ThueVAT, 0, ',', '.') }}</td>
                <td>{{ number_format($ct->ChietKhau, 0, ',', '.') }}</td>
                <td>{{ number_format($ct->ThanhTien, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <p><strong>Tổng tiền hàng:</strong> {{ number_format($don->TongTienHang, 0, ',', '.') }}</p>
            <p><strong>Thuế VAT:</strong> {{ number_format($don->TongThueVAT, 0, ',', '.') }}</p>
            <p><strong>Chiết khấu:</strong> {{ number_format($don->TongChietKhau, 0, ',', '.') }}</p>
            <h5><strong>Tổng thanh toán:</strong> {{ number_format($don->TongThanhToan, 0, ',', '.') }}</h5>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('orders.edit', $don->MaDon) }}" class="btn btn-warning">Sửa</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
</body>
</html>
