@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">&nbsp;</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation"><a href="/home">首页</a></li>
                        <li role="presentation" class="active"><a href="/list">方剂管理</a></li>
                        <li role="presentation"><a href="/medicines">药剂管理</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="/detail/{{ $anagraph['ma_id'] }}" role="button">
                        {{ $anagraph['anagraph_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>方剂: {{ $anagraph['anagraph_name'] }}</h5>
                    <h5>来源: {{ $anagraph['anagraph_origin'] }}</h5>
                    <h5>最后修改于 : {{ $anagraph['modify_time'] }}</h5>
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
                            @foreach ($anagraph['consist'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $post['medicine_name'] }}</td>
                                    <td>{{ $post['dosage'] }}</td>
                                    <td>{{ $post['usage'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="/edit/{{ $anagraph['ma_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="/list?page={{ $anagraph['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
