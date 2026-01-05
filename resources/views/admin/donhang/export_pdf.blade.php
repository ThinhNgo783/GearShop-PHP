<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đơn hàng {{ $donhang->ma_don }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Đơn hàng: {{ $donhang->ma_don }}</h2>
        <p>Ngày đặt: {{ $donhang->created_at->format('d/m/Y H:i:s') }}</p>
    </div>
    <div>
        <p><strong>Khách hàng:</strong> {{ $donhang->nguoidung->name }}</p>
        <p><strong>Điện thoại:</strong> {{ $donhang->dienthoaigiaohang }}</p>
        <p><strong>Địa chỉ:</strong> {{ $donhang->diachigiaohang }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ $donhang->phuongthucthanhtoan->tenphuongthucthanhtoan ?? 'N/A' }}</p>
    </div>
    <hr>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $tongtien = 0; @endphp
            @foreach($donhang->donhang_chitiet as $index => $chitiet)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $chitiet->sanpham->tensanpham ?? 'N/A' }}</td>
                <td>{{ $chitiet->soluongban }}</td>
                <td>{{ number_format($chitiet->dongiaban) }}</td>
                <td>{{ number_format($chitiet->soluongban * $chitiet->dongiaban) }}</td>
            </tr>
            @php $tongtien += $chitiet->soluongban * $chitiet->dongiaban; @endphp
            @endforeach
            <tr>
                <td colspan="4">Tổng tiền sản phẩm:</td>
                <td class="text-end"><strong>{{ number_format($tongtien) }}</strong><sup><u>đ</u></sup></td>
            </tr>
            <tr>
                <td colspan="4">Phí vận chuyển:</td>
                <td class="text-end"><strong>{{ number_format($donhang->phivanchuyen ?? 0) }}</strong><sup><u>đ</u></sup></td>
            </tr>
            <tr>
                <td colspan="4">Tổng thanh toán:</td>
                <td class="text-end"><strong>{{ number_format($tongtien + ($donhang->phivanchuyen ?? 0)) }}</strong><sup><u>đ</u></sup></td>
            </tr>
        </tbody>
    </table>
</body>

</html>