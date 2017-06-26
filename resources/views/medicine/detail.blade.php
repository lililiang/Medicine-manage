@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicine.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/medicineDetail/{{ $medicine['mm_id'] }}" role="button">
                        {{ $medicine['medicine_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>药剂: {{ $medicine['medicine_name'] }}</h5>
                    <hr>
                    <h5>标准条文：</h5>
                    @if (!empty($medicine['source']))
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th scope="row">名称</th>
                                    <td>{{ $medicine['source']['name'] }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">别名</th>
                                    <td>{{ $medicine['source']['alias'] }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">归经</th>
                                    <td>{{ $medicine['source']['direct'] }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">功能</th>
                                    <td>{{ $medicine['source']['func'] }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">性味</th>
                                    <td>{{ $medicine['source']['prop'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <a class="btn btn-default js-delete" href="javascript:void(0)" role="button" onclick="deleteRelation({{ $medicine['mm_id'] }})">删除对应关系</a>
                    @endif

                    <h3>相关方剂</h3>
                    @if (!empty($medicine['related_anagraphs']))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>方剂</th>
                                    <th>来源</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicine['related_anagraphs'] as $key => $one_ana)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <a class="btn btn-link" href="{{ config('medicine.base_url') }}/detail/{{ $one_ana['ma_id'] }}" role="button">
                                                {{ $one_ana['anagraph_name'] }}
                                            </a>
                                        </td>
                                        <td>{{ $one_ana['anagraph_origin'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/medicines?page={{ $medicine['page_index'] }}" role="button">« 返回</a>
                </div>
                <script>
                    function deleteRelation(mm_id){
                        //全局的增加和删除的绑定
                        $('.js-delete').on('click', function(e){
                            e.preventDefault();

                            $.post("{{ config('medicine.base_url') }}/deleteMedSource", {'_token':'{{csrf_token()}}', 'mm_id':mm_id}, function(res){
                                if (res == '0') {
                                    alert('提交出错，请重新编辑');
                                }else {
                                    setTimeout(location.reload(),'800');
                                }
                            });
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
