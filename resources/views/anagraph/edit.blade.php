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
                    <a class="btn btn-link" href="/detail/{{ $anagraph['ma_id'] }}" role="button">
                        {{ $anagraph['anagraph_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <form class="pure-form pure-form-aligned js-slidetitlebanners">
                        <input name="ma_id" type="hidden" class="form-control" id="anagraphId" value="{{ $anagraph['ma_id'] }}" />
                        <div class="form-group">
                            <label for="anagraph_name" class="col-sm-2 control-label">药方:</label>
                            <div class="col-sm-10">
                                <input name="anagraph_name" type="text" class="form-control" id="anagraphName" autocomplete="off" value="{{ $anagraph['anagraph_name'] }}" placeholder="药方">
                            </div>
                        </div>
                        <div class="ml5 mt10 form-group">
                            <label for="anagraph_origin" class="col-sm-2 control-label">药方来源:</label>
                            <div class="col-sm-10">
                                <input name="anagraph_origin" type="text" class="form-control" id="anagraphOrigin" autocomplete="off" value="{{ $anagraph['anagraph_origin'] }}" placeholder="药方来源">
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>药名</th>
                                        <th>剂量</th>
                                        <th>用法</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anagraph['consist'] as $key => $post)
                                        <tr class="js-slide_one_block">
                                            <th scope="row">
                                                <input name="medicines[{{ $key }}][mac_id]" type="hidden" class="form-control" value="{{ $post['mac_id'] }}" />
                                                <input name="medicines[{{ $key }}][mm_id]" type="hidden" class="form-control" value="{{ $post['mm_id'] }}" />
                                            </th>
                                            <td><input name="medicines[{{ $key }}][name]" class="" type="text" autocomplete="off" value="{{ $post['medicine_name'] }}" /></td>
                                            <td><input name="medicines[{{ $key }}][dosage]" class="" type="text" autocomplete="off" value="{{ $post['dosage'] }}" /></td>
                                            <td><input name="medicines[{{ $key }}][usage]" class="" type="text" autocomplete="off" value="{{ $post['usage'] }}" /></td>
                                            <td><button class="btn btn-danger js-del_slide" onclick="">删除</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success js-add_slide" type="button" value="增加新药" />
                            <input class="btn btn-primary" type="submit" value="保存" />
                        </div>
                    </form>

                    <script>
                        $(function() {
                            bindslides();
                        });

                        function bindslides(){
                            //全局的增加和删除的绑定
                            $('.js-add_slide').on('click', function(e){
                                e.preventDefault();
                                var block = $(this).parents('form').find('.js-slide_one_block');
                                var tpl_dom = block.first().clone(true);
                                //数据置空
                                tpl_dom.find('input[type=text]').val('');
                                //更新index相关
                                tpl_dom.find('input[type=text]').each(function(){
                                    $(this).attr('name',$(this).attr('name').replace('[0]','['+block.length+']'));
                                });
                                tpl_dom.insertAfter(block.last());
                            });
                            $('.js-del_slide').on('click',function(e){
                                e.preventDefault();

                                if ($(this).parents('form').find('.js-slide_one_block').length > 1){
                                    $(this).parent().parent().remove();
                                }else{
                                    alert("至少要有一条数据!");
                                }
                            });
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
                            $.post("/doedit", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                                // res = $.parseJSON(res);
                                if (res == '0') {
                                    alert('提交出错，请重新编辑');
                                }else {
                                    setTimeout(self.location=document.referrer,'800');
                                }
                            });
                        }
                    </script>
                    <script type="text/javascript" src="//dn-staticfile.qbox.me/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
