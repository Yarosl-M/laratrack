{{-- Show a single ticket page --}}
<x-layout :stylesheets="$sheets" :title="$title">
    <h1>
        {{$ticket->subject}}
    </h1>
    <div class="message first-message">
        <div class="user-info">
            <a href="/users/olp123">
                <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png">
            </a>
            <p>username <i>создаёт тикет (1 день назад)</i></p>
        </div>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam, perspiciatis repellat ut officia aliquam molestias quaerat quasi a nulla suscipit similique et facilis dolor dignissimos quis nostrum beatae incidunt libero. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde eos iste quis dignissimos neque fuga. Doloribus, quasi. Quod necessitatibus ad vel! In magni optio quae vero fugiat nostrum porro tempore.</p>
        <p class="attachments-header">Прикреплённые файлы:</p>
        <ul class="bordered">
            <li><a href="{{url('/', ['tickets', 'create'])}}">filename.png</a></li>
            <li><a href="{{url('/', ['tickets', 'create'])}}">filename2.png</a></li>
        </ul>
    </div>
    <div class="action">
        <a href="/users/olp123">
            <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png">
        </a>
        <p>
            username <i>изменяет теги на</i> 
            <span class="tag">Проблемы с оборудованием</span>, <span class="tag">Гарантия</span> 
            <i>(2 часа назад)</i>
        </p>
    </div>
    <div class="action">
        <a href="/users/olp123">
            <img class="pfp" src="https://cdn.discordapp.com/attachments/1085284239815217182/1104351697335230564/j01.png">
        </a>
        <p>
            username <i>оценивает качество обслуживания в </i>
            <img src="{{url('/assets/star_filled.svg')}}" style="width:1.5rem;height:1.5rem;">
            <img src="{{url('/assets/star_filled.svg')}}" style="width:1.5rem;height:1.5rem;">
            <img src="{{url('/assets/star_filled.svg')}}" style="width:1.5rem;height:1.5rem;">
            <img src="{{url('/assets/star.svg')}}" style="width:1.5rem;height:1.5rem;">
            <img src="{{url('/assets/star.svg')}}" style="width:1.5rem;height:1.5rem;">
            <i>(2 часа назад)</i>
        </p>
    </div>
</x-layout>