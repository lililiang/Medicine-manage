@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('prescriptiondatasource.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/prescriptionDetail/{{ $prescription['mp_id'] }}" role="button">
                        {{ $prescription['name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>方剂: {{ $prescription['name'] }}</h5>
                    <h5>来源: {{ $prescription['origin'] }}</h5>
                    <hr>

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">名称</th>
                                <td>{{ $prescription['name'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">别名</th>
                                <td>{{ $prescription['alias'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">组成</th>
                                <td>{{ $prescription['components'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">用法</th>
                                <td>{{ $prescription['dosage'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">禁忌</th>
                                <td>{{ $prescription['fobbiden'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">功能</th>
                                <td>{{ $prescription['effec'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">主治</th>
                                <td>{{ $prescription['indications'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">分析</th>
                                <td>{{ $prescription['analyze'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">来源</th>
                                <td>{{ $prescription['origin'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">备注</th>
                                <td>{{ $prescription['assist'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/edit/{{ $prescription['mp_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/prescriptions?page={{ $prescription['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
