@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicine.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="" role="button">
                        搜索结果
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>中药名称</th>
                                <th>最后修改时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicines as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/medicineDetail/{{ $post->mm_id }}">
                                            {{ $post->medicine_name }}
                                        </a>
                                    </td>
                                    <td>{{ $post->modify_time }}</td>
                                </li>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <a class="btn btn-primary btn" href="{{ config('medicine.base_url') }}/medicines" role="button">返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
