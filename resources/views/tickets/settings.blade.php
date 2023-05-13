@props(['ticket'])
<x-layout :stylesheets="$sheets" :title="$title">
    <form method="post" action="/{{ Request::path() }}" class="bordered settings-wrapper">
        @csrf
        <h2>
            {{ $ticket->subject }}
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
                            <input type="checkbox" name="tag-{{ $t->id }}" value="tag-{{ $t->id }}"
                                id="tag-{{ $t->id }}" @checked($tags->contains($t->id)) />
                            <label for="tag-{{ $t->id }}">{{ $t->name }}</label>
                        </div>
                    @endforeach
                </div>
            </section>
            <section class="form-priority">
                Приоритет тикета:
                <select name="priority">
                    @foreach (App\Models\Priority::get() as $p)
                        <option @selected($p->id === $ticket->priority_id) value="p-{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </section>
            <section class="form-assignee">
                <input type="hidden" name="assignee" value="{{ $ticket->assigned_to }}">
                <h3>Ответственный сотрудник</h3>
                <p id="no-assignee-text" @style([
                    'display: none;' => $ticket->assigned_to != null,
                ])>Нет ответственного сотрудника.
                </p>
                <p id="assigned-text" @style([
                    'display: none;' => $ticket->assigned_to == null,
                ])>
                    {{ $ticket->assigned_to == null ? $ticket->assigned_to : $ticket->assignee->displayName() }}
                </p>
                <a id="assign" href="#" class="no-underline">Изменить…</a><br>
                <a id="unassign" @style([
                    'display: none;' => $ticket->assigned_to == null,
                ]) href="#" class="no-underline">Снять сотрудника {{ $ticket->assigned_to == null ? $ticket->assigned_to : $ticket->assignee->displayName() }} с тикета</a>
                {{-- @if ($ticket->assigned_to ?? false)
                    <div id="assignee-name">
                        <b>{{$ticket->assignee->displayName()}}</b>
                    </div>
                    <a id="assign-another" href="#" class="no-underline">Назначить другого сотрудника…</a><br>
                @else
                    <div id="assignee-name">Нет ответственного сотрудника.</div>
                @endif --}}
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
                    <div class="user-card" id="{{$user->id}}">
                        <x-user-pfp :user="$user" size="2" />
                        <span>{{ $user->displayName() }}</span>
                        <button class="assign-button"id="u-{{ $user->id }}">Выбрать</button>
                        </button>
                    </div>
                @endforeach
            </div>
            <button class="assign-cancel">Отмена</button>
        </form>
    </dialog>
    <script>
        function hide(element) { element.css('display', 'none'); }
        function show(element) { element.css('display', ''); }
        $(document).ready(function() {
            // open form
            $('#assign').on('click', function(e) {
                e.preventDefault();
                $('#assign-dialog')[0].showModal();
            });

            $('input[name="assignee"]').on('change', function(e) {
                var noAssignee = $('#no-assignee-text');
                var assigned = $('#assigned-text');
                var unassign = $('#unassign');
                // unassign
                if ($(this).val() == '') {
                    show(noAssignee);
                    hide(assigned);
                    hide(unassign);
                }
                else {
                    var id = $(this).val();
                    var name = $('.user-card#' + id + ' span').text();
                    hide(noAssignee);
                    assigned.text(name);
                    show(assigned);
                    unassign.text('Снять сотрудника ' + name + ' с тикета');
                    show(unassign);
                }
            });

            $('#unassign').on('click', function(e) {
                e.preventDefault();
                var assignee = $('input[name="assignee"]');
                assignee.val('').trigger('change');
            });

            // assignee buttons
            $('button[id^="u-"]').click(function() {
                var userId = $(this).attr('id').substring(2);
                var assignee = $('input[name="assignee"]');
                assignee.val(userId).trigger('change');
            });
            // cancel button
            $('button.btn-cancel').click(function(e) {
                e.preventDefault();
                window.location.href = "{{ url('/tickets/' . $ticket->id) }}"
            });
        });
    </script>
</x-layout>
