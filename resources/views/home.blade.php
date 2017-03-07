@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">&nbsp;</div>
                <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation" class="active"><a href="#">首页</a></li>
                        <li role="presentation"><a href="/list">方剂列表</a></li>
                        <li role="presentation"><a href="/medicines">药剂列表</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="#" role="button">
                        Dash
                    </a>
                </div>

                <div class="panel-body">
                    功能完善中...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
