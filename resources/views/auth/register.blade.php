<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar — SI Toko Bu Untung</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root { --accent:#f0592b; --black:#0f0f0f; --muted:#7a7a7a; --bg:#fff; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;color:var(--black);background:var(--bg)}
    .bar,.bar-bottom{width:100vw;height:12px;background:#000;margin:0}
    .stage{min-height:calc(100vh - 24px);display:grid;grid-template-columns:1.1fr 1fr;gap:48px;align-items:center;width:100%;padding:clamp(16px,4vw,32px)}
    .left{padding-left:clamp(8px,6vw,64px)}
    .left h1{font-weight:800;font-size:clamp(40px,6.5vw,64px);line-height:.95;margin:0 0 16px}
    .left p{color:var(--muted);max-width:640px;margin:0}
    .card{background:#fff;border-radius:22px;box-shadow:0 24px 60px rgba(0,0,0,.08);padding:28px;max-width:560px;margin:0 auto}
    .card h1{margin:0 0 18px;font-weight:800}
    label{font-size:12px;font-weight:600;color:#444;display:block;margin-bottom:6px}
    .ctrl{width:100%;height:44px;border:0;border-radius:8px;background:#efefef;padding:10px 12px}
    textarea.ctrl{height:auto;min-height:88px;resize:vertical;padding:12px}
    .row{margin-bottom:14px}
    .input-group{display:flex;gap:8px;align-items:stretch}
    .icon-btn{width:44px;height:44px;border:0;border-radius:8px;background:#efefef;display:grid;place-items:center;cursor:pointer}
    .btn-accent{width:100%;height:44px;border:none;border-radius:8px;color:#fff;background:var(--accent);font-weight:600;letter-spacing:.2px}
    .btn-accent:hover{filter:brightness(.95)}
    .foot{text-align:center;font-size:13px;margin-top:12px}
    .spark,.spark-sm{position:absolute;pointer-events:none}
    .spark{right:4vw;top:8vh;width:80px}
    .spark-sm{left:46vw;top:46vh;width:65px}
    .error-text{color:#c0392b;font-size:12px;margin-top:6px}
    @media (max-width:992px){.stage{grid-template-columns:1fr;gap:24px}.left{padding-left:0}.spark,.spark-sm{display:none}}
  </style>
</head>
<body>
  <div class="bar"></div>

  <div class="stage position-relative">
    @if (file_exists(public_path('assets/Vector.svg')))
      <img class="spark" src="{{ asset('assets/Vector.svg') }}" alt="">
      <img class="spark-sm" src="{{ asset('assets/Vector.svg') }}" alt="">
    @endif

    <div class="left">
      <h1>DEKAT<br>HEMAT<br>BERSAHABAT</h1>
      <p>Jl. Gatotkaca, Karangduwur, Jatimulyo, Kec. Alian, Kabupaten Kebumen, Jawa Tengah 54352</p>
    </div>

    <div class="right">
      <div class="card">
        <h1>Daftar</h1>

        @if(session('success'))
          <div style="background:#e9f7ef;padding:10px 12px;border-radius:8px;margin-bottom:10px">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div style="background:#fdeaea;padding:10px 12px;border-radius:8px;margin-bottom:10px">
            <ul style="margin:0;padding-left:18px">
              @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('pelanggan.register.store') }}" novalidate>
          @csrf

          <div class="row">
            <label for="nama_pelanggan">Nama Lengkap</label>
            <input id="nama_pelanggan" class="ctrl @error('nama_pelanggan') is-invalid @enderror"
                   type="text" name="nama_pelanggan" placeholder="Masukan Nama Anda..."
                   value="{{ old('nama_pelanggan') }}" required autocomplete="name">
            @error('nama_pelanggan') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="row">
            <label for="no_hp">No Hp (WA)</label>
            <input id="no_hp" class="ctrl @error('no_hp') is-invalid @enderror"
                   type="tel" name="no_hp" placeholder="08xxxxxxxxxx"
                   value="{{ old('no_hp') }}" required autocomplete="tel">
            @error('no_hp') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="row">
            <label for="username">Username</label>
            <input id="username" class="ctrl @error('username') is-invalid @enderror"
                   type="text" name="username" placeholder="username_anda"
                   value="{{ old('username') }}" required autocomplete="username">
            @error('username') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="row">
            <label for="email">Email</label>
            <input id="email" class="ctrl @error('email') is-invalid @enderror"
                   type="email" name="email" placeholder="emailanda@gmail.com"
                   value="{{ old('email') }}" required autocomplete="email">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="row">
            <label for="pwd">Password</label>
            <div class="input-group">
              <input id="pwd" class="ctrl @error('password') is-invalid @enderror"
                     type="password" name="password" placeholder="Password" required autocomplete="new-password" minlength="6">
              <button type="button" id="togglePwd" class="icon-btn" aria-label="Tampilkan sandi" aria-pressed="false">
                <img id="eyeIcon" src="{{ asset('assets/eye-crossed.svg') }}" alt="sembunyikan/lihat sandi" width="18" height="18">
              </button>
            </div>
            @error('password') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="row">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" class="ctrl @error('alamat') is-invalid @enderror"
                      name="alamat" placeholder="Masukan Alamat Lengkap Anda ..."
                      autocomplete="street-address">{{ old('alamat') }}</textarea>
            @error('alamat') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <button type="submit" class="btn-accent">Buat Akun</button>
        </form>

        <div class="foot">
          Sudah punya akun ? <a href="{{ url('/login') }}">Masuk</a>
        </div>
      </div>
    </div>
  </div>

  <div class="bar bar-bottom"></div>

  <script>
    const pwd  = document.getElementById('pwd');
    const btn  = document.getElementById('togglePwd');
    const icon = document.getElementById('eyeIcon');
    const ICON_SHOW = "{{ asset('assets/eye.svg') }}";
    const ICON_HIDE = "{{ asset('assets/eye-crossed.svg') }}";
    let visible = false;
    function applyState(){
      pwd.type = visible ? 'text' : 'password';
      icon.src = visible ? ICON_SHOW : ICON_HIDE;
      btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
      btn.setAttribute('aria-label', visible ? 'Sembunyikan sandi' : 'Tampilkan sandi');
    }
    btn?.addEventListener('click', ()=>{ visible=!visible; applyState(); });
    applyState();
  </script>
</body>
</html>
