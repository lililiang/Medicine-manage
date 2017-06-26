@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('prescriptiondatasource.navbar')
        @include('prescriptiondatasource.searchbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button class="btn btn-success" onclick="exportFile()">保存</button>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/download">导出数据</a>
                    <span class="pull-right"> <strong> 搜索结果: </strong> <em> {{ count($presources->toArray()) }} </em> 条记录</span>
                </div>
                <div class="panel-body">
                    <input name="keyword" type="hidden" class="form-control data-keyword" value="{{ $keyword }}" />
                    <input name="search_type" type="hidden" class="form-control data-type" value="{{ $search_type }}" />
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>组成</th>
                                <th>来源</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($presources as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/prescriptionDetail/{{ $post->mp_id }}">
                                            {{ $post->name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->components, 40) }}</td>
                                    <td>{{ str_limit($post->origin, 30) }}</td>
                                </li>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <a class="btn btn-primary btn" href="{{ config('medicine.base_url') }}/prescriptions" role="button">返回</a>
                </div>
            </div>
            <script>
                //统一的向后台提交的处理
                function exportFile(){
                    var keyword = $('.data-keyword').val();
                    var type = $('.data-type').val();

                    $.post("{{ config('medicine.base_url') }}/search/exportFile", {'_token':'{{csrf_token()}}', 'keyword':keyword, 'search_type':type}, function(res){
                        // res = $.parseJSON(res);
                        // if (res == '0') {
                        //     alert('提交出错，请重新编辑');
                        // }else {
                        //     setTimeout(location.reload(),'800');
                        // }
                    });
                }
            </script>
        </div>
    </div>
</div>
@endsection
