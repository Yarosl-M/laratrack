@props(['ticket'])
<form method="POST" action="/tickets/{{$ticket->id}}/messages" class="msg-form">
    <label for="message-text">Ответить</label>
    <textarea name="message-text">{{old('message-text')}}</textarea>
    <label for="files">Прикрепить файлы</label>
    <input type="file"/>
    <button>Отправить</button>
    <script>
    </script>
</form>