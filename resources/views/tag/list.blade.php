@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('tag.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/tags?page={{ $posts->currentPage() }}" role="button">
                        标签管理
                    </a>
                </div>

                <div class="panel-body">
                    <!-- <h1>{{ config('medicine.title') }}</h1>
                    <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5> -->
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/addTag" role="button">新增标签</a>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>标签名称</th>
                                <th>标签类型</th>
                                <th>标签描述</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/tagDetail/{{ $post->mt_id }}">
                                            {{ $post->name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->type) }}</td>
                                    <td>{{ str_limit($post->desc) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-mtid="{{ $post->mt_id }}">删除</button>
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
                            <button type="button" class="btn btn-danger js-del_syn" data-mtid="" onclick="deleteTag()">确认删除</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                        $('#exampleModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var mtId = button.data('mtid');

                            $('.js-del_syn').attr("data-mtid", mtId);
                        })

                        function deleteTag() {
                            var mtId = $('.js-del_syn').data('mtid')
                            $.post("{{ config('medicine.base_url') }}/deleteTag", {'_token':'{{csrf_token()}}', 'mt_id':mtId}, function(res){
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
