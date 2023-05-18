@props(['user', 'permissions'])
<div class="bordered user-tab-item user-dashboard-card" id="card-{{$user->id}}">
    <h2>{{$user->displayName()}}{{$user->deactivated_at != null ? ' (отключён)' : ''}}</h2>
    <form action="{{url("api/dashboard/users", $user->id)}}" method="POST">
        @csrf
        @can('change_permissions', $user)
            <div class="bordered user-type-settings">
                <div style="display:flex;flex-direction:column;">
                    <h3>Тип учётной записи</h3>
                    <p>Этот параметр, наряду с правами доступа, определяет, выполнение каких операций доступно пользователю. Установите &laquo;Оператор&raquo; для сотрудников службы поддержки.</p>
                </div>
                <select @disabled($user->deactivated_at) name="type" id="type-{{$user->id}}" style="height:auto">
                    <option @selected($user->type == 'client') value="client">Клиент</option>
                    <option @selected($user->type == 'operator') value="operator">Оператор</option>
                    <option @selected($user->type == 'admin') value="admin">Администратор</option>
                </select>
            </div>
            <div class="bordered user-permissions">
                <h3>Права доступа пользователя</h3>
                <div class="bordered">
                    <h3>Клиент</h3>
                    <div class="permission-list">
                        @foreach ($permissions->filter(fn($p) => $p->class == 'client') as $p)
                            @php    
                                $checked = $user->permissions->contains($p->id);
                                $disabled = $user->deactivated_at != null;
                            @endphp
                            <x-permission-card :permission="$p" :checked="$checked" :disabled="$disabled"/>
                        @endforeach    
                    </div>
                </div>
                <div class="bordered">
                    <h3>Оператор</h3>
                    <div class="permission-list">
                        @foreach ($permissions->filter(fn($p) => $p->class == 'operator') as $p)
                        @php    
                            $checked = $user->permissions->contains($p->id);
                        @endphp
                        <x-permission-card :permission="$p" :checked="$checked" :disabled="$disabled"/>
                        @endforeach    
                    </div>
                </div>
                <div class="bordered">
                    <h3>Администратор</h3>
                    <div class="permission-list">
                        @foreach ($permissions->filter(fn($p) => $p->class == 'admin' && $p->name != 'superuser') as $p)
                        @php
                            $checked = $user->permissions->contains($p->id);
                        @endphp
                        <x-permission-card :permission="$p" :checked="$checked" :disabled="$disabled"/>
                        @endforeach
                        @php
                            $p = $permissions->firstWhere('name', 'superuser');
                            $checked = $user->hasPermission('superuser');
                        @endphp
                        <br>
                        <x-permission-card :permission="$p" :checked="$checked" :disabled="$disabled"/>
                    </div>
                </div>
            </div>
        @endcan
        <div style="display:flex;flex-direction:row;justify-content: space-between;align-items:baseline;gap:1rem">
            <button @disabled($user->deactivated_at) type="submit" id="submit-user-settings" style="margin-right: auto">
                Сохранить изменения
            </button>
            @can('deactivate', $user)
                @unless ($user->deactivated_at)
                <a href="#" id="deactivate-link" class="no-underline">Отключить учётную запись</a>
                @else
                <a href="#" id="activate-link" class="no-underline">Активировать учётную запись</a>
                @endunless
            @endcan
            @can('delete', $user)
                <a href="#" id="delete-user-link" style="color:#d00" class="no-underline">Удалить учётную запись</a>
            @endcan
        </div>
    </form>
</div>