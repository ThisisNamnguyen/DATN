<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email đặt lại mật khẩu</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    <p>Chào, {{ $formData['user']->name }}</p>

    <h1>Bạn có yêu cầu đặt lại mật khẩu:</h1>

    <p>Vui lòng ấn vào link bên dưới để chuyển đến trang đặt lại mật khẩu</p>

    <a href="{{ route('frontend.resetPassword', $formData['token']) }}">Bấm tại đây</a>

    <p>Cảm ơn</p>
</body>

</html>
