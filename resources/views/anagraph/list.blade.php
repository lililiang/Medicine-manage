@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        @include('layouts.search')
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
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/import" role="button">批量导入</a>
                    <!-- <button class="btn btn-primary" onclick="calculate()">更新相似度</button> -->

                    <!-- @foreach ($sources as $source)
                        <a class="btn btn-danger" href="{{ config('medicine.base_url') }}/deleteSource?source={{ $source }}" role="button">删除：{{ $source }}</a>
                    @endforeach -->
                    <hr>
                    {!! $posts->render() !!}
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
                                <th>操作</th>
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
                                        @if ($post->need_find)
                                            <span class="label label-warning">药物数据缺失</span>
                                        @endif
                                        @if ($post->need_modify)
                                            <span class="label label-danger">方剂数据出错</span>
                                        @endif
                                        @if ($post->need_source)
                                            <span class="label label-success">方剂大词典无此记录</span>
                                        @endif
                                    </td>
                                    <td>{{ str_limit($post->anagraph_origin) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-maid="{{ $post->ma_id }}">删除</button>
                                    </td>
                                </li>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- 模态框（Modal） -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">提示框</h4>
                          </div>
                          <div class="modal-body">
                            <p>是否确定删除？</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-danger js-del_syn" data-maid="" onclick="deleteAnagraph()">确认删除</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                        $('#exampleModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var maId = button.data('maid');

                            $('.js-del_syn').attr("data-maid", maId);
                        })

                        function deleteAnagraph() {
                            var maId = $('.js-del_syn').data('maid')

                            $.post("{{ config('medicine.base_url') }}/delete", {'_token':'{{csrf_token()}}', 'ma_id':maId}, function(res){
                                res = $.parseJSON(res);
                                if (res == '0') {
                                    alert('提交出错，请重新编辑');
                                }else {
                                    setTimeout(location.reload(),'800');
                                }
                            });
                        }
                    </script>
                    <hr>
                    {!! $posts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
