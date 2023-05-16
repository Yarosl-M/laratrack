@props(['user', 'tags', 'users', 'permissions'])
@php
    $sheets = ['style_sidebar', 'style_dashboard'];
    $title = 'Панель управления';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="main">
        <div id="users-tab">
            {{-- manage users' permissions, deactivate accounts and so on --}}
            <h1>Настройки учётных записей</h1>
            <section class="users-tab-main">
                <div class="bordered user-list-wrapper user-tab-item">
                    <h3>Пользователи системы</h3>
                    <div class="user-list">
                        @foreach ($users->except(['id', $user->id]) as $u)
                        <div class="user-card-wrapper" id="{{$u->id}}">
                            <div class="user-card">
                                <x-user-pfp :user="$u" size="2"/>
                                {{$u->displayName()}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <p id="users-prompt">Выберите пользователя из списка слева, чтобы настроить его права доступа…</p>
            </section>
        </div>
        <div id="tags-tab" style="display:none">
            {{-- just edit tags  --}}
        </div>
    </div>
    <div class="sidebar">
        <a href="" class="sidebar-link" id="users-tab-link">Управление пользователями</a>
        <a href="" class="sidebar-link" id="tags-tab-link">Управление тегами</a>
    </div>
    <script>
        var selectedId = '';
        function lockCard(id) {
            $('#' + id + ' *').css('font-weight', 'bold');
            $('#' + id).css('background-color', '#ddd'); 
        }
        function unlockCard(id) {
            $('#' + id + ' *').css('font-weight', '');
            $('#' + id).css('background-color', ''); 
        }
            // mode is either append or replace
            // bloody hell
            // we already have the ids and the card html
            // but we could put the event attaching somewhere else then
            // would be actually fun to have it at, like, the card itself but whatever
            // so, with that in mind, besides addCard(), I also present to you...
        function initCard(id) {
            $('#submit-user-settings').on('click', {
                id: id
            }, submitUserForm);
            $('#deactivate-link').on('click', {
                id: id
            }, showDeactivateDialog);
            $('#activate-link').on('click', {
                id: id
            }, showActivateDialog);
            $('#delete-user-link').on('click', {
                id: id
            }, showDeleteDialog);

            // dayum that actually looks neat
            $('#deactivate-form')[0].action = "/api/dashboard/users/" + id + '/deactivate';
            $('#activate-form')[0].action = "/api/dashboard/users/" + id + '/activate';
            $('#delete-form')[0].action = "/api/dashboard/users/" + id;

            $('#deactivate-submit').on('click', {
                id: id }, deactivateUser);
            $('#activate-submit').on('click', {
                id: id }, reactivateUser);
            $('#delete-submit').on('click', {
                id: id }, deleteUser);
        }
        function addCard(id, html, mode = 'append') {
            if (mode == 'append') {
                $('#users-tab .users-tab-main').first().append(html);
            }
            else if (mode == 'replace') {
                $('.user-dashboard-card').replaceWith(html);
            }
            initCard(id);
        }

        function openUserCard(id) {
            // hide the previous form if there was any as well
            if ($('.user-dashboard-card').length != 0)
                $('.user-dashboard-card').remove();
            $('#users-prompt').css('display', '');
            $('#users-prompt').text('Загрузка, пожалуйста, подождите…');

            $.ajax({
                url: '{{url("api/dashboard/users")}}/' + id,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    if (selectedId != id && selectedId != '')
                        unlockCard(selectedId);
                    lockCard(id);
                    selectedId = id;
                    $('#users-prompt').css('display', 'none');
                    var html = data.html;

                    $('#users-tab .users-tab-main').first().append(html);

                    $('#submit-user-settings').on('click', {
                        id: selectedId
                    }, submitUserForm);
                    $('#deactivate-link').on('click', {
                        id: selectedId
                    }, showDeactivateDialog);
                    $('#activate-link').on('click', {
                        id: selectedId
                    }, showActivateDialog);
                    $('#delete-user-link').on('click', {
                        id: selectedId
                    }, showDeleteDialog);

                    $('#deactivate-form')[0].action = "/api/dashboard/users/" + selectedId + '/deactivate';
                    $('#activate-form')[0].action = "/api/dashboard/users/" + selectedId + '/activate';
                    $('#delete-form')[0].action = "/api/dashboard/users/" + selectedId;

                    $('#deactivate-submit').on('click', {
                        id: selectedId }, deactivateUser);
                    $('#activate-submit').on('click', {
                        id: selectedId }, reactivateUser);
                    $('#delete-submit').on('click', {
                        id: selectedId }, deleteUser);
                }
            });
        }
        function submitUserForm(event) {
            event.preventDefault();
            var id = event.data.id;
            console.log(id);
            var form = new FormData();

            form.append('type', $('#card-' + id + ' option:selected').val());
            var checkboxes = $('#card-' + id + ' input[type="checkbox"]:checked');

            for (var i = 0; i < checkboxes.length; i++) {
                form.append(checkboxes.get(i).id, checkboxes.get(i).id);
            }

            $('#submit-user-settings').prop('disabled', true);
            $.ajax({
                url: '{{url("api/dashboard/users")}}/' + id,
                method: 'POST',
                data: form,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    var response = data;
                    alert(response.message);
                },
            }).always(function() {
                setTimeout(() => { $('#submit-user-settings').prop('disabled', false); }, 500);
            });
        }
        function deactivateUser(event) {
            event.preventDefault();
            var id = event.data.id;
            $('deactivate-dialog button').prop('disabled', true);
            $.ajax({
                url: '/api/dashboard/users/' + id + '/deactivate',
                method: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    var response = data.message;
                    // it would just return the updated card in the response
                    var html = data.html;
                    alert(response);
                    $('.user-dashboard-card').replaceWith(html);
                    $('#deactivate-dialog')[0].close();
                }
            }).always(function() {
                $('#deactivate-dialog button').prop('disabled', false);
            });
        }
        function reactivateUser(event) {
            event.stopImmediatePropagation();
            event.preventDefault();
            var id = event.data.id;
            $('#activate-dialog button').prop('disabled', true);
            $.ajax({
                url: '/api/dashboard/users/' + id + '/activate',
                method: 'POST',
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    var response = data.message;
                    var html = data.html;
                    alert(response);
                    $('.user-dashboard-card').replaceWith(html);
                    $('#activate-dialog')[0].close();
                }
            }).always(function() {
                $('#activate-dialog button').prop('disabled', false);
            });
        }
        function deleteUser(event) {
            event.preventDefault();
            var id = event.data.id;
            $('#delete-dialog button').prop('disabled', true);
            $.ajax({
                url: '/api/dashboard/users/' + id,
                method: 'DELETE',
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    var response = data.message;
                    alert(response);
                    $(id).remove();
                    $('.user-dashboard-card').remove();
                    $('#delete-dialog')[0].close();
                    $('#users-prompt').text('Выберите пользователя из списка слева, чтобы настроить его права доступа…');
                    $('#users-prompt').css('display', '');
                }
            }).always(function() {
                    $('#delete-dialog button').prop('disabled', false);
                });
        }

        function showDeactivateDialog(event) {
            event.preventDefault();
            $('#deactivate-dialog')[0].showModal();
        }
        function showActivateDialog(event) {
            event.preventDefault();
            $('#activate-dialog')[0].showModal();
        }
        function showDeleteDialog(event) {
            event.preventDefault();
            $('#delete-dialog')[0].showModal();
        }
        
        $(document).ready(function() {
            $('.user-card-wrapper').on('click', function() {
                var id = $(this).attr('id');
                openUserCard(id);
            });
            $('#deactivate-cancel').on('click', function(e) {
                e.preventDefault();
                $('#deactivate-dialog')[0].close();
            });
            $('#activate-cancel').on('click', function(e) {
                e.preventDefault();
                $('#activate-dialog')[0].close();
            })
        });
            $('#delete-cancel').on('click', function(e) {
                e.preventDefault();
                $('#delete-dialog')[0].close();
            });
    </script>
