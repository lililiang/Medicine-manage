@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="" role="button">
                        搜索结果
                    </a>
                </div>
                <div class="panel-body">
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
                            @foreach ($anagraphs as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/detail/{{ $post->ma_id }}">
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
                    <a class="btn btn-primary btn" href="{{ config('medicine.base_url') }}/list" role="button">返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
