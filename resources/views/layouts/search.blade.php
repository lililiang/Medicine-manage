<div class="col-md-8 col-md-offset-1">
    <form method="post" class="navbar-form navbar-right" role="search" action="{{ config('medicine.base_url') }}/search/anagraph">
        <div class="form-group">
            <label for="name">搜索类型</label>
            <select class="form-control" name="search_type">
                <option value="anagraph">标准方剂</option>
                <option value="medicine">标准药物</option>
                <option value="disease">标准症状</option>
                <option value="medsource">药剂数据库</option>
                <option value="presource">方剂数据库</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" autocomplete="off" placeholder="方剂搜索" name="keyword" value="">
        </div>
        <input type="hidden" name='_token' value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>
