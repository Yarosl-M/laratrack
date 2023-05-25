@props(['user', 'tags', 'users', 'permissions'])
@php
    $sheets = ['style_sidebar', 'style_dashboard'];
    $title = 'Панель управления';
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="main">
        <div id="users-tab">
            {{-- manage users' permissions, deactivate accounts and so on --}}
            <h1>Управление учётными записями</h1>
            <section class="users-tab-main">
                <div class="bordered user-list-wrapper user-tab-item">
                    <h3>Пользователи системы</h3>
                    <div class="user-list">
                        @foreach ($users->except(['id', $user->id])->sortBy('id') as $u)
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
        @if (true)
            @can('update', App\Models\Tag::class)
                <template id="tag-form-template">
                    {{-- maybe not required? --}}
                </template>
                <div id="tags-tab" style="display:none">
                    <h1>Управление тегами</h1>
                    <section class="tags-tab-main">
                        <div class="tag-list bordered">
                            @foreach ($tags->sortBy('id') as $tag)
                                @php        
                                    $usages = $tag->tickets->count();
                                @endphp
                                <x-tag-dashboard-card :tag="$tag" :usages="$usages"/>
                            @endforeach
                            <div id="add-tag-section">
                                <input type="text" id="new-tag-name" placeholder="Введите имя тега…" style="visibility:hidden">                                
                                <a href="#" class="no-underline hover-underline" id="add-tag-link">Добавить тег…</a>
                                <a href="#" class="no-underline hover-underline" style="display:none" id="save-new-tag-link">Сохранить</a>
                                <a href="#" class="no-underline hover-underline" style="display:none" id="cancel-new-tag-link">Отмена</a>
                            </div>
                        </div>
                    </section>
                </div>
            @endcan
        @endif
    </div>
    <div class="sidebar">
        <a href="#" class="sidebar-link" id="users-tab-link">Управление пользователями</a>
        <a href="" class="sidebar-link" id="tags-tab-link">Управление тегами</a>
    </div>
    {{-- scripts for users tab --}}
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
        function addClosures(id) {
            // ???
        }
        // add all of the necessary event listeners to a card
        function initCard(id) {
            // there's some black magic happening around which I will need to get to later
            // basically, for some reason the ids attached to those event listeners below
            // might unexpectedly change when you're switching between the users
            // dayum turns out it probably didn't remove the previous event listeners!
            // whahahha
            console.log('init card ' + id);
            $('#submit-user-settings').off('click');
            $('#deactivate-link').off('click');
            $('#activate-link').off('click');
            $('#delete-user-link').off('click');
            $('#deactivate-submit').off('click');
            $('#activate-submit').off('click');
            $('#delete-submit').off('click');

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
        // this one will add a card and the init its event listeners
        function addCard(id, html) {
            if ($('.user-dashboard-card').length == 0)
                $('#users-tab .users-tab-main').first().append(html);
            else
                $('.user-dashboard-card').replaceWith(html);
            initCard(id);
        }
        function errorAlert() {
            alert('При обработке Вашего запроса произошла ошибка. Пожалуйста, повторите попытку позже.');
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
                    // console.log(selectedId + ' / ' + id);
                    selectedId = id;
                    $('#users-prompt').css('display', 'none');
                    var html = data.html;
                    addCard(selectedId, html);
                }
            });
        }
        function submitUserForm(event) {
            event.stopImmediatePropagation();
            event.preventDefault();
            var id = event.data.id;
            // console.log(id);
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
                error: function(xhr, status, exception) {
                    errorAlert();
                }
            }).always(function() {
                setTimeout(() => { $('#submit-user-settings').prop('disabled', false); }, 500);
            });
        }
        function deactivateUser(event) {
            event.stopImmediatePropagation();
            event.preventDefault();
            var id = event.data.id;
            // console.log('deactivate ' + id);
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
                    addCard(id, html);
                    $('#deactivate-dialog')[0].close();
                    $('html,body').scrollTop(0);
                },
                error: function(xhr, status, exception) {
                    errorAlert();
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
            // console.log('reactivate ' + id);
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
                    addCard(id, html);
                    $('#activate-dialog')[0].close();
                    $('html,body').scrollTop(0);
                },
                error: function(xhr, status, exception) {
                    errorAlert();
                }
            }).always(function() {
                $('#activate-dialog button').prop('disabled', false);
            });
        }
        function deleteUser(event) {
            event.stopImmediatePropagation();
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
                    $('.user-card-wrapper#' + id).remove();
                    $('#delete-dialog')[0].close();
                    $('#users-prompt').text('Выберите пользователя из списка слева, чтобы настроить его права доступа…');
                    $('#users-prompt').css('display', '');
                    $('html,body').scrollTop(0);
                },
                error: function(xhr, status, exception) {
                    errorAlert();
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
    {{-- scripts for tags tab --}}
    <script>
        let textField = (id) => $('input[name="' + id + '"]');
        let tagName = (id) => $('#' + id + ' .tag-name');
        let editTagBtn = (id) => $('#' + id + ' .tag-edit');
        let saveTagBtn = (id) => $('#' + id + ' .tag-edit-save');
        let cancelEditBtn = (id) => $('#' + id + ' .tag-edit-cancel');
        function bindTagEvents(id) {
            $('.tag-wrapper#' + id + ' .tag-edit').on('click', openEditForm.bind(null, id));
            $('.tag-wrapper#' + id + ' .tag-edit-cancel').on('click', cancelTagEdit.bind(null, id));
            $('.tag-wrapper#' + id + ' .tag-edit-save').on('click', saveTagEdit.bind(null, id));
            $('.tag-wrapper#' + id + ' .tag-delete').on('click', deleteTag.bind(null, id));
        }
        function openEditForm(id) {
            idList.forEach(element => {
                if (element != id) {
                    cancelTagEdit(element);
                }
            });
            closeNewForm();
            tagName(id).css('display', 'none');
            textField(id).val(tagName(id).text()); textField(id).css('display', '');
            editTagBtn(id).css('display', 'none');
            saveTagBtn(id).css('visibility', '');
            cancelEditBtn(id).css('visibility', '');
        }
        function cancelTagEdit(id) {
            textField(id).css('display', 'none'); textField(id).val(tagName(id).text());
            tagName(id).css('display', ''); 
            saveTagBtn(id).css('visibility', 'hidden');
            cancelEditBtn(id).css('visibility', 'hidden');
            editTagBtn(id).css('display', '');
        }
        let stuff = undefined;
        function saveTagEdit(id) {
            var tagForm = new FormData();
            var newName = textField(id).val();
            tagForm.set('name', newName);
            // ha!
            tagForm.set('_method', 'PUT');
            $.ajax({
                url: '/api/dashboard/tags/' + id,
                method: 'POST',
                data: tagForm,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    alert('Тег успешно изменён');
                    $('#' + id + ' .tag-name').text(newName);
                },
                error: function(xhr, status, error) {
                    switch (xhr.status) {
                        case 422:
                            if (xhr.responseJSON.message.includes('required')) {
                                alert('Ошибка: название тега обязательно для заполнения');
                            }
                            else if (xhr.responseJSON.message.includes('taken')) {
                                alert('Ошибка: такое название тега уже занято');
                            }
                            break;
                        default:
                            alert('Произошла неизвестная ошибка. Пожалуйста, повторите попытку позже.');
                            break;
                    }
                }
            }).always(function() {
                cancelTagEdit(id);
            });
        }
        function deleteTag(id) {
            var usageCount = $('#' + id).attr('data-usages');
            var msg = '';
            if (usageCount > 0) {
                msg += 'Этот тег был использован в тикетах ';
                msg += usageCount;
                msg += ' раз'
                if (usageCount > 1 && usageCount < 5) {
                    msg += 'а';
                }
                msg += '. ';
            }
            msg += 'Вы уверены, что хотите удалить этот тег?'
            if (confirm(msg)) {
                // blah blah delete as usual
            }
        }
        function createTag(id) {
        }
        function openNewForm() {
            $('#new-tag-name').css('visibility', '');
            $('#add-tag-link').css('display', 'none');
            $('#save-new-tag-link').css('display', '');
            $('#cancel-new-tag-link').css('display', '');
            idList.forEach(element => cancelTagEdit(element));
        }
        function closeNewForm() {
            $('#new-tag-name').css('visibility', 'hidden');
            $('#add-tag-link').css('display', '');
            $('#save-new-tag-link').css('display', 'none');
            $('#cancel-new-tag-link').css('display', 'none');
            $('#new-tag-name').val('');
        }
        function createNewTag() {
            let fd = new FormData();
            let tagName = $('#new-tag-name').val();
            fd.set('name', tagName);
            $.ajax({
                url: '/api/dashboard/tags',
                method: 'POST',
                data: fd,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    alert(data.message);
                    let html = data.html;
                    let newId = data.id;
                    $('#add-tag-section').before(html);
                    bindTagEvents(newId);
                },
                error: function(xhr, status, error) {
                    switch (xhr.status) {
                        case 422:
                            if (xhr.responseJSON.message.includes('required')) {
                                alert('Ошибка: название тега обязательно для заполнения');
                            }
                            else if (xhr.responseJSON.message.includes('taken')) {
                                alert('Ошибка: такое название тега уже занято');
                            }
                            break;
                        default:
                            alert('Произошла неизвестная ошибка. Пожалуйста, повторите попытку позже.');
                            break;
                    }
                }
            }).always(function() {
                cancelTagEdit();
                closeNewForm();
            });
        }
        let idList = [];
        $(document).ready(function() {
            $('.tag-wrapper').each(function() {
                var id = $(this).attr('id');
                idList.push(id);
                bindTagEvents(id);
            });
            $('#add-tag-link').on('click', function(e) {
                e.preventDefault();
                openNewForm();
            });
            $('#save-new-tag-link').on('click', function(e) {
                e.preventDefault();
                createNewTag();
            })
            $('#cancel-new-tag-link').on('click', function(e) {
                e.preventDefault();
                closeNewForm();
            })
        });
    </script>
    {{-- global (e. g. changing tabs) --}}
    <script>
        function showTab(tabId) {
            $('[id$="-tab"]').css('display', 'none');
            $('#' + tabId).css('display', '');
        }
        $(document).ready(function() {
            $('#users-tab-link').on('click', function(e) {
                e.preventDefault();
                showTab('users-tab');
            });
            $('#tags-tab-link').on('click', function(e) {
                e.preventDefault();
                showTab('tags-tab');
            });
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
