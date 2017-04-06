@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('disease.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/diseaseDetail/{{ $disease['md_id'] }}" role="button">
                        {{ $disease['disease_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>病症: {{ $disease['disease_name'] }}</h5>
                    <h5>病症描述: {{ $disease['disease_desc'] }}</h5>
                    <h5>最后修改于 : {{ $disease['modify_time'] }}</h5>
                    <hr>

                    @if (!empty($disease['alias']))
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">症状别名</div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>别名</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disease['alias'] as $key => $post)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>{{ $post['disease_alias'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="panel-footer">
                                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/editDiseaseAlias/{{ $disease['md_id'] }}" role="button">编辑</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($disease['syndromes']))
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">对应证候</div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>证候名称</th>
                                            <th>证候描述</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disease['syndromes'] as $key => $one_syndrome)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>{{ $one_syndrome['syndrome_name'] }}</td>
                                            <td>{{ $one_syndrome['syndrome_desc'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="panel-footer">
                                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/editDiseaseSyndromes/{{ $disease['md_id'] }}" role="button">编辑</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="panel-footer">
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/diseases?page={{ $disease['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
