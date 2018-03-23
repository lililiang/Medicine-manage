<div class="col-md-2">
    <div class="panel panel-default">
        <div class="panel-heading">&nbsp;</div>
        <div class="panel-body">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/home">首页</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/list">方剂管理</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/medicines">药剂管理</a></li>
                <li role="presentation" class="active">
                    <!-- <a href="{{ config('medicine.base_url') }}/diseases"> -->
                    <a data-toggle="collapse" href="#collapseDiseases" aria-expanded="false" aria-controls="collapseDiseases">
                        症状管理
                    </a>
                    <div id="collapseDiseases" class="collapse in">
                        <div class="panel-body well">
                            <ul class="nav nav-links nav-stacked">
                                <li id="diseasesList" role="presentation">
                                    <a href="{{ config('medicine.base_url') }}/diseases">症状列表</a>
                                </li>
                                <li id="diseasesNew" role="presentation">
                                    <a href="{{ config('medicine.base_url') }}/addDisease">新增症状</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/syndromes">证候管理</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/prescriptions">中医方剂大词典八万记录</a></li>
                <li role="presentation"><a href="{{ config('medicine.base_url') }}/meddict">本草数据库</a></li>
            </ul>
        </div>
    </div>
</div>
