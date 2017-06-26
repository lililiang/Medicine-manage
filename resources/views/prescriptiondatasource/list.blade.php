@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('prescriptiondatasource.navbar')
        @include('prescriptiondatasource.searchbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/prescriptions?page={{ $posts->currentPage() }}" role="button">
                        方剂数据库
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>名称</th>
                                <th>组成</th>
                                <th>来源</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $key => $post)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        <a href="{{ config('medicine.base_url') }}/prescriptionDetail/{{ $post->mp_id }}">
                                            {{ $post->name }}
                                        </a>
                                    </td>
                                    <td>{{ str_limit($post->components, 40) }}</td>
                                    <td>{{ str_limit($post->origin, 30) }}</td>
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
