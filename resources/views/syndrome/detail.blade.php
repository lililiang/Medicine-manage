@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('syndrome.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/syndromeDetail/{{ $syndrome['mts_id'] }}" role="button">
                        {{ $syndrome['syndrome_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <h5>症状: {{ $syndrome['syndrome_name'] }}</h5>
                    <h5>症状描述: {{ $syndrome['syndrome_desc'] }}</h5>
                    <h5>最后修改于 : {{ $syndrome['modify_time'] }}</h5>
                    <hr>

                    @if (!empty($syndrome['alias']))
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
                                        @foreach ($syndrome['alias'] as $key => $post)
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>{{ $post['syndrome_alias'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="panel-footer">
                    <a class="btn btn-default" href="{{ config('medicine.base_url') }}/editSyndromeAlias/{{ $syndrome['mts_id'] }}" role="button">编辑</a>
                    <a class="btn btn-primary" href="{{ config('medicine.base_url') }}/syndromes?page={{ $syndrome['page_index'] }}" role="button">« 返回</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
