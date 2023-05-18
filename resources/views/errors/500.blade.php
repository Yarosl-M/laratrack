@php
    $sheets = ['style_error'];
    $title = '500 Internal Server Error';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="center-container">
        <h1 class="error-header">500</h1>
        <p class="error-caption">Ошибка сервера</p>
        <p>Что-то пошло не так. Пожалуйста, попробуйте повторить операцию позже.</p>
        <a href="/" class="go-back-link no-underline hover-underline">На главную страницу</a>    
    </div>
</x-layout>