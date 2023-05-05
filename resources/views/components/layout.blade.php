<!DOCTYPE html>
<html>
    <head>
        <title>
            @isset($title)
                {{$title . ' | '}}
            @endisset
            Тикет-система LaraTrack
        </title>
        <link rel="stylesheet" href="{{asset("css/style.css")}}">
        <link rel="stylesheet" href="{{asset("css/style_layout.css")}}">
        @isset($stylesheets)
            @foreach ($stylesheets as $sheet)
                <link rel="stylesheet" href="{{asset("css/" . $sheet . ".css")}}">
            @endforeach
        @endisset
    </head>
    <body>
        <header class="head">
            <div class="logo">
                <a href="/">
                    {{-- logo goes here... also don't forget to use stuff like {{asset}} and whatever --}}
                    <span style="font-size:48px;font-weight:bold;">LOREM
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
                        {{-- @switch(Auth::user()->class)
                            @case('client')
                            <a href="/tickets/create" class="nav-highlight">Создать тикет</a>
                            <a href="/mytickets">Мои тикеты</a>
                                @break
                            @case('operator') --}}
                            {{-- TOODO: sort out the permission stuff --}}
                            <a href="/tickets">Тикеты</a>
                            <a href="/tickets/archive">Архив</a>
                            {{-- TODO: архивные тикеты (на той же странице?) --}}
                            <a href="/tickets" class="nav-highlight">Мои тикеты</a>
                                {{-- @break
                            @case('admin')
                            <a href="/tickets">Тикеты</a>
                            <a href="/tickets/archive">Архив</a>
                            <a href="/tickets" class="nav-highlight">Мои тикеты</a>
                            <a href="/tickets">Панель управления</a>
                                @break
                            @default    
                        @endswitch --}}
                    </div> <!-- LOGO AND NAVIGATION -->
                {{-- @endauth --}}
            </nav>
            {{-- also user stuff like login button and whatever --}}
            <div class="test">Lorem ipsum dolor sit amet (user stuff)</div>
        </header>
        {{-- something else... --}}
        {{$slot}}
        {{-- something else too... --}}
    </body>
</html>