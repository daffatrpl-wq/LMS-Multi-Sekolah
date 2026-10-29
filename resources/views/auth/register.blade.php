<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>LMS – Register</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/register.css'])
<style>
.stage.reverse{grid-template-columns:1fr 1.5fr}
.reverse .logo{left:40%}
.reverse .panel{border-radius:28px 0 0 28px}
.reverse .panel h2{font-size:2.2rem}
.reverse .card{border-radius:28px;padding:64px 56px 52px}
.reverse form{max-width:440px}
.reverse .hint{margin-bottom:18px}
.reverse .c1{width:116px;height:116px;left:174px;top:-14px;right:auto}
.reverse .c2{width:58px;height:58px;left:138px;top:-27px;z-index:4}
.reverse .c3{width:171px;height:171px;left:271px;bottom:-33px}
.reverse .c4{width:120px;height:120px;left:190px;bottom:-62px}
.reverse .c5{width:65px;height:65px;left:135px;bottom:-38px}
@media (max-width:760px){
  .stage.reverse{grid-template-columns:1fr}
  .reverse .logo{left:50%}
  .reverse .card{order:1;border-radius:28px 28px 0 0;padding:76px 24px 36px}
  .reverse .panel{order:2;border-radius:0 0 28px 28px}
}
</style>
</head>
<body>
<main class="stage reverse">
  <div class="logo" aria-label="LMS Logo">
    <svg viewBox="0 0 64 52" fill="none" stroke="#fff" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" aria-hidden="true">
      <path d="M32 4 6 15l26 11 26-11L32 4Z" fill="#fff"/>
      <path d="M16 22v8c0 4 7 7 16 7s16-3 16-7v-8"/>
      <path d="M32 46c-7-5-11-8-11-12a6 6 0 0 1 11-3 6 6 0 0 1 11 3c0 4-4 7-11 12Z" fill="#7aa5de"/>
    </svg>
    <b>LMS</b><small>Learning Management System</small>
  </div>

  <aside class="panel">
    <span class="circle c1"></span><span class="circle c2"></span>
    <span class="circle c3"></span><span class="circle c4"></span><span class="circle c5"></span>
    <h2>Welcome Back!</h2>
    <p>To keep connected with us please login with your personal info</p>
    <a href="{{ route('login.store') }}" class="btn register">Sign In</a>
  </aside>

  <section class="card">
    <h1>Create Account</h1>
    <div class="social">
      <button type="button" aria-label="Login dengan Facebook"><svg viewBox="0 0 34 34" fill="none" stroke="currentColor" stroke-width="2"><circle cx="17" cy="17" r="14"/><path d="M18.5 28V18h3.2l.5-3.6h-3.7v-2.2c0-1 .4-1.7 1.8-1.7h2V7.4c-.4 0-1.500-.2-2.800-.2-2.800 0-4.600 1.700-4.600 4.800v2.400h-3.200V18h3.200v10" fill="currentColor" stroke="none"/></svg></button>
      <button type="button" aria-label="Login dengan Google"><svg viewBox="0 0 34 34" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"><path d="M26 11a10 10 0 1 0 2 8h-10"/></svg></button>
      <button type="button" aria-label="Login dengan Threads"><svg viewBox="0 0 34 34" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="17" cy="17" r="14"/><path d="M22 14c-.6-2.500-2.400-3.800-5-3.800-3.200 0-5 2.200-5 6.800s1.800 6.800 5 6.800c2.600 0 4.400-1.200 4.400-3.300 0-2.300-2-3.200-4.400-3.200-1.800 0-3 .8-3 2s1 1.800 2.200 1.800c2 0 3.200-1.500 3.200-4.300"/></svg></button>
    </div>
    <p class="hint">or use your email for registration:</p>

    <form id="registerForm" method="POST" action="{{ route('register.store') }}" novalidate>
      @csrf
      <div class="row">
        <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4.500"/><path d="M3.500 21c0-4.700 3.800-7.500 8.500-7.500s8.500 2.800 8.500 7.500v.5h-17V21Z"/></svg></span>
        <div class="field"><input id="name" name="name" type="text" placeholder="Nama" value="{{ old('name') }}" autocomplete="name" aria-label="Nama" required></div>
      </div>
      <div class="row">
        <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 5h20v14H2z" opacity=".0"/><path d="M3 6h18a1 1 0 0 1 1 1v.4l-10 6.100L2 7.400V7a1 1 0 0 1 1-1Zm-1 3.700 10 6.100 10-6.100V18a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V9.700Z"/></svg></span>
        <div class="field"><input id="email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email" aria-label="Email" required></div>
      </div>
      <div class="row">
        <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 10V7a5 5 0 0 1 10 0v3h1a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1h1Zm2 0h6V7a3 3 0 0 0-6 0v3Zm3 4a1.500 1.500 0 0 0-.8 2.800V19h1.600v-2.200A1.500 1.500 0 0 0 12 14Z"/></svg></span>
        <div class="field">
          <input id="password" name="password" type="password" placeholder="Password" autocomplete="new-password" aria-label="Password" required>
          <button type="button" class="eye" id="toggle" aria-label="Tampilkan password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button>
        </div>
      </div>
      <button type="submit" class="btn signin">Register</button>
      <p class="msg" id="msg" role="status" aria-live="polite">{{ $errors->first() }}</p>
    </form>
  </section>
</main>

<script>
    const password = document.getElementById('password');
    const toggle = document.getElementById('toggle');

    toggle.addEventListener('click', function () {
        if (password.type === 'password') {
            password.type = 'text';
            toggle.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            password.type = 'password';
            toggle.setAttribute('aria-label', 'Tampilkan password');
        }
    });
</script>

</body>
</html>