<div class="col-md-2">
    <div class="panel panel-default">
        <div class="panel-heading">&nbsp;</div>
        <div class="panel-body">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/home">首页</a></li>
                <li role="presentation" class="active">
                    <a data-toggle="collapse" href="#collapseAnagraphs" aria-expanded="false" aria-controls="collapseAnagraphs">
                        方剂管理
                    </a>
                    <div id="collapseAnagraphs" class="collapse in">
                        <div class="panel-body well">
                            <ul class="nav nav-links nav-stacked">
                                <li id="anagraphList" role="presentation">
                                    <a href="{{ config('medicine.base_url') }}/list">方剂列表</a>
                                </li>
                                <li id="anagraphNew" role="presentation">
                                    <a href="{{ config('medicine.base_url') }}/add">新增方剂</a>
                                </li>
                                <li id="anagraphImport" role="presentation">
                                    <a href="{{ config('medicine.base_url') }}/import">导入方剂</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/medicines">药剂管理</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/diseases">症状管理</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/syndromes">证候管理</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/prescriptions">中医方剂大词典八万记录</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/meddict">本草数据库</a></li>
            </ul>
        </div>
    </div>
</div>
