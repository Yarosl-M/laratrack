<x-layout :stylesheets="['style_index', 'style_pagination', 'style_sidebar']" :title="$title">
    <section class="main">
        <form class="search" action="">
            <div>
                <input type="text" name="search" size="50" value="{{$queryData['search']}}" placeholder="Введите поисковый запрос…">
                <button type="submit">Поиск</button>
                <button type="reset">Очистить</button>
            </div>
        </form>
        <div class="ticket-list">
            @foreach ($tickets as $ticket)
                <x-ticket-card :ticket="$ticket"/>
            @endforeach
        </div>
        <div class="pagination-wrapper">
            {{$tickets->links()}}
        </div>    
    </section>
    <section class="sidebar">
        <div class="sort-list">
            <h2>Сортировка</h2>
            <div class="radio-wrapper">
                <input type="radio" @checked($queryData['sort_by'] == 'latest') name="sort_by" value="latest" id="latest">
                <label for="latest">Сначала новые</label>    
            </div>
            <div class="radio-wrapper">
                <input type="radio" @checked($queryData['sort_by'] == 'oldest') name="sort_by" value="oldest" id="oldest">
                <label for="oldest">Сначала старые</label>
            </div>
            <div class="radio-wrapper">
                <input type="radio" @checked($queryData['sort_by'] == 'oldest_activity') name="sort_by" value="oldest_activity" id="oldest_activity">
                <label for="oldest_activity">Без последней активности</label>
            </div>
            <div class="radio-wrapper">
                <input type="radio" @checked($queryData['sort_by'] == 'latest_activity') name="sort_by" value="latest_activity" id="latest_activity">
                <label for="latest_activity">Недавно активные</label>
            </div>
        </div>
        <div class="status">
            <h2>Статус тикета</h2>
            <select name="status" id="status">
                <option @selected($queryData['status'] == 'all') value="all">Все</option>
                <option @selected($queryData['status'] == 'open') value="open">Открытые</option>
                <option @selected($queryData['status'] == 'closed') value="closed">Закрытые</option>
            </select>
        </div>
        <div class="tag-choose">
            <h2>Теги</h2>
            <div class="tag-list">
                @foreach ($tags as $tag)
                {{-- so much for 'radio' huh? --}}
                <div class="radio-wrapper">
                    <input type="checkbox" @checked(!is_null($queryData['tags']) && in_array($tag->id, $queryData['tags'])) name="tags[]" value={{$tag->id}} id="{{$tag->id}}">
                    <label for="{{$tag->id}}">{{$tag->name}}</label>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
<script>
    $(document).ready(function() {
        // return;
        // we'll have to combine both elements into one form and then submit it
        $('button[type="submit"]').on('click', function(e) {
            e.preventDefault();
            var form = $('<form>')
                .attr('method', 'GET')
                .attr('action', '{{url()->current()}}')
                .css('display', 'none');
            form.append($('input[name="search"]'));
            form.append($('input[type="radio"]:checked'));
            form.append($('select'));
            form.append($('input[type="checkbox"]:checked'));
            $('body').append(form);
            form.submit();
        });
    })
</script>