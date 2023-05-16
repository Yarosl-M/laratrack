@props(['user'])
@php
    $sheets = ['style_form'];
    $title = 'Подтвердить e-mail';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <h1>Подтверждение e-mail</h1>
    <form class="bordered" method="POST" action="/email/verification-notification">
        @csrf
        <p>
            На Ваш адрес электронной почты <b>{{$user->email}}</b> было выслано сообщение. Перейдите по ссылке в этом сообщении для того, чтобы подтвердить свой адрес электронной почты и начать пользование сервисом.
        </p>
        <button>Отправить сообщение ещё раз</button>
    </form>
    @if (session()->has('message'))
    <script>
        $(document).ready(function() {
            alert('{{session("message")}}');
        });
    </script>
    @endif
</x-layout>