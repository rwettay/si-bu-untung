<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <h2>Selamat Datang, {{ session('staff_username') }}</h2>
  <p>Role: {{ session('staff_role') }}</p>
  <a href="{{ route('logout') }}">Logout</a>
</body>
</html>
