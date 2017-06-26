@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicine.navbar')
        @include('layouts.search')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/medicines?page={{ $posts->currentPage() }}" role="button">
                        药剂管理
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>中药名称</th>
                                <th>标准名称</th>
                                <th>最后修改时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/medicineDetail/{{ $post->mm_id }}">
                                            {{ $post->medicine_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if (isset($post->mmds_id) && isset($post->standard_name))
                                            <a href="{{ config('medicine.base_url') }}/medDetail/{{ $post->mmds_id }}">
                                                {{ $post->standard_name }}
                                            </a>
                                        @endif
                                    </td>
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
