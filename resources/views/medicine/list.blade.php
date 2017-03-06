<html>
    <head>
        <title>{{ config('medicine.title') }}</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>{{ config('medicine.title') }}</h1>
            <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5>
            <hr>
            <ul>
            @foreach ($posts as $post)
                <li>
                    <a href="/detail/{{ $post->ma_id }}">{{ $post->anagraph_name }}</a>
                    <em>({{ $post->create_time }})</em>
                    <p>
                        {{ str_limit($post->anagraph_origin) }}
                    </p>
                </li>
            @endforeach
            </ul>
            <hr>
            {!! $posts->render() !!}
        </div>
    </body>
</html>
