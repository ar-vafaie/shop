<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link rel="stylesheet" href={{ asset("css/bootstrap.min.css") }}>
  <link rel="stylesheet" href={{  asset("css/style.css")  }}>
  @stack('styles')
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header class="header">
    <div class="container header__inner">
      <a href="index.html" class="logo">🛒 ModernShop</a>
      

      <nav class="nav" id="nav">
        <ul class="nav__list">
          <li class="nav__item"><a href={{ route('home') }} class="nav__link" id='nav-link__home'>Home</a></li>
          <li class="nav__item"><a href={{ route('categories') }} class="nav__link" id='nav-link__categories'>Categories</a></li>
          <li class="nav__item"><a href={{ route('cart') }} class="nav__link" id='nav-link__cart'>Cart</a></li>
        </ul>
      </nav>

      <div class="header__actions">
        @auth
          <button class="btn btn-danger" aria-label="{{ route('logout') }}">
            <a href="{{ route('logout') }}">LogOut</a>
          </button>
        @endauth 
        <button class="theme-btn" id="themeToggle" aria-label="Toggle theme">
          <span class="theme-icon">🌙</span>
        </button>
        <button class="icon-btn" aria-label="Cart" id="cartIcon">
          🛍️ <span class="badge" id="cartBadge">0</span><a href={{ route('cart') }}></a>
        </button>
        @auth
          <button type="button" class="btn btn--primary">Account</button>
        @else
          <button type="button" class="btn btn--primary"><a href="{{ route('login') }}">Login-Register</a></button>
        @endauth
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span class="hamburger__line"></span>
          <span class="hamburger__line"></span>
          <span class="hamburger__line"></span>
        </button>
      </div>
    </div>
  </header>
