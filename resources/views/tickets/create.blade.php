<!-- Create a new ticket -->
{{-- @php
    $sheets = ['test','test2','style_ticket_create'];
    $title = 'Создать тикет';
@endphp --}}
<x-layout :stylesheets="$sheets" :title="$title">
    <header>
        <h1>Создать новый тикет</h1>
    </header>
    <form method="POST" class="bordered" action="/tickets" enctype="multipart/form-data">
        @csrf
            <label for="subject" class="required">Тема тикета</label>
            <input type="text" name="subject" value="{{old('subject')}}"/>
            <label for="content" class="required">Содержание тикета</label>
            <textarea placeholder="Опишите подробно возникшую у Вас проблему…" name="content"
            >{{old('content')}}</textarea>
            <label for="attachments">При необходимости прикрепите файлы</label>
            <input type="file" name="attachment"/>
            <button type="submit">Создать</button>
    </form>
</x-layout>