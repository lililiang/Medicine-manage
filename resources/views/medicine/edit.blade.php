<html>
    <head>
        <title>{{ $anagraph['anagraph_name'] }}</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.js"></script>
    </head>
    <body>
        <div class="container">
            <hr>
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
                            @foreach ($anagraph['consist'] as $post)
                                <tr class="js-slide_one_block">
                                    <th scope="row">*</th>
                                    <td><input name="medicines[{{ $post['mm_id'] }}][name]" class="" type="text" autocomplete="off" value="{{ $post['medicine_name'] }}" /></td>
                                    <td><input name="medicines[{{ $post['mm_id'] }}][dosage]" class="" type="text" autocomplete="off" value="{{ $post['dosage'] }}" /></td>
                                    <td><input name="medicines[{{ $post['mm_id'] }}][usage]" class="" type="text" autocomplete="off" value="{{ $post['usage'] }}" /></td>
                                    <td><button class="btn btn-danger js-del_slide" onclick="">删除</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <input class="btn btn-success js-add_slide" type="button" value="增加" />
                    <input class="btn btn-primary" type="submit" value="确定" />
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
                        res = $.parseJSON(res);
                        // if (res.status === 0) {
                        //     ui.error(res.info);
                        // }else {
                        //     ui.success(res.info);
                        //     setTimeout(refreshopage,'800');
                        // }
                    });
                }
            </script>
            <script type="text/javascript" src="//dn-staticfile.qbox.me/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>
        </div>
    </body>
</html>
