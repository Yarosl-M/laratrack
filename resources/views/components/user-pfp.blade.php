@props(['user', 'size'])
@php
    $size = $size ?? 3;
@endphp
@if (isset($user->profile_picture))
    <img style="width:{{$size}}rem;height:{{$size}}rem;border-radius:50%;" src="/storage/users/{{$user->id}}/{{$user->profile_picture}}">
@else
    <img style="width:{{$size}}rem;height:{{$size}}rem;border-radius:50%;border-style:solid;border-width:3px;" src="/assets/usericon.svg">
@endif
