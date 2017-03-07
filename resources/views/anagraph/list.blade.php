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
                    <a class="btn btn-link" href="/list?page={{ $posts->currentPage() }}" role="button">
                        {{ config('medicine.title') }}
                    </a>
                </div>

                <div class="panel-body">
                    <!-- <h1>{{ config('medicine.title') }}</h1>
                    <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5> -->
                    <a class="btn btn-primary" href="/add" role="button">新增方剂</a>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>方剂名称</th>
                                <th>方剂来源</th>
                                <th>修改时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="/detail/{{ $post->ma_id }}">
                                            {{ $post->anagraph_name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->anagraph_origin) }}</td>
                                    <td>{{ $post->modify_time }}</td>
                                </li>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    {!! $posts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
