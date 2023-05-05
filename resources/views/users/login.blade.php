{{-- @php
    $sheets = ['style_form'];
@endphp --}}
<x-layout :stylesheets="$sheets" :title="$title">
    <h1>Вход в систему</h1>
    <form class="bordered" action="/users/authenticate" method="POST">
        @csrf
        @error('auth')
        <p class="error">{{$message}}</p>
        @enderror
        <label for="email">E-mail</label>
        <input type="text" value="{{old('email')}}" name="email"/>
        <label for="password">Пароль</label>
        <input type="password" name="password"/>
        <div>
            <button type="submit">Войти</button>
            <a href="/register">Нет аккаунта?</a>
        </div>
    </form>
</x-layout>