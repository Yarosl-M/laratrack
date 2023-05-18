@php
    $sheets = ['style_error'];
    $title = '419 Page Expired';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="center-container">
        <h1 class="error-header">419</h1>
        <p class="error-caption">Некорректный CSRF-токен</p>
        <p>Одноразовый ключ для сессии истёк или недействителен. Перезагрузите страницу с формой и повторите отправку ещё раз; если проблема не устранена, очистите кэш и cookie-файлы.</p>
        <a href="/" class="go-back-link no-underline hover-underline">На главную страницу</a>    
    </div>
</x-layout>