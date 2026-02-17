<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial; background:#111; color:#fff; padding:30px;">

    <h2 style="color:#ef4444;">Booking Dibatalkan</h2>

    <p>Halo {{ $order->user->name }},</p>

    <p>
        Booking dengan kode:
        <strong>{{ $order->booking_code }}</strong>
        telah dibatalkan karena melewati batas waktu pembayaran.
    </p>

    <p>
        Anda dapat melakukan pemesanan ulang melalui website kami.
    </p>

    <hr style="margin:20px 0; border-color:#333;">

    <small style="color:#888;">
        © {{ date('Y') }} BioskopApp
    </small>

</body>
</html>