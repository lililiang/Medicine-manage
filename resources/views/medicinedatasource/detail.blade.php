@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('medicinedatasource.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/medDetail/{{ $medicine['mmds_id'] }}" role="button">
                        {{ $medicine['name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>药剂: {{ $medicine['name'] }}</h5>
                    <hr>

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">名称</th>
                                <td>{{ $medicine['name'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">别名</th>
                                <td>{{ $medicine['alias'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">归经</th>
                                <td>{{ $medicine['direct'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">功能</th>
                                <td>{{ $medicine['func'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">性味</th>
                                <td>{{ $medicine['prop'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/edit/{{ $medicine['mmds_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/meddict?page={{ $medicine['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
