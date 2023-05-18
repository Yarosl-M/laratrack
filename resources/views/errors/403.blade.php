@php
    $sheets = ['style_error'];
    $title = '403 Forbidden';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="center-container">
        <h1 class="error-header">403</h1>
        <p class="error-caption">Отказано в доступе</p>
        <p>У Вас нет прав на просмотр данного ресурса. Если Вы считаете, что это произошло по ошибке, обратитесь к администратору системы.</p>
        <a href="/" class="go-back-link no-underline hover-underline">На главную страницу</a>    
    </div>
</x-layout>