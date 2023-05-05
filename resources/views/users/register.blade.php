@php
    $sheets = ['style_form'];
@endphp
<x-layout :stylesheets="$sheets">
    <h1>Регистрация</h1>
    <form class="bordered" action="/users" method="POST">
        {{-- also don't forget error messages --}}
        @csrf
        <label for="email" class="required">E-mail</label>
        <input type="text" name="email"/>
        <label for="username" class="required">Имя пользователя (логин)</label>
        <input type="text" name="username"/>
        <label for="name">Ваше имя (будет отображаться другим в системе)</label>
        <input type="text" name="name"/>
        <label for="password" class="required">Пароль</label>
        <input type="password" name="password"/>
        <label for="password_confirmation" class="required">Повторите пароль</label>
        <input type="password" name="password_confirmation"/>
        <div>
            <button type="submit">Зарегистрироваться</button>
            <a href="/login">Уже есть аккаунт?</a>
        </div>
    </form>
</x-layout>