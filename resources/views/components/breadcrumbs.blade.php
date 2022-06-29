{{-- resources/views/components/breadcrumbs.blade.php --}}

<div class="app-title">
    <div>
        @if(isset($title))
        <h1>    
            @if(isset($icon))
            <i class="fa {{$icon}}"></i>
            @endif
            {{$title}}
        
        </h1>
        @endif
        @if(isset($subtitle))
        <p>{{$subtitle}}</p>
        @endif
        
        {{ $slot }}
        
    </div>
    @if(isset($route) && isset($title))
    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item active"><a href="{{$route}}">{{$title}}</a></li>
    </ul>
    @endif
</div>