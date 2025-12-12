<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cập nhật phiếu giao hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1 class="mb-4">Cập nhật phiếu {{ $phieu->MaPhieuGiao }}</h1>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="post" action="{{ route('shipping.update', $phieu->MaPhieuGiao) }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label class="form-label">Trạng thái giao hàng</label>
            <select name="TrangThaiGiao" class="form-select" required>
                <option value="chưa_giao" {{ $phieu->TrangThaiGiao == 'chưa_giao' ? 'selected' : '' }}>Chưa giao</option>
                <option value="đang_giao" {{ $phieu->TrangThaiGiao == 'đang_giao' ? 'selected' : '' }}>Đang giao</option>
                <option value="đã_giao" {{ $phieu->TrangThaiGiao == 'đã_giao' ? 'selected' : '' }}>Đã giao</option>
                <option value="giao_thất_bại" {{ $phieu->TrangThaiGiao == 'giao_thất_bại' ? 'selected' : '' }}>Giao thất bại</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mã vận đơn</label>
            <input name="MaVanDon" class="form-control" value="{{ old('MaVanDon', $phieu->MaVanDon) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tên Shipper</label>
            <input name="TenShipper" class="form-control" value="{{ old('TenShipper', $phieu->TenShipper) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="GhiChu" class="form-control">{{ old('GhiChu', $phieu->GhiChu) }}</textarea>
        </div>

        <button class="btn btn-primary">Lưu</button>
        <a href="{{ route('shipping.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
