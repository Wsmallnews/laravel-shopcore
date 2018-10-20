<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Wsmallnews\Shopcore\Models\SmCategory as Category;
use Wsmallnews\Shopcore\Http\Requests\SmCategoryRequest;
use Wsmallnews\Shopcore\Events\OperateLogEvent;

class SmCategorysController extends CommonController
{
    /**
     * 产品分类列表.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $categorys = Category::get()->toTree()->toArray();

        return response()->json([
            'error' => 0,
            'info' => '获取成功',
            'result' => $categorys,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'error' => 0,
            'info' => '获取成功',
            'result' => $category,
        ]);
    }

    public function store(SmCategoryRequest $request)
    {
        $parent_ids = $request->input('parent_id');
        $parent_id = empty($parent_ids) ? null : $parent_ids[count($parent_ids) - 1];

        $category = new Category();
        $category->name = $request->input('name');
        $category->parent_id = $parent_id;
        $category->icon = $request->input('icon');
        $category->sort_order = $request->input('sort_order');
        $category->save();

        $data = array(
            'type' => 'admin',
            'log_info' => '添加产品分类:'.$category->name,
        );
        \Event::fire(new OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '保存成功',
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 商家信息修改.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SmCategoryRequest $request, $id)
    {
        $parent_ids = $request->input('parent_id');
        $parent_id = empty($parent_ids) ? null : $parent_ids[count($parent_ids) - 1];

        $category = Category::findOrFail($id);

        $category->name = $request->input('name');
        $category->icon = $request->input('icon');
        $category->sort_order = $request->input('sort_order');

        if ($parent_id) {
            if ($parent_id != $category->parent_id) {
                $parent = Category::find($parent_id);
                $category->appendToNode($parent)->save();
            } else {
                $category->save();
            }
        } else {
            $category->makeRoot()->save();
        }

        $data = array(
            'type' => 'admin',
            'log_info' => '修改产品分类:'.$category->name,
        );
        \Event::fire(new OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '保存成功',
        ]);
    }

    public function destroy($id)
    {
        $category = Category::with('product', 'descendants')->findOrFail($id);

        if (!$category->product->isEmpty() || !$category->descendants->isEmpty()) {
            return response()->json([
                'error' => 2801,
                'info' => '分类下有子分类或产品，不可删除',
            ]);
        }

        $category_name = $category->name;
        $category->delete();

        $data = array(
            'type' => 'admin',
            'log_info' => '删除产品分类:'.$category_name,
        );
        \Event::fire(new OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '删除成功',
        ]);
    }
}
