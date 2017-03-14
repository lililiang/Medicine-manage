@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('disease.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="/detail/{{ $disease['md_id'] }}" role="button">
                        {{ $disease['disease_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>病症: {{ $disease['disease_name'] }}</h5>
                    <h5>病症描述: {{ $disease['disease_desc'] }}</h5>
                    <h5>最后修改于 : {{ $disease['modify_time'] }}</h5>
                    <hr>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>别名</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disease['consist'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $post['disease_alias'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="/editDisease/{{ $disease['md_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="/diseases?page={{ $disease['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
