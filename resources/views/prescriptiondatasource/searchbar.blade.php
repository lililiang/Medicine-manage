<div class="col-md-8 col-md-offset-1">
    <form method="post" class="navbar-form navbar-right" role="search" action="{{ config('medicine.base_url') }}/search/presource">
        <div class="form-group">
            <label for="name">方剂搜索</label>
            <select class="form-control" name="search_type">
                @if (isset($search_type) && $search_type == 'name')
                    <option value="name" selected="selected">名称</option>
                @else
                    <option value="name">名称</option>
                @endif
                @if (isset($search_type) && $search_type == 'consist')
                    <option value="consist" selected="selected">成分</option>
                @else
                    <option value="consist">成分</option>
                @endif
                @if (isset($search_type) && $search_type == 'origin')
                    <option value="origin" selected="selected">来源</option>
                @else
                    <option value="origin">来源</option>
                @endif
            </select>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" autocomplete="off" placeholder="方剂搜索" name="keyword" value="<?php echo (isset($keyword)) ? $keyword : ''; ?>">
        </div>
        <input type="hidden" name='_token' value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>
