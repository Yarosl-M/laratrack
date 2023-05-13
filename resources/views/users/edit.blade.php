@props(['user'])
@php
$sheets = ['style_sidebar', 'style_account_edit'];
$title = $user->displayName();
@endphp
<x-layout :stylesheets="$sheets" :title="$title">
    <div class="main">
        <div id="account-tab">
            <h1>Настройки учётной записи</h1>
            <div style="display:flex;flex-direction:row;">
                <div class="bordered section">
                    <h3>Учётные данные</h3>
                    <form method="POST" class="account-settings">
                        @csrf
                        <label class="username-label">Имя пользователя (Вы не можете его изменить)</label>
                        <input class="username" type="text" disabled readonly value="{{$user->username}}">
                        <label for="name" class="name-label">Ваше имя (необязательно)</label>
                        <input type="text" class="name" name="name" value="{{$user->name}}">
                        <label for="email" class="email-label">Адрес эл. почты</label>
                        <input type="email" class="email" name="email" value="{{$user->email}}">
                        <button class="save-user-settings-btn">Сохранить изменения</button>
                        <button class="reset-user-settings-btn">Сброс</button>
                        <a class="no-underline" style="justify-self: start;" href="/account/change-password" class="change-pwd">Сменить пароль…</a>
                    </form>
                </div>
                <form method="POST" enctype="multipart/form-data" action="/account/update-profile-picture" class="bordered section user-pfp-settings">
                    <h3>Изображение учётной записи</h3>
                    @csrf
                    <x-user-pfp :user="$user" :size="8"/>
                    <label style="font-size:0.8rem">Максимальный размер изображения: 3 МБ.<br>Поддерживаемые форматы: PNG, JPEG, JPG.<br></label>
                    <input type="file" name="pfp" accept=".png,.jpg,.jpeg">
                    <button type="submit">Загрузить</button>
                </form>
            </div>
        </div>
        <div id="tickets-tab">
            <h1>Мои тикеты</h1>
            @if ($user->type == 'operator' || $user->type == 'admin')
            <div class="bordered section assigned-tickets">
                <h3>Назначенные тикеты</h3>
                @if ($user->assigned_tickets->isEmpty())
                    Вам не назначено тикетов.
                @else
                <div class="assigned-ticket-list">
                    @foreach ($user->assigned_tickets as $ticket)
                        <x-ticket-card :ticket="$ticket"/>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
            <div class="bordered section created-tickets">
                <h3>Созданные мной тикеты</h3>
                @if ($user->created_tickets->isEmpty())
                    Вы не создавали тикетов.
                @else
                    <div class="created-ticket-list">
                        @foreach ($user->created_tickets as $ticket)
                        <x-ticket-card :ticket="$ticket"/>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="sidebar">
        <a href="" class="sidebar-link" id="account-tab-link">Настройки учётной записи</a>
        <a href="" class="sidebar-link" id="tickets-tab-link">Мои тикеты</a>
    </div>
    <script>
        $(document).ready(function(e) {
            const initialName = '{{$user->name}}';
            const initialEmail = '{{$user->email}}';
            $('#account-tab-link').on('click', function(e) {
                $('#tickets-tab').css('display', 'none');
                $('#account-tab').css('display', 'block');
                e.preventDefault();
            });
            $('#tickets-tab-link').on('click', function(e) {
                $('#account-tab').css('display', 'none');
                $('#tickets-tab').css('display', 'block');
                e.preventDefault();
            });
            $('.reset-user-settings-btn').on('click', function(e) {
                $('input[name="name"]').val(initialName);
                $('input[name="email"]').val(initialEmail);
                e.preventDefault();
            })
        })
    </script>
</x-layout>