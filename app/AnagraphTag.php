<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;

class AnagraphTag extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $table = 'anagraph_tag';
    protected $primaryKey = 'mat_id';

    protected $fillable = [
        'mt_id',
        'ma_id'
    ];

    public function getAnagraphTags(int $ma_id) {
        $ana_tags = $this->where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->get();
        if (!$ana_tags) {
            return [];
        }

        $ana_tags = $ana_tags->toArray();

        $arr_mt_ids = [];
        foreach ($ana_tags as $ana_tag_item) {
            if (isset($ana_tag_item['mt_id'])) {
                $arr_mt_ids[intval($ana_tag_item['mt_id'])] = 1;
            }
        }

        $arr_mt_ids = array_keys($arr_mt_ids);

        $obj_tag = new Tag;
        $tags = $obj_tag->getTagsByType('anagraph');
        if (!$tags) {
            return [];
        }

        foreach ($tags as &$tag_item) {
            if (in_array(intval($tag_item['mt_id']), $arr_mt_ids)) {
                $tag_item['checked'] = true;
            } else {
                $tag_item['checked'] = false;
            }
        }

        return $tags;
    }

    public function addAnagraphTags(int $ma_id, array $mt_ids) {
        // 删除之前的关联
        $this->deleteAnagraphTags($ma_id);

        if (!empty($mt_ids)) {
            foreach ($mt_ids as $mt_id) {
                $add_data = [];

                $add_data['ma_id'] = $ma_id;
                $add_data['mt_id'] = $mt_id;

                $this->create($add_data);
            }
        }
    }

    public function deleteAnagraphTags(int $ma_id) {
        $this->where('ma_id', '=', $ma_id)
            ->where('is_del', '=', 0)
            ->update([
                'is_del' => 1
            ]);
    }

    public function deleteTag(int $mt_id) {
        $this->where('mt_id', '=', $mt_id)
            ->where('is_del', '=', 0)
            ->update([
                'is_del' => 1
            ]);
    }
}
