<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Base
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Base newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Base newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Base query()
 * @mixin \Eloquent
 */
class Base extends Model
{

    // 设置时间戳字段
    public $timestamps = true;
    // 设置时间保存为时间戳
    public $dateFormat = 'Y-m-d H:i:s';
    // 过滤黑名单字段
    public $guarded = [];

    /**
     * 为数组 / JSON序列化准备一个日期
     * @param DateTimeInterface $date
     * @return int|string
     * @author: iszmxw <mail@54zm.com>
     * @Date：2021/10/6 18:19
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        if ($date->getTimestamp() == 0) return null;
        return Carbon::instance($date)->format('Y-m-d H:i:s');
    }

    // 获取单字段数据
    public static function getValue($where, $value)
    {
        return self::where($where)->value($value);
    }

    // 获取单组数据
    public static function getOne($where = [], $field = [], $orderby = "id", $sort = 'DESC')
    {
        // 默认获取全部字段
        if (empty($field)) {
            $field = "*";
        }
        $res = self::select($field)->where($where)->orderBy($orderby, $sort)->first();
        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }

    // 获取列表数据
    public static function getList($where = [], $field = [], $offset = 0, $limit = 0, $orderby = "", $sort = 'DESC')
    {
        if (empty($field)) {
            $field = '*';
        }
        $model = self::select($field);
        if (!empty($where)) {
            $model = $model->where($where);
        }
        if (!empty($orderby)) {
            $model = $model->orderBy($orderby, $sort);
        }
        if (!empty($offset)) {
            $model = $model->offset($offset);
        }
        if (!empty($limit)) {
            $model = $model->limit($limit);
        }
        $res = $model->get();

        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }


    //分页获取数据
    public static function getPaginate($where = [], $field = [], $paginate = 1, $orderby = "", $sort = "DESC")
    {
        if (empty($field)) {
            $field = '*';
        }
        $model = self::select($field);
        if (!empty($where)) {
            $model = $model->where($where);
        }
        if (!empty($orderby)) {
            $model = $model->orderBy($orderby, $sort);
        }
        if (is_array($paginate)) {
            // 自定义分页
            $res = $model->paginate($paginate['limit'], $paginate['page']);
        } else {
            // 默认分页
            $res = $model->paginate($paginate);
        }
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    // 添加数据
    public static function AddData($data = [], $where = [])
    {
        if (!empty($where)) {
            $res = self::where($where)->first();
            if (empty($res)) {
                $res = self::create($data);
            }
        } else {
            $res = self::create($data);
        }

        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }

    // 编辑数据
    public static function EditData($where = [], $data = [])
    {
        $res = self::where($where)->update($data);

        if (!empty($res)) {
            return self::getOne($where);
        } else {
            return false;
        }
    }

    //删除数据
    public static function selected_delete($where)
    {
        $res = self::where($where)->delete();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    // 获取单列数据
    public static function getPluck($where = [], $field = "id")
    {
        $res = self::where($where)->pluck($field);
        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }

    // 查询该数据是否存在
    public static function checkRowExists($where = [], $field = "id")
    {
        // 添加withTrashed（软删除查询）包含已经软删除的数据
        $res = self::withTrashed()->where($where)->value($field);
        if (isset($res)) {
            return true;
        } else {
            return false;
        }
    }

    // 求总数
    public static function getCount($where = [])
    {
        return self::where($where)->count();
    }

    // 求和
    public static function getSum($where = [], $field = "id")
    {
        $res = self::where($where)->sum($field);
        return $res;
    }

    // 求最大值
    public static function getMax($where = [], $field = "id")
    {
        $res = self::where($where)->max($field);
        return $res;
    }

    // 求最小值
    public static function getMin($where = [], $field = "id")
    {
        $res = self::where($where)->min($field);
        return $res;
    }

    // 求平均值
    public static function getAvg($where = [], $field = "id")
    {
        $res = self::where($where)->avg($field);
        return $res;
    }
}
