@props(['permission', 'checked', 'disabled'])
<div class="permission-card">
    <div style="display:flex;flex-direction:row;">
        <input @disabled($disabled) @checked($checked == 'true') type="checkbox" name="p-{{$permission->id}}" id="{{$permission->id}}">
        <label for="{{$permission->id}}">{{$permission->display_name}}</label>    
    </div>
    <p class="permission-description">
        {{$permission->description}}
    </p>
</div>