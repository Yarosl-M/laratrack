@props(['tag', 'usages'])
<div class="tag-wrapper bordered" id="{{$tag->id}}" data-usages="{{$usages}}">
    <span class="tag-name">{{$tag->name}}</span>
    <input type="text" name="{{$tag->id}}" style="display:none">
    <span class="tag-option tag-edit-save no-underline hover-underline" style="visibility:hidden;">Сохранить</span>
    <span class="tag-option tag-edit-cancel no-underline hover-underline" style="visibility:hidden;">Отмена</span>
    <span class="tag-option tag-edit no-underline hover-underline">Изменить</span>
    <span class="tag-option tag-delete no-underline hover-underline">Удалить</span>
</div>