@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('tag.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/tagDetail/{{ $tag['mt_id'] }}" role="button">
                        {{ $tag['name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>标签: {{ $tag['name'] }}</h5>
                    <h5>标签类型: {{ $tag['type'] }}</h5>
                    <h5>标签描述: {{ $tag['desc'] }}</h5>
                    <h5>最后修改于 : {{ $tag['modify_time'] }}</h5>
                    <hr>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/tags?page={{ $tag['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
