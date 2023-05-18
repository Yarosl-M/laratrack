@php
    $sheets = ['style_error'];
    $title = '404 Not Found';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="center-container">
        <h1 class="error-header">404</h1>
        <p class="error-caption">Не найдено</p>
        <p>Запрашиваемый Вами ресурс не найден. Проверьте, правильно ли указан адрес.</p>
        <a href="/" class="go-back-link no-underline hover-underline">На главную страницу</a>    
    </div>
</x-layout>