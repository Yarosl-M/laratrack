{{-- @php
    $sheets = ['style_form'];
@endphp --}}
@php
    use App\Models\Ticket;
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <h1>Регистрация</h1>
    <form class="bordered" action="/users" method="POST">
        {{-- also don't forget error messages --}}
        @csrf
        <label for="email" class="required">E-mail</label>
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="email" value="{{old('email')}}"/>
        <label for="username" class="required">Имя пользователя (логин)</label>
        @error('username')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="username" value="{{old('username')}}"/>
        <label for="name">Ваше имя (будет отображаться другим в системе)</label>
        @error('name')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="name" value="{{old('name')}}"/>
        <label for="password" class="required">Пароль</label>
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="password" name="password"/>
        <label for="password_confirmation" class="required">Повторите пароль</label>
        @error('password_confirmation')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="password" name="password_confirmation"/>
        <div>
            <button type="submit">Зарегистрироваться</button>
            <a href="/login">Уже есть аккаунт?</a>
        </div>
    </form>
</x-layout>