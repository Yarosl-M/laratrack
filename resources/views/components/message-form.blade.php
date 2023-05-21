@props(['ticket'])
<template id="filetemplate">
    <input type="file" name="files[]" accept="image/*,text/plain">
</template>
<form method="POST" enctype="multipart/form-data" action="/tickets/{{$ticket->id}}/comment" id="msg-form" class="msg-form">
    @csrf
    <label for="content">Ответить</label>
    <p class="error" id="textarea-error" style="display:none">test</p>
    <textarea name="content" required>{{old('content')}}</textarea>
    <label for="files">
        <abbr title="До 5 файлов до 5 МБ каждый. Разрешённые форматы файлов: JPG, JPEG, PNG, GIF, BMP, DOC, DOCX, TXT, LOG, PDF, RTF">
            При необходимости прикрепите файлы
        </abbr>
    </label>
    <p class="error" id="files-error" style="display:none;"></p>
    <input type="file" name="files[]" accept="image/*,text/plain">
    <button id="submit-btn">Отправить</button>
    <script>
        var myxhr = null;
        const fileLimit = 5;
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
            $('#msg-form').on('submit', function(event) {
                event.preventDefault();

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
                    formData.append('files[]', file);
                }

                $.ajax({
                    url: '{{url("/api/tickets/".$ticket->id."/comment")}}',
                    data: formData,
                    contentType: false,
                    dataType: 'json',
                    processData: false,
                    method: 'POST',
                    success: function(data) {
                        hideError('textarea-error');
                        hideError('files-error');
                        $('#msg-form')[0].reset();
                        $('input[type="file"]').not(':first').remove();
                        $('input[type="file"]').last().one('change', addFileInput);

                        var html = data.html;
                        $('.ticket-items').append(html);
                    },
                    error: function(xhr, status, exception) {
                        switch (xhr.status) {
                            case 422:
                                let errors = xhr.responseJSON.errors;
                                if (errors.hasOwnProperty('content')) {
                                    displayError(errors.content[0], 'textarea-error')
                                }
                                for (let prop in errors) {
                                    if (/file*/.test(prop)) {
                                        displayError(errors[prop][0], 'files-error');
                                        break;
                                    }
                                }
                                break;
                            default:
                                displayError('Произошла неизвестная ошибка. Пожалуйста, повторите попытку позже.', 'textarea-error');
                                break;
                        }
                    },
                }).always(function() {
                    setTimeout(() => { $('#submit-btn').prop('disabled', false); }, 500);                    
                });
            });
        });

        function displayError(msg, id) {
            $('#' + id).text(msg);
            $('#' + id).css('display', '');
        }
        function hideError(id) {
            $('#' + id).css('display', 'none');
        }
    </script>
</form>