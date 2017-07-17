@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicine.navbar')
        @include('layouts.search')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/medicines?page={{ $posts->currentPage() }}" role="button">
                        药剂管理
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>中药名称</th>
                                <th>标准名称</th>
                                <th>最后修改时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/medicineDetail/{{ $post->mm_id }}">
                                            {{ $post->medicine_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if (isset($post->mmds_id) && isset($post->standard_name))
                                            <a href="{{ config('medicine.base_url') }}/medDetail/{{ $post->mmds_id }}">
                                                {{ $post->standard_name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $post->modify_time }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal" data-mmid="{{ $post->mm_id }}">散轶</button>
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
                            <p>是否确定设为散轶？</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-danger js-del_syn" data-mmid="" onclick="setMedicineMissed()">确定</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <script>
                        $('#exampleModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var mmId = button.data('mmid');

                            $('.js-del_syn').attr("data-mmid", mmId);
                        })

                        function setMedicineMissed() {
                            var mmId = $('.js-del_syn').data('mmid')

                            $.post("{{ config('medicine.base_url') }}/setMedMissed", {'_token':'{{csrf_token()}}', 'mm_id':mmId}, function(res){
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
