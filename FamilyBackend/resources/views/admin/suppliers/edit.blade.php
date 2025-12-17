<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding-left: 250px;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')
    @include('admin.navbar')

    <div class="container p-4">
        <h1 class="mb-4"><i class="fas fa-edit"></i> Sửa Nhà cung cấp</h1>

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('suppliers.update', $supplier->MaNV) }}" method="post">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="form-label">Tên nhân viên</label>
                <input name="TenNV" class="form-control @error('TenNV') is-invalid @enderror" value="{{ old('TenNV', $supplier->TenNV) }}">
                @error('TenNV')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tài khoản</label>
                <input name="TaiKhoan" class="form-control @error('TaiKhoan') is-invalid @enderror" value="{{ old('TaiKhoan', $supplier->TaiKhoan) }}">
                @error('TaiKhoan')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu (để trống nếu không thay đổi)</label>
                <input name="MatKhau" type="password" class="form-control @error('MatKhau') is-invalid @enderror">
                @error('MatKhau')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">SĐT</label>
                <input name="SDT" class="form-control @error('SDT') is-invalid @enderror" value="{{ old('SDT', $supplier->SDT) }}">
                @error('SDT')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="DiaChi" class="form-control @error('DiaChi') is-invalid @enderror">{{ old('DiaChi', $supplier->DiaChi) }}</textarea>
                @error('DiaChi')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mã tuyến lộ</label>
                <input name="MaTL" class="form-control @error('MaTL') is-invalid @enderror" value="{{ old('MaTL', $supplier->MaTL) }}">
                @error('MaTL')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="TrangThai" class="form-select @error('TrangThai') is-invalid @enderror">
                    <option value="1" {{ old('TrangThai', $supplier->TrangThai) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('TrangThai', $supplier->TrangThai) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                </select>
                @error('TrangThai')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật nhà cung cấp</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
