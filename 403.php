<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ffdde1, #ee9ca7);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            width: 380px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .2);
        }

        h1 {
            font-size: 48px;
            margin: 0;
            color: #e74c3c;
        }

        p {
            color: #555;
            margin: 15px 0 30px;
        }

        a {
            display: inline-block;
            padding: 12px 24px;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: .3s;
        }

        a:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>403</h1>
        <p>Akses ditolak.<br>Silakan login terlebih dahulu.</p>
        <a href="/page/login.php">Ke Halaman Login</a>
    </div>
</body>

</html>