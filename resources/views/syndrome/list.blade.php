@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('syndrome.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/syndromes?page={{ $posts->currentPage() }}" role="button">
                        证候管理
                    </a>
                </div>

                <div class="panel-body">
                    <!-- <h1>{{ config('medicine.title') }}</h1>
                    <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5> -->
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/addSyndrome" role="button">新增证候</a>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>证候名称</th>
                                <th>证候描述</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/syndromeDetail/{{ $post->mts_id }}">
                                            {{ $post->syndrome_name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->syndrome_desc) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-mtsid="{{ $post->mts_id }}">删除</button>
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
                            <button type="button" class="btn btn-danger js-del_syn" data-mtsid="" onclick="deleteSyndrome()">确认删除</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                        $('#exampleModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var mtsId = button.data('mtsid');

                            $('.js-del_syn').attr("data-mtsid", mtsId);
                        })

                        function deleteSyndrome() {
                            var mtsId = $('.js-del_syn').data('mtsid')

                            $.post("{{ config('medicine.base_url') }}/deleteSyndrome", {'_token':'{{csrf_token()}}', 'mts_id':mtsId}, function(res){
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
