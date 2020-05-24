<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\mall\api;

use app\mall\model\MallCategoryModel;
use think\db\Query;

class CategoryApi
{
    /**
     * 分类列表 用于模板设计
     * @param array $param
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function index($param = [])
    {
        $mallCategoryModel = new MallCategoryModel();

        $where = function (Query $query) use ($param) {
            if (!empty($param['keyword'])) {
                $query->where('name', 'like', "%{$param['keyword']}%");
            }
        };

        //返回的数据必须是数据集或数组,item里必须包括id,name,如果想表示层级关系请加上 parent_id
        return $mallCategoryModel->where('delete_time', 0)->where($where)->select();
    }

    /**
     * 分类列表 用于导航选择
     * @return array
     */
    public function nav()
    {
        $mallCategoryModel = new MallCategoryModel();

        $categories = $mallCategoryModel->where('delete_time', 0)->select();

        $return = [
            'rule'  => [
                'action' => 'mall/List/index',
                'param'  => [
                    'id' => 'id'
                ]
            ],//url规则
            'items' => $categories //每个子项item里必须包括id,name,如果想表示层级关系请加上 parent_id
        ];

        return $return;
    }

}