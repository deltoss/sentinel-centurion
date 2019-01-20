@if (Session::has('message') || isset($content))
    <div class="alert alert-info">
        @isset($title)
            <h3>
                {!! $title !!}
            </h3>
        @endisset

        @if(isset($content))
            {!! $content !!}
        @else
            {!! Session::get('message') !!}
        @endif
    </div>
@endif