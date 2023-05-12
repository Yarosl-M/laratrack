@props(['ticket'])
<template id="filetemplate">
    <input type="file" name="attachments[]" accept="image/*,text/plain">
</template>
<form method="POST" action="/api/tickets/{{$ticket->id}}/comment" id="msg-form" class="msg-form">
    @csrf
    <label for="message-text">Ответить</label>
    <textarea name="message-text" required>{{old('message-text')}}</textarea>
    <label for="files">Прикрепить файлы</label>
    <input type="file" name="attachments[]" accept="image/*,text/plain">
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
            $('input[name="attachments[]"]').last().one("change", addFileInput);
            $('#msg-form').on('submit', function(event) {
                console.log('hi');

                $('#submit-btn').prop('disabled', true);

                var formData = new FormData();
                var message = $('[name="message-text"]').val();
                formData.append('content', message);
                var attachments = [];
                var fileInputs = $('[name="attachments[]"]');
                // also if we have 5 files then we will need to iterate over the last one too
                for (var i = 0; i < fileInputs.length - (fileInputs.length < fileLimit); i++) {
                    var file = fileInputs[i].files[0];
                    f.append('attachments', file);
                }

                // 
                $.ajax({
                    url: '{{url("/api/tickets/".{{$ticket->id}}."/comment")}}',
                    contentType: false,
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': csrf },
                    xhrFields: { withCredentials: true },
                    dataType: 'json',
                    processData: false,
                    method: 'POST',
                    success: function(data) {
                        $('textarea[name="message-text"]').val('');
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