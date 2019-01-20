@if ($errors->any() || isset($content))
    <div class="alert alert-danger">
        @isset($title)
            <h3>
                {!! $title !!}
            </h3>
        @endisset

        @isset($description)
            <p>
                {!! $description !!}
            </p>
        @endisset

        @if($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
            {{--
                Alternative Code - with composer package "laravelcollective/html" installed:
                {{ HTML::ul($errors->all()) }}
            --}}
        @else
            {!! $content !!}
        @endif
    </div>
@endif