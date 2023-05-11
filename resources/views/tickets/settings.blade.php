@props(['ticket'])
<x-layout :stylesheets="$sheets" :title="$title">
    <form method="post" action="/{{Request::path()}}" class="bordered settings-wrapper">
        @csrf
        <h2>
            {{$ticket->subject}}
        </h2>
        <section class="settings-form">
            <section class="form-tags">
                <h3>Теги</h3>
                <div class="tag-list">
                    @foreach (App\Models\Tag::get() as $t)
                    <div class="tag-wrapper">
                        @php
                            $tags = $ticket->tags;
                        @endphp
                        <input type="checkbox" name="tag-{{$t->id}}" value="tag-{{$t->id}}" id="tag-{{$t->id}}" @checked($tags->contains($t->id))/>
                        <label for="tag-{{$t->id}}">{{$t->name}}</label>
                    </div>
                    @endforeach
                </div>
            </section>
            <section class="form-priority">
                Приоритет тикета:
                <select name="priority">
                    @foreach (App\Models\Priority::get() as $p)
                        <option @selected($p->id === $ticket->priority_id) value="p-{{$p->id}}">{{$p->name}}</option>
                    @endforeach
                </select>
            </section>
            <section class="form-assignee">
                <input type="hidden" name="assignee" value="{{$ticket->assigned_to}}">
                <h3>Ответственный сотрудник</h3>
                @if ($ticket->assigned_to ?? false)
                    <div id="assignee-name">
                        <b>{{$ticket->assignee->displayName()}}</b>
                    </div>
                    <a id="assign-another" href="#" class="no-underline">Назначить другого сотрудника…</a><br>
                    <a id="unassign" href="#" class="no-underline">Снять сотрудника {{$ticket->assignee->displayName()}} с тикета</a>
                @else
                    <div id="assignee-name">Нет ответственного сотрудника.</div>
                    <a id="assign" href="#" class="no-underline">Назначить сотрудника…</a>
                @endif
            </section>    
        </section>
        <div class="settings-btns">
            <button class="btn-save">Сохранить изменения</button>
            <button class="btn-cancel">Отмена</button>
        </div>
    </form>
    <dialog id="assign-dialog">
        <form method="dialog" class="dialog-wrapper">
            <h2>Выбрать сотрудника</h2>
            <p>Отображаются только сотрудники, имеющие права для работы над данным тикетом.</p>
            <div class="assignee-list">
                @foreach (App\Models\User::active()->employees()->get()->filter(fn(App\Models\User $u) => $u->can('send_message', $ticket) && $u->id !== Auth::id()) as $user)
                    <div class="user-card">
                        <x-user-pfp :user="$user" size="2"/>
                        <span>{{$user->displayName()}}</span>
                        <button @disabled($user->id===$ticket->assigned_to) class="assign-button"id="u-{{$user->id}}">Выбрать</button>
                        </button>
                    </div>
                @endforeach
            </div>
            <button class="assign-cancel">Отмена</button>
        </form>
    </dialog>
    <script>
        $(document).ready(function() {
            // open form
            $('#assign').on('click', function() {
            $('#assign-dialog')[0].showModal();
        });
        // assignee buttons
        $('button[id^="u-"]').click(function() { 
            var userId = $(this).attr('id').substring(2);
            var assignee = $('input[name="assignee"]');
            console.log('cbt');
            assignee.val(userId); });
                    // cancel button
            $('button.btn-cancel').click(function() {
                window.location.href = "{{url('/tickets/' . $ticket->id)}}"
                return false;
            });
        });
    </script>
</x-layout>