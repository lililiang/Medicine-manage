@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        <div class="col-md-8 col-md-offset-1">
            <form class="pure-form pure-form-aligned js-slidetitlebanners">
                <input name="ma_id" type="hidden" class="form-control" id="anagraphId" value="{{ $anagraph['ma_id'] }}" />
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>标签</strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            @foreach ($tags as $key => $tag_item)
                                <div class="checkbox">
                                    <label class="ml20">
                                        @if ($tag_item['checked'])
                                            <input type="checkbox" checked='checked' autocomplete="off" name="tags[{{ $key }}][mt_id]" value="{{ $tag_item['mt_id'] }}">
                                            {{ $tag_item['name'] }}
                                        @else
                                            <input type="checkbox" autocomplete="off" name="tags[{{ $key }}][mt_id]" value="{{ $tag_item['mt_id'] }}">
                                            {{ $tag_item['name'] }}
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="更改" />
                        </div>
                    </div>
                </div>
            </form>

            <script>
                $(function() {
                    bindslides();
                });

                function bindslides(){
                    //全局的增加和删除的绑定
                    $('.js-slidetitlebanners').submit(function(e){
                        e.preventDefault();

                        var data = $(this).serializeObject();
                        // if (data.length <= 0){
                        //     //具体的判断放在后台处理
                        //     ui.error("请输入至少一条数据");
                        //     return ;
                        // }
                        doSaveData(data);
                    });
                }
                //统一的向后台提交的处理
                function doSaveData(data){
                    $.post("{{ config('medicine.base_url') }}/anaTagAdd", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                        res = $.parseJSON(res);
                        if (res == '0') {
                            alert('提交出错，请重新编辑');
                        }else {
                            setTimeout(location.reload(),'800');
                        }
                    });
                }
            </script>
            <script type="text/javascript" src="//dn-staticfile.qbox.me/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/detail/{{ $anagraph['ma_id'] }}" role="button">
                        {{ $anagraph['anagraph_name'] }}
                    </a>
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/edit/{{ $anagraph['ma_id'] }}" role="button">编辑</a>
                    @if ($prev_next['prev'])
                        <a class="btn btn-default" href="{{ config('medicine.base_url') }}/detail/{{ $prev_next['prev'] }}" role="button">上一页</a>
                    @endif
                    @if ($prev_next['next'])
                        <a class="btn btn-default" href="{{ config('medicine.base_url') }}/detail/{{ $prev_next['next'] }}" role="button">下一页</a>
                    @endif
                </div>

                <div class="panel-body">
                    <h5><strong>方剂:</strong> {{ $anagraph['anagraph_name'] }}</h5>
                    <h5><strong>作者:</strong> {{ $anagraph['author'] }}</h5>
                    <h5>
                        <strong>来源:</strong>
                        @if ($anagraph['anagraph_origin'] != '')
                            {{ "《" . $anagraph['anagraph_origin'] . "》"}}
                        @endif
                    </h5>
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>功能</strong></div>
                        <div class="panel-body">
                            @if (!empty($anagraph['anagraph_source'][0]['effec']))
                                {{ $anagraph['anagraph_source'][0]['effec'] }}
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>主治</strong></div>
                        <div class="panel-body">
                            @if (!empty($anagraph['anagraph_source'][0]['indications']))
                                {{ $anagraph['anagraph_source'][0]['indications'] }}
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>用法</strong></div>
                        <div class="panel-body">{{ $anagraph['usage'] }}</div>
                    </div>
                    <h5>
                        <strong>引用:</strong>
                        @if ($anagraph['inference'] != '')
                            {{ "《" . $anagraph['inference'] . "》"}}
                        @endif
                    </h5>
                    <h5><strong>最后修改于:</strong> {{ $anagraph['modify_time'] }}</h5>
                    <hr>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>药名</th>
                                <th>古代剂量</th>
                                <th>现代标准剂量(g)</th>
                                <th>用法</th>
                                <th>需要校对</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anagraph['consist'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/medicineDetail/{{ $post['mm_id'] }}">
                                            {{ $post['medicine_name'] }}
                                            @if ($post['is_missing'] == 1)
                                                <span class="label label-danger">药物缺失</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ $post['dosage'] }}</td>
                                    <td>
                                        {{ $post['standard_dosage'] }}
                                        @if ($post['standard_dosage'] == 0)
                                            <span class="label label-warning">剂量缺失</span>
                                        @endif
                                    </td>
                                    <td>{{ $post['usage'] }}</td>
                                    <td>
                                        @if ($post['need_modify'])
                                            <label>
                                                <input type="checkbox" checked="checked" disabled="true">
                                            </label>
                                        @else
                                            <label>
                                                <input type="checkbox" disabled="true">
                                            </label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    @if (!empty($anagraph['anagraph_source']))
                        <h3>方剂源数据</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>方剂</th>
                                    <th>组成</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anagraph['anagraph_source'] as $key => $one_source_ana)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <a class="btn btn-link" href="{{ config('medicine.base_url') }}/prescriptionDetail/{{ $one_source_ana['mp_id'] }}" role="button">
                                                {{ $one_source_ana['name'] }}
                                            </a>
                                        </td>
                                        <td>{{ $one_source_ana['components'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-maid="{{ $one_source_ana['masr_id'] }}">删除</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
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

                                    $.post("{{ config('medicine.base_url') }}/delete", {'_token':'{{csrf_token()}}', 'masr_id':maId}, function(res){
                                        res = $.parseJSON(res);
                                        if (res == '0') {
                                            alert('提交出错，请重新编辑');
                                        }else {
                                            setTimeout(location.reload(),'800');
                                        }
                                    });
                                }
                            </script>
                        </table>
                        <hr>
                    @endif
                    <h3>相似方剂</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>方剂</th>
                                <th>相似度</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anagraph['similarities'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a class="btn btn-link" href="{{ config('medicine.base_url') }}/detail/{{ $post['des_id'] }}" role="button">
                                            {{ $post['anagraph_name'] }}
                                        </a>
                                    </td>
                                    <td>{{ $post['similarity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/edit/{{ $anagraph['ma_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/list?page={{ $anagraph['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
