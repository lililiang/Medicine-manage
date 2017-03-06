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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>药名</th>
                        <th>剂量</th>
                        <th>用法</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anagraph['consist'] as $post)
                        <tr>
                            <th scope="row">{{ $post['mac_id'] }}</th>
                            <td>{{ $post['medicine_name'] }}</td>
                            <td>{{ $post['dosage'] }}</td>
                            <td>{{ $post['usage'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <a class="btn btn-default" href="/edit/{{ $anagraph['ma_id'] }}" role="button">编辑</a>
            <a class="btn btn-primary" href="/list" role="button"> « 返回</a>
        </div>
    </body>
</html>
