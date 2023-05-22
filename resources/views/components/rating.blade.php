@props(['ticket'])
<form class="bordered" action="/tickets/{{$ticket->id}}/rate" method="post" id="rating-form">
    @csrf
    <h2>Оцените качество обслуживания на данном тикете</h2>
    <div style="display:flex;">
        <div style="display:flex;flex-direction:column;flex-grow:1;gap:0.5rem;align-items:center">
            <div id="rating-wrapper">
                @for ($i = 5; $i >= 1; $i--)
                <input class="rating-input" id="rating-{{$i}}" name="rating" type="radio" value="{{$i}}"/>
                <label class="rating-star" for="rating-{{$i}}"></label>
                @endfor
            </div>
            <button id="rating-submit"style="visibility:hidden" type="submit">Отправить</button>    
        </div>
        <div>
            <a class="no-underline hover-underline" style="visibility:hidden" id="reset-rating" href="#">Сброс</a> 
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#reset-rating').on('click', function(e) {
            e.preventDefault();
            $('.rating-input').prop('checked', false);
            $('#rating-submit, #reset-rating').css('visibility', 'hidden');
        });

        $('.rating-input').on('change', function(e) {
            $('#rating-submit, #reset-rating').css('visibility', '');
        });
    });
</script>