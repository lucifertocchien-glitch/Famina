<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cập nhật đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1 class="mb-4">Cập nhật đơn hàng {{ $don->MaDon }}</h1>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="post" action="{{ route('orders.update', $don->MaDon) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="TrangThai" class="form-select" required>
                <option value="chờ_xác_nhận" {{ $don->TrangThai == 'chờ_xác_nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="đã_xác_nhận" {{ $don->TrangThai == 'đã_xác_nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="đang_giao" {{ $don->TrangThai == 'đang_giao' ? 'selected' : '' }}>Đang giao</option>
                <option value="đã_giao" {{ $don->TrangThai == 'đã_giao' ? 'selected' : '' }}>Đã giao</option>
                <option value="đã_hủy" {{ $don->TrangThai == 'đã_hủy' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình thức thanh toán</label>
            <input name="HinhThucTT" class="form-control" value="{{ old('HinhThucTT', $don->HinhThucTT) }}">
        </div>

        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
