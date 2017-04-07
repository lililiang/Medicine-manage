@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/list?page={{ $posts->currentPage() }}" role="button">
                        {{ config('medicine.title') }}
                    </a>
                </div>

                <div class="panel-body">
                    <!-- <h1>{{ config('medicine.title') }}</h1>
                    <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5> -->
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/add" role="button">新增方剂</a>
                    <button class="btn btn-primary" onclick="calculate()">更新相似度</button>
                    <script>
                        //统一的向后台提交的处理
                        function calculate(){
                            $.post("{{ config('medicine.base_url') }}/calculate", {'_token':'{{csrf_token()}}'}, function(res){
                                // res = $.parseJSON(res);
                                if (res == '0') {
                                    alert('提交出错，请重新编辑');
                                }else {
                                    setTimeout(location.reload(),'800');
                                }
                            });
                        }
                    </script>
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
                    {!! $posts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
