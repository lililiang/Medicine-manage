@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('disease.navbar')
        @include('layouts.search')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/diseases?page={{ $posts->currentPage() }}" role="button">
                        病症管理
                    </a>
                </div>

                <div class="panel-body">
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/addDisease" role="button">新增病症</a>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>症状名称</th>
                                <th>症状描述</th>
                                <th>证候归类</th>
                                <th>最后修改时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/diseaseDetail/{{ $post->md_id }}">
                                            {{ $post->disease_name }}
                                        </a>
                                    </td>
                                    <td>{{ $post->disease_desc }}</td>
                                    <td>{{ $post->syndromes }}</td>
                                    <td>{{ $post->modify_time }}</td>
                                </li>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    {!! $posts->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
