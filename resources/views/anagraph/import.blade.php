@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('anagraph.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="#" role="button">
                        批量导入方剂
                    </a>
                </div>

                <div class="panel-body">
                    <form class="pure-form pure-form-aligned" method="POST" enctype="multipart/form-data" action="{{ config('medicine.base_url') }}/uploadAnagraphs">
                        <div class="form-group">
                            <label for="exampleInputFile">上传文件</label>
                            <input type="file" id="exampleInputFile" name="import_file">
                            <p class="help-block">点击上传开始上传</p>
                        </div>
                        <div class="ml5 mt10 form-group">
                            <label for="anagraph_origin" class="col-sm-2 control-label">药方来源:</label>
                            <div class="col-sm-10">
                                <input name="anagraph_origin" type="text" class="form-control" id="anagraphOrigin" autocomplete="off" value="" placeholder="药方来源">
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="_token" type="hidden" class="form-control" value="{{ csrf_token() }}">
                            <input class="btn btn-primary" type="submit" value="提交" />
                        </div>
                    </form>
                    <script type="text/javascript" src="//dn-staticfile.qbox.me/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
