<!-- Create a new ticket -->
{{-- @php
    $sheets = ['test','test2','style_ticket_create'];
    $title = 'Создать тикет';
@endphp --}}
<x-layout :stylesheets="$sheets" :title="$title">
    <template id="filetemplate">
        <input type="file" name="attachments[]"
        accept="image/*,text/plain"/>
    </template>
    <header>
        <h1>Создать новый тикет</h1>
    </header>
    <form method="POST" class="bordered" action="/tickets" enctype="multipart/form-data">
        @csrf
            <label for="subject" class="required">Тема тикета</label>
            @error('subject')
                <p class="error">{{$message}}</p>
            @enderror
            <input type="text" name="subject" value="{{old('subject')}}"/>
            <label for="content" class="required">Содержание тикета</label>
            @error('content')
                <p class="error">{{$message}}</p>
            @enderror
            <textarea placeholder="Опишите подробно возникшую у Вас проблему…" name="content"
            >{{old('content')}}</textarea>
            <label for="attachments">
                <abbr title="До 5 файлов до 5 МБ каждый. Разрешённые форматы файлов: JPG, JPEG, PNG, GIF, BMP, DOC, DOCX, TXT, LOG, PDF, RTF">
                    При необходимости прикрепите файлы
                </abbr>
            </label>
            <input type="file" name="attachments[]"
            accept="image/*,text/plain"/>
            <button type="submit">Создать</button>
    </form>
    <script>
        const fileLimit = 5;
        var fileAmount = 1;
        var fileHtml = $('#filetemplate').html().trim();
        function addFileInput() {
            if (fileAmount < fileLimit) {
                var input = $.parseHTML(fileHtml);
                $('input[type="file"]').last().after(input);
                $('input[type="file"]').last().one('change', addFileInput);
                fileAmount++;
            }
        }
        $(document).ready(function() {
            $('input[name="attachments[]"]').last().one("change", addFileInput);
        });
    </script>
</x-layout>