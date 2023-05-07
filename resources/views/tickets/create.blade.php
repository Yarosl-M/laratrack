<!-- Create a new ticket -->
{{-- @php
    $sheets = ['test','test2','style_ticket_create'];
    $title = 'Создать тикет';
@endphp --}}
<x-layout :stylesheets="$sheets" :title="$title">
    <template id="filetemplate">
        <input type="file" name="attachments[]"/>
    </template>
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
            <input type="file" name="attachments[]" id="files" multiple/>
            <button type="submit">Создать</button>
    </form>

    <script>
        const fileLimit = 5;
        var fileAmount = 1;
        $(document).ready(function() {
            // no wait this is all bs (maybe?) 
            // why do we even need to iterate each time a new file is added if we only need to 
            // add a new one each time we upload a file
            $('#files').on('change', function() {

                // let files = $(this).get(0)>.files;
                // if (files.length > 0) {
                //     for (let i = 0; i < files.length; i++) {
                //         let input = $($.parseHTML($('#filetemplate').html()));
                //         input.attr('id', 'file' + String(4));
                //         $('#files')
                //     }
                // }
            })
        });
    </script>
</x-layout>