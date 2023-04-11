<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Priority;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public static function initPermissions()
    {
        $perms = [
            [
                'name' => 'create_tickets',
                'display_name' => 'Создание тикетов',
                'class' => 'client',
                'description' => 'Позволяет пользователю создавать новые тикеты.'
            ],
            [
                'name' => 'view_tickets',
                'display_name' => 'Просмотр тикетов',
                'class' => 'client',
                'description' => 'Позволяет подтверждённому пользователю просматривать свои тикеты.'
            ],
            [
                'name' => 'send_messages_client',
                'display_name' => 'Отправка сообщений (клиент)',
                'class' => 'client',
                'description' => 'Позволяет клиенту писать новые сообщения в тикете.'
            ],
            [
                'name' => 'send_feedback',
                'display_name' => 'Отправка отзыва',
                'class' => 'client',
                'description' => 'Позволяет клиенту отправлять отзыв по закрытии тикета.'
            ],
            [
                'name' => 'view_assigned_tickets',
                'display_name' => 'Просмотр назначенных тикетов',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику просматривать назначенные ему тикеты.'
            ],
            [
                'name' => 'view_unassigned_tickets',
                'display_name' => 'Просмотр тикетов без назначенного сотрудника',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику просматривать тикеты, которые не назначены ни одному сотруднику.'
            ],
            [
                'name' => 'view_all_tickets',
                'display_name' => 'Просмотр всех тикетов',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику просматривать все тикеты без исключений.'
            ],
            [
                'name' => 'send_messages_operator',
                'display_name' => 'Отправка сообщений (сотрудник)',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику писать новые сообщения в тикете.'
            ],
            [
                'name' => 'change_ticket_params',
                'display_name' => 'Изменение свойств тикета',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику изменять приоритет и теги тикета.'
            ],
            [
                'name' => 'change_ticket_status',
                'display_name' => 'Изменение статуса тикета',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику помечать тикет как закрытый (разрешённый) или открытый.'
            ],
            [
                'name' => 'assign_tickets',
                'display_name' => 'Назначение тикетов сотрудникам',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику назначать и снимать сотрудников с тикетов. Нельзя назначить или снять самого себя.'
            ],
            [
                'name' => 'archive_tickets',
                'display_name' => 'Архивирование тикетов',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику отправлять тикеты в архив после их закрытия.'
            ],
            [
                'name' => 'view_archived_tickets',
                'display_name' => 'Просмотр архивных тикетов',
                'class' => 'operator',
                'description' => 'Позволяет сотруднику просматривать тикеты в архиве.'
            ],
            [
                'name' => 'edit_user_permissions',
                'display_name' => 'Управление учётными записями',
                'class' => 'admin',
                'description' => 'Позволяет изменять параметры учётных записей пользователей.'
            ],
            [
                'name' => 'view_reports',
                'display_name' => 'Просмотр отчётности',
                'class' => 'admin',
                'description' => 'Позволяет создавать и просматривать отчёты по работе организации.'
            ],
            [
                'name' => 'deactivate_users',
                'display_name' => 'Отключение учётных записей',
                'class' => 'admin',
                'description' => 'Позволяет отключать учётные записи пользователей. Отключённый пользователь будет отображаться в связанных тикетах, но от его имени нельзя выполнить вход в систему. Данное действие рекомендуется для, например, уволившихся сотрудников вместо удаления их учётных записей.'
            ],
            [
                'name' => 'edit_tags',
                'display_name' => 'Управление тегами',
                'class' => 'admin',
                'description' => 'Позволяет изменять теги, использующиеся в системе.'
            ],
            [
                'name' => 'delete_tickets',
                'display_name' => 'Удаление тикетов',
                'class' => 'admin',
                'description' => 'Позволяет удалять тикеты. ВНИМАНИЕ: в большинстве случаев рекомендуется вместо этого отправлять тикеты в архив.'
            ],
            [
                'name' => 'delete users',
                'display_name' => 'Удаление учётных записей',
                'class' => 'admin',
                'description' => 'Позволяет удалять учётные записи пользователей. ВНИМАНИЕ: В большинстве случаев рекомендуется вместо этого отключать учётную запись пользователя.'
            ],
            [
                'name' => 'superuser',
                'display_name' => 'Суперпользователь',
                'class' => 'admin',
                'description' => 'Пользователь с этим правом доступа обходит все ограничения в системе. ВНИМАНИЕ: давать это право опасно. Рекомендуется только для первичной настройки системы администратором.'
            ],
        ];

        foreach ($perms as $permission) {
            Permission::create($permission);
        }
    }

    public static function initPriorities()
    {
        $priorities = [
            [ 'name' => 'Критический' ],
            [ 'name' => 'Высокий' ],
            [ 'name' => 'Средний' ],
            [ 'name' => 'Низкий' ],
            [ 'name' => 'Несрочный' ],
            [ 'name' => 'Не установлен' ],
        ];

        foreach ($priorities as $p) {
            Priority::create($p);
        }
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    }
}