</x-layout>
<dialog id="deactivate-dialog">
    <form action="/api/dashboard/users" method="dialog" id="deactivate-form">
        @csrf
        <h2>Отключение учётной записи</h2>
        <p class="dialog-confirmation">Вы уверены, что хотите отключить данную учётную запись?</p>
        <p class="dialog-note">Отключённые пользователи отображаются в тикетах, но не могут войти в систему.</p>
        <div class="dialog-buttons">
            <button id="deactivate-submit">Отключить</button>
            <button id="deactivate-cancel">Отмена</button>
        </div>    
    </form>
</dialog>
<dialog id="activate-dialog">
    <form action="/api/dashboard/users" method="dialog" id="activate-form">
        @csrf
        <h2>Включение учётной записи</h2>
        <p class="dialog-confirmation">Вы уверены, что хотите активировать данную учётную запись?</p>
        <p class="dialog-note">Данный пользователь вновь сможет выполнять вход и пользоваться системой.</p>
        <div class="dialog-buttons">
            <button id="activate-submit">Включить</button>
            <button id="activate-cancel">Отмена</button>
        </div>    
    </form>
</dialog>
<dialog id="delete-dialog">
    <form action="/api/dashboard/users" method="dialog" id="delete-form">
        @csrf
        @method('DELETE')
        <h2>Удаление учётной записи</h2>
        <p class="dialog-confirmation">Вы уверены, что хотите удалить данную учётную запись?</p>
        <p class="dialog-note">Это действие нельзя отменить. Рекомендуем вместо этого отключить учётную запись, чтобы история работы с ней всё равно отображалась в системе.</p>
        <div class="dialog-buttons">
            <button id="delete-submit">Удалить</button>
            <button id="delete-cancel">Отмена</button>
        </div>
    </form>
</dialog>
