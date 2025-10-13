<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif;">

    <header style="background-color: #f0592b; padding: 20px; color: white;">
        <h1>Welcome to the Dashboard</h1>
    </header>

    <main style="padding: 20px;">
        <h2>Hello, {{ session('staff_username') }}!</h2> <!-- Tampilkan nama pengguna yang login -->
        <p>Welcome to your dashboard.</p>

        <div>
            <p>Role: {{ session('staff_role') }}</p> <!-- Menampilkan role user -->
        </div>

        <!-- Kamu bisa menambahkan konten lebih lanjut di sini -->
    </main>

    <footer style="background-color: #f0592b; padding: 10px; color: white; text-align: center;">
        <p>© 2025 Toko Bu Untung</p>
    </footer>

</body>
</html>
