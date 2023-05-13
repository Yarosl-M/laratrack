@props(['ticket'])
{{-- {{dd($errors)}} --}}
<template id="filetemplate">
    <input type="file" name="files[]" accept="image/*,text/plain">
</template>
<form method="POST" enctype="multipart/form-data" action="/tickets/{{$ticket->id}}/comment" id="msg-form" class="msg-form">
    @csrf
    <label for="content">Ответить</label>
    <textarea name="content" required>{{old('content')}}</textarea>
    <label for="files">Прикрепить файлы</label>
    <input type="file" name="files[]" accept="image/*,text/plain">
    <button id="submit-btn">Отправить</button>
    <script>
        const fileLimit = 5;
        // ain't this about the file input amount rather than actual uploaded files?
        var fileAmount = 1;
        var fileHtml = $('#filetemplate').html().trim();
        var csrf = undefined;
        function addFileInput() {
            if (fileAmount < fileLimit) {
                var input = $.parseHTML(fileHtml);
                $('input[type="file"]').last().after(input);
                $('input[type="file"]').last().one('change', addFileInput);
                fileAmount++;
            }
        }
        $(document).ready(function() {
            var csrf = $('meta[name="csrf-token"]').attr('content');
            $('input[name="files[]"]').last().one("change", addFileInput);
            return; // !!!!!!!!!!!!!!!
            $('#msg-form').on('submit', function(event) {

                $('#submit-btn').prop('disabled', true);

                var formData = new FormData();
                var message = $('[name="content"]').val();
                formData.append('content', message);
                var attachments = [];
                var fileInputs = $('[name="files[]"]');
                // also if we have 5 files then we will need to iterate over the last one too
                // thi is the best line of code in this entire repository btw
                for (var i = 0; i < fileInputs.length - (fileInputs.length < fileLimit); i++) {
                    var file = fileInputs[i].files[0];
                    f.append('files', file);
                }

                // 
                $.ajax({
                    url: '{{url("/api/tickets/".$ticket->id."/comment")}}',
                    contentType: false,
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': csrf, 'Referer': 'localhost' },
                    xhrFields: { withCredentials: true },
                    dataType: 'json',
                    processData: false,
                    method: 'POST',
                    success: function(data) {
                        $('textarea[name="content"]').val('');
                        console.log(data);
                        var html = data.html;
                        $('.ticket-items').append(html);
                    },
                    error: function(xhr, status, exception) {
                        console.log(xhr);
                        console.log(status);
                        console.log(exception);
                    }
                });

                $('#submit-btn').prop('disabled', false);
                event.preventDefault();
            });
        });
    </script>
</form>