@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('disease.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="#" role="button">
                        新增病症
                    </a>
                </div>

                <div class="panel-body">
                    <form class="pure-form pure-form-aligned js-slidetitlebanners">
                        <div class="form-group">
                            <label for="disease_name" class="col-sm-2 control-label">病症:</label>
                            <div class="col-sm-10">
                                <input name="disease_name" type="text" class="form-control" id="anagraphName" autocomplete="off" value="" placeholder="病症">
                            </div>
                        </div>
                        <div class="ml5 mt10 form-group">
                            <label for="disease_desc" class="col-sm-2 control-label">病症描述:</label>
                            <div class="col-sm-10">
                                <input name="disease_desc" type="text" class="form-control" id="anagraphOrigin" autocomplete="off" value="" placeholder="病症描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>病症别名</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="js-slide_alias_block">
                                        <th scope="row"></th>
                                        <td><input name="alias[][disease_alias]" class="" type="text" autocomplete="off" value="" /></td>
                                        <td><button class="btn btn-danger js-del_alias" onclick="">删除</button></td>
                                    </tr>
                                </tbody>
                            </table>
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
                                    <tr class="js-slide_syndrome_block">
                                        <th scope="row"></th>
                                        <td>
                                            <input name='syndrome[][name]' class="" type="text" autocomplete="off" value="" />
                                        </td>
                                        <td>
                                            <input name='syndrome[][desc]' class="" type="text" autocomplete="off" value="" />
                                        </td>
                                        <td>
                                            <button class="btn btn-danger js-del_syndrome" onclick="">删除</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success js-add_alias" type="button" value="增加别名" />
                            <input class="btn btn-success js-add_syndrome" type="button" value="增加证候" />
                            <input class="btn btn-primary" type="submit" value="保存" />
                        </div>
                    </form>

                    <script>
                        $(function() {
                            bindslides();
                        });

                        function bindslides(){
                            //全局的增加和删除的绑定
                            $('.js-add_alias').on('click', function(e){
                                e.preventDefault();
                                var block = $(this).parents('form').find('.js-slide_alias_block');
                                var tpl_dom = block.first().clone(true);
                                //数据置空
                                tpl_dom.find('input[type=text]').val('');
                                //更新index相关
                                tpl_dom.find('input[type=text]').each(function(){
                                    $(this).attr('name',$(this).attr('name').replace('[0]','['+block.length+']'));
                                });
                                tpl_dom.insertAfter(block.last());
                            });
                            $('.js-add_syndrome').on('click', function(e){
                                e.preventDefault();
                                var block = $(this).parents('form').find('.js-slide_syndrome_block');
                                var tpl_dom = block.first().clone(true);
                                //数据置空
                                tpl_dom.find('input[type=text]').val('');
                                //更新index相关
                                tpl_dom.find('input[type=text]').each(function(){
                                    $(this).attr('name',$(this).attr('name').replace('[0]','['+block.length+']'));
                                });
                                tpl_dom.insertAfter(block.last());
                            });
                            $('.js-del_alias').on('click',function(e){
                                e.preventDefault();

                                if ($(this).parents('form').find('.js-slide_alias_block').length > 1){
                                    $(this).parent().parent().remove();
                                }else{
                                    alert("至少要有一条数据!");
                                }
                            });
                            $('.js-del_syndrome').on('click',function(e){
                                e.preventDefault();

                                if ($(this).parents('form').find('.js-slide_syndrome_block').length > 1){
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
                            $.post("{{ config('medicine.base_url') }}/createDisease", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                                // res = $.parseJSON(res);
                                // if (res == '0') {
                                //     alert('提交出错，请重新编辑');
                                // }else {
                                //     setTimeout(self.location=document.referrer,'800');
                                // }
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
