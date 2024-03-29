<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            @isset($title)
                {{$title . ' | '}}
            @endisset
            Тикет-система LaraTrack
        </title>
        <link rel="stylesheet" href="{{asset("css/style.css")}}">
        <link rel="stylesheet" href="{{asset("css/style_layout.css")}}">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>        @isset($stylesheets)
            @foreach ($stylesheets as $sheet)
                <link rel="stylesheet" href="{{asset("css/" . $sheet . ".css")}}">
            @endforeach
        @endisset
    </head>
    <body>
        <header class="head">
            <div class="logo">
                <a href="/">
                    <span style="font-size:48px;font-weight:bold;">LaraTrack
                    </span>
                </a>
            </div>
            <nav class="navigation">
                {{-- <a href="/tickets">Тикеты</a>
                <a href="/tickets/archive">Архив</a>
                <a href="/tickets" class="nav-highlight">Мои тикеты</a>
                <a href="/tickets">Панель управления</a> --}}
                {{-- @auth --}}
                    <div> <!-- LOGO AND NAVIGATION -->
                        @auth
                            @switch(Auth::user()->type)
                            @case('client')
                                <x-nav-client/>
                                @break
                            @case('operator')
                                <x-nav-op/>
                                @break
                            @case('admin')
                                <x-nav-admin/>
                                @break
                            @default
                                @break
                            @endswitch
                        @endauth
                        @guest
                            {{-- <x-nav-guest/> --}}
                        @endguest
                    </div> <!-- LOGO AND NAVIGATION -->
                {{-- @endauth --}}
            </nav>
            <nav class="user">
                @auth
                        {{-- <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png"> --}}
                        <x-user-pfp :user="auth()->user()" :size="2.5"/>
                        <p style="display:inline-block;">Добро пожаловать, {{Auth::user()->displayName()}}</p>
                        <a href="/account">
                            <img style="width:2rem;height:2rem;" src="/assets/settings.svg" alt="Настройки аккаунта">
                        </a>
                        {{-- maybe do it with a form instead and post request? --}}
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit">
                                <img style="width:2rem;height:2rem;" src="/assets/logout.svg" alt="Выйти">
                            </button>
                        </form>
                @endauth
                @guest
                    <i style="display:inline-block;font-size:0.9rem;">Вы не вошли в систему</i>
                    <a class="navlink" href="/login">Войти</a>
                    <a class="nav-highlight" href="/register">Зарегистрироваться</a>
                @endguest
            </nav>
        </header>
        <div class="container">
            {{$slot}}
        </div>
    </body>
</html>