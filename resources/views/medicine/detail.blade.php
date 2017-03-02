<html>
    <head>
        <title>{{ $anagraph['anagraph_name'] }}</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>{{ $anagraph['anagraph_name'] }}</h1>
            <h3>{{ $anagraph['anagraph_origin'] }}</h3>
            <h5>{{ $anagraph['create_time'] }}</h5>
            <hr>
            @foreach ($anagraph['consist'] as $post)
                <p>
                    {{ $post['medicine_name'] }}
                    {{ $post['dosage'] }}
                    {{ $post['usage'] }}
                </p>
            @endforeach
            <hr>
            <button class="btn btn-primary" onclick="history.go(-1)">
                « Back
            </button>
        </div>
    </body>
</html>
