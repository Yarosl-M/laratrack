@php
    $sheets = ['style_form'];
    $title = 'Сменить пароль'
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <form class="bordered" action="/users/change-password" method="POST">
        @csrf
        @error('pwd_change')
            <p class="error">{{$message}}</p>
        @enderror
        <label for="current_password">Текущий пароль</label>
        <input type="password" name="current_password"/>
        <label for="password">Новый пароль</label>
        <input type="password" name="password"/>
        <label for="password_confirmation">Повторите пароль</label>
        <input type="password" name="password_confirmation"/>
        <div>
            <button type="submit">Изменить пароль</button>
            <a href="/account">Назад</a>
        </div>
    </form>

</x-layout>