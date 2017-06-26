@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicinedatasource.navbar')
        @include('layouts.search')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/meddict?page={{ $posts->currentPage() }}" role="button">
                        药剂数据库
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>功能</th>
                                <th>性味</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/medDetail/{{ $post->mmds_id }}">
                                            {{ $post->name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->func, 40) }}</td>
                                    <td>{{ str_limit($post->prop, 30) }}</td>
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
