@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/detail/{{ $anagraph['ma_id'] }}" role="button">
                        {{ $anagraph['anagraph_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>方剂: {{ $anagraph['anagraph_name'] }}</h5>
                    <h5>来源: {{ $anagraph['anagraph_origin'] }}</h5>
                    <h5>最后修改于 : {{ $anagraph['modify_time'] }}</h5>
                    <hr>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>药名</th>
                                <th>古代剂量</th>
                                <th>现代标准剂量(g)</th>
                                <th>用法</th>
                                <th>需要校对</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anagraph['consist'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $post['medicine_name'] }}</td>
                                    <td>{{ $post['dosage'] }}</td>
                                    <td>
                                        {{ $post['standard_dosage'] }}
                                        @if ($post['standard_dosage'] == 0)
                                            <span class="label label-warning">剂量缺失</span>
                                        @endif
                                    </td>
                                    <td>{{ $post['usage'] }}</td>
                                    <td>
                                        @if ($post['need_modify'])
                                            <label>
                                                <input type="checkbox" checked="checked" disabled="true">
                                            </label>
                                        @else
                                            <label>
                                                <input type="checkbox" disabled="true">
                                            </label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <h3>相似方剂</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>方剂</th>
                                <th>相似度</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anagraph['similarities'] as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a class="btn btn-link" href="{{ config('medicine.base_url') }}/detail/{{ $post['des_id'] }}" role="button">
                                            {{ $post['anagraph_name'] }}
                                        </a>
                                    </td>
                                    <td>{{ $post['similarity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/edit/{{ $anagraph['ma_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/list?page={{ $anagraph['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
