@props(['sheets', 'title'])
<x-layout :stylesheets="$sheets" :title="$title">
    <header class="hero">
        <h1>Тикет-система LaraTrack</h1>
        <h2>Автоматизация работы службы технической поддержки</h2>
    </header>
    <div class="bordered stuff-wrapper">
        @guest
            <div class="bordered stuff-element">
                <h3>Вы клиент?</h3>
                    <a class='no-underline hover-underline' href="/login">
                        Войдите в систему
                    </a> или <a class='no-underline hover-underline' href="/register">
                        зарегистрируйтесь</a>, чтобы оставить тикет
            </div>
            <div class="bordered stuff-element">
                <h3>Вы сотрудник?</h3>
                    <a href="/login" class="no-underline hover-underline">
                        Войдите в систему</a>, чтобы начать работу
            </div>
        @endguest
        @auth
            <div class="bordered stuff-element">
                @if (Auth::user()->type == 'client')
                    <h3>Оставить тикет</h3>
                    <a href="/tickets/create" class="no-underline hover-underline">
                        Создайте тикет</a> для техподдержки
                @else
                    <h3>Приступить к работе</h3>
                    <a href="/tickets" class="no-underline hover-underline">Просмотреть список тикетов</a>
                @endif
            </div>
        @endauth
    </div>
</x-layout>