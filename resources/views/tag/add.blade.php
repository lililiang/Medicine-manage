@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('tag.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="#" role="button">
                        新增标签
                    </a>
                </div>

                <div class="panel-body">
                    <form class="pure-form pure-form-aligned js-slidetitlebanners">
                        <div class="form-group">
                            <label for="tag_name" class="col-sm-2 control-label">标签名称:</label>
                            <div class="col-sm-10">
                                <input name="tag_name" type="text" class="form-control" id="tagName" autocomplete="off" value="" placeholder="标签名称">
                            </div>
                        </div>
                        <div class="ml5 mt10 form-group">
                            <label for="tag_desc" class="col-sm-2 control-label">标签描述:</label>
                            <div class="col-sm-10">
                                <input name="tag_desc" type="text" class="form-control" id="tagDesc" autocomplete="off" value="" placeholder="标签描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">标签类型</label>
                            <select class="form-control" name="tag_type">
                                <option value="anagraph">方剂</option>
                                <option value="medicine">药物</option>
                                <option value="disease">症状</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="保存" />
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
                            $.post("{{ config('medicine.base_url') }}/createTag", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                                res = $.parseJSON(res);
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
