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
use Wsmallnews\Shopcore\Http\Requests\ShopProductRequest;
use Wsmallnews\Shopcore\Models\ShopProduct;
use Wsmallnews\Shopcore\Models\ShopProductSpec;
use Wsmallnews\Shopcore\Models\ShopProductAttr;
use Wsmallnews\Shopcore\Models\ShopProductType;

class ShopProductsController extends CommonController
{
    /**
     * 产品列表.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $admin = $this->guard()->user();
        $name = $request->input('name', '');
        $category_id = $request->input('category_id', []);

        $products = ShopProduct::with('category');

        if (!empty($name)) {
            $products = $products->where('name', 'like', '%'.$name.'%');
        }

        if (!empty($category_id)) {
            $products = $products->where('category_id', $category_id[0]);
        }
        $products = $products->paginate($request->input('page_size', 10));

        return response()->json([
            'error' => 0,
            'info' => '获取成功',
            'result' => $products,
        ]);
    }

    // 获取所有产品
    public function all(Request $request)
    {
        $products = ShopProduct::get();

        return response()->json([
            'error' => 0,
            'info' => '产品列表',
            'result' => $products,
        ]);
    }

    /**
     * 产品回收站.
     *
     * @param [type] $id [description]
     *
     * @return [type] [description]
     */
    public function trashIndex(Request $request)
    {
        $name = $request->input('name', '');
        $category_id = $request->input('category_id', []);

        $products = ShopProduct::onlyTrashed()->with('category');

        if (!empty($name)) {
            $products = $products->where('name', 'like', '%'.$name.'%');
        }

        if (!empty($category_id)) {
            $products = $products->where('category_id', $category_id[0]);
        }

        $products = $products->paginate($request->input('page_size', 10));

        return response()->json([
            'error' => 0,
            'info' => '获取成功',
            'result' => $products,
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
        $product = ShopProduct::with('specItem', 'productAttr')->findOrFail($id);

        $spec_name = $product->getSpecName($product);

        return response()->json([
            'error' => 0,
            'info' => '获取成功',
            'result' => $product,
            'spec_name' => $spec_name,       // 产品编辑使用
        ]);
    }

    /**
     * 产品添加.
     *
     * @param ShopProductRequest $request [description]
     *
     * @return [type] [description]
     */
    public function store(ShopProductRequest $request)
    {
        \DB::transaction(function () use ($request) {
            $category_arr = $request->input('category_id', array());
            $category_id = 0;
            if (!empty($category_arr)) {
                $category_id = $category_arr[count($category_arr) - 1];
            }

            $start_spec = $request->input('start_spec');

            $product = new ShopProduct();
            $product->name = $request->input('name');
            $product->category_id = $category_id;
            $product->type = $request->input('type');
            $product->is_virtual = $request->input('is_virtual');
            $product->images = json_encode($request->input('images'));
            $product->origin_price = $request->input('origin_price');
            $product->price = $request->input('price');
            $product->sale_num = 0;
            $product->stock = $request->input('stock');
            $product->desc = $request->input('desc');
            $product->content = json_encode($request->input('content'));
            $product->is_on_sale = $request->input('is_on_sale');
            $product->is_recommend = $request->input('is_recommend');
            $product->is_special = $request->input('is_special');
            $product->sort_order = $request->input('sort_order');
            $product->type_id = '' == $request->input('type_id') ? 0 : $request->input('type_id');
            $product->stock_time = $request->input('stock_time');
            $product->check_status = 1;         // 总后台产品默认不需要审核

            if ($start_spec) {
                $spec_name = $request->input('spec_only_name');
                $product->spec_name_one = isset($spec_name['spec_name_one']) ? $spec_name['spec_name_one'] : '';
                $product->spec_name_two = isset($spec_name['spec_name_two']) ? $spec_name['spec_name_two'] : '';
                $product->spec_name_three = isset($spec_name['spec_name_three']) ? $spec_name['spec_name_three'] : '';
            }
            $product->save();

            $product_id = $product->id;
            if ($start_spec) {
                $spec_item = $request->input('spec_item');
                foreach ($spec_item as $key => $value) {
                    $shopProductSpec = new ShopProductSpec();

                    $shopProductSpec->product_id = $product_id;
                    $shopProductSpec->spec_name_one = !empty($value['spec_name_one']) ? $value['spec_name_one'] : '';
                    $shopProductSpec->spec_name_two = !empty($value['spec_name_two']) ? $value['spec_name_two'] : '';
                    $shopProductSpec->spec_name_three = !empty($value['spec_name_three']) ? $value['spec_name_three'] : '';
                    $shopProductSpec->origin_price = $value['origin_price'];
                    $shopProductSpec->price = $value['price'];
                    $shopProductSpec->stock = $value['stock'];

                    $shopProductSpec->save();
                }
            }

            $type_attrs = $request->input('type_attrs');
            if (!empty($type_attrs)) {
                foreach ($type_attrs as $key => $attr) {
                    $shopProductAttr = new ShopProductAttr();
                    $shopProductAttr->product_id = $product_id;
                    $shopProductAttr->attr_id = $attr['id'];
                    $shopProductAttr->attr_name = $attr['name'];
                    $shopProductAttr->value = $attr['value'];

                    $shopProductAttr->save();
                }
            }
        });

        $data = array(
            'type' => 'admin',
            'log_info' => '添加产品:'.$request->input('name'),
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '保存成功',
        ]);
    }

    /**
     * 产品修改.
     *
     * @param int $id
     */
    public function update(ShopProductRequest $request, $id)
    {
        \DB::transaction(function () use ($id, $request) {
            $category_arr = $request->input('category_id');
            $category_id = $category_arr[count($category_arr) - 1];

            $start_spec = $request->input('start_spec');

            $product = ShopProduct::with('productAttr')->findOrFail($id);
            $product->name = $request->input('name');
            $product->category_id = $category_id;
            $product->type = $request->input('type');
            $product->is_virtual = $request->input('is_virtual');
            $product->images = json_encode($request->input('images'));
            $product->origin_price = $request->input('origin_price');
            $product->price = $request->input('price');
            $product->sale_num = 0;
            $product->stock = $request->input('stock');
            $product->desc = $request->input('desc');
            $product->content = json_encode($request->input('content'));
            $product->is_on_sale = $request->input('is_on_sale');
            $product->is_recommend = $request->input('is_recommend');
            $product->is_special = $request->input('is_special');
            $product->sort_order = $request->input('sort_order');
            $product->type_id = $request->input('type_id');
            $product->stock_time = $request->input('stock_time');
            $product->check_status = 1;         // 总后台产品默认不需要审核

            if ($start_spec) {
                $spec_name = $request->input('spec_only_name');
                $product->spec_name_one = isset($spec_name['spec_name_one']) ? $spec_name['spec_name_one'] : '';
                $product->spec_name_two = isset($spec_name['spec_name_two']) ? $spec_name['spec_name_two'] : '';
                $product->spec_name_three = isset($spec_name['spec_name_three']) ? $spec_name['spec_name_three'] : '';
            } else {
                $product->spec_name_one = '';
                $product->spec_name_two = '';
                $product->spec_name_three = '';
            }

            $product->save();

            $product_id = $product->id;
            if ($request->input('start_spec')) {
                $spec_item = $request->input('spec_item');

                if ($request->input('is_reset_spec')) {
                    // 删除之前规格项
                    ShopProductSpec::where('product_id', $product_id)->delete();
                }

                foreach ($spec_item as $key => $value) {
                    $id = isset($value['id']) ? $value['id'] : '';
                    $shopProductSpec = ShopProductSpec::where('id', $id)->where('product_id', $product_id)->first();
                    if (!$shopProductSpec) {
                        $shopProductSpec = new ShopProductSpec();
                    }

                    $shopProductSpec->product_id = $product_id;
                    $shopProductSpec->spec_name_one = !empty($value['spec_name_one']) ? $value['spec_name_one'] : '';
                    $shopProductSpec->spec_name_two = !empty($value['spec_name_two']) ? $value['spec_name_two'] : '';
                    $shopProductSpec->spec_name_three = !empty($value['spec_name_three']) ? $value['spec_name_three'] : '';
                    $shopProductSpec->origin_price = $value['origin_price'];
                    $shopProductSpec->price = $value['price'];
                    $shopProductSpec->stock = $value['stock'];

                    $shopProductSpec->save();
                }
            }

            if ($request->input('type_attrs')) {
                $type_attrs = $request->input('type_attrs');
                foreach ($type_attrs as $key => $attr) {
                    if (isset($attr['product_attr_id']) && $attr['product_attr_id']) {
                        $shopProductAttr = ShopProductAttr::where('id', $attr['product_attr_id'])
                                            ->where('attr_id', $attr['id'])
                                            ->where('product_id', $product_id)
                                            ->first();
                    } else {
                        $shopProductAttr = new ShopProductAttr();
                    }

                    $shopProductAttr->product_id = $product_id;
                    $shopProductAttr->attr_id = $attr['id'];
                    $shopProductAttr->attr_name = $attr['name'];
                    $shopProductAttr->value = $attr['value'];
                    $shopProductAttr->save();
                }

                $productType = ShopProductType::with('shopProductTypeAttr')->where('id', $product->type_id)->first();

                if ($productType && !$productType->shopProductTypeAttr->isEmpty()) {
                    $attrIds = array_column($productType->shopProductTypeAttr->toArray(), 'id');
                    ShopProductAttr::where('product_id', $product_id)->whereNotIn('attr_id', $attrIds)->delete();
                }
            }
        });

        $data = array(
            'type' => 'admin',
            'log_info' => '编辑产品:'.$request->input('name'),
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '保存成功',
        ]);
    }

    public function setOnSale(Request $request, $id)
    {
        $product = ShopProduct::findOrFail($id);

        $product->is_on_sale = $request->input('is_on_sale', 0);
        $product->save();

        $on_sale = 0 == $request->input('is_on_sale') ? '下架' : '上架';
        $data = array(
            'type' => 'admin',
            'log_info' => '设置产品:'.$product->name.$on_sale,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '操作成功',
        ]);
    }

    public function setRecommend(Request $request, $id)
    {
        $product = ShopProduct::findOrFail($id);
        $product->is_recommend = $request->input('is_recommend', 0);
        $product->save();

        $is_rec = 0 == $request->input('is_recommend') ? '不推荐' : '推荐';
        $data = array(
            'type' => 'admin',
            'log_info' => '设置产品:'.$product->name.$is_rec,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '操作成功',
        ]);
    }

    public function setSpecial(Request $request, $id)
    {
        $product = ShopProduct::findOrFail($id);
        $product->is_special = $request->input('is_special', 0);
        $product->save();

        $is_spec = 0 == $request->input('is_special') ? '取消特价' : '特价';
        $data = array(
            'type' => 'admin',
            'log_info' => '设置产品'.$product->name.$is_spec,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '操作成功',
        ]);
    }

    public function destroy($id)
    {
        $product = ShopProduct::findOrFail($id);

        $product_name = $product->name;
        $product->delete();

        $data = array(
            'type' => 'admin',
            'log_info' => '删除产品:'.$product_name,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '删除成功',
        ]);
    }

    public function restore($id)
    {
        $product = ShopProduct::onlyTrashed()->findOrFail($id);
        $product->restore();    // 恢复

        $data = array(
            'type' => 'admin',
            'log_info' => '恢复删除产品:'.$product->name,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '恢复成功',
        ]);
    }

    public function forceDelete($id)
    {
        $product = ShopProduct::onlyTrashed()->findOrFail($id);
        $product_name = $product->name;

        $product->forceDelete();    // 强制删除

        $data = array(
            'type' => 'admin',
            'log_info' => '强制删除产品'.$product_name,
        );
        \Event::fire(new \App\Events\OperateLogEvent($data));

        return response()->json([
            'error' => 0,
            'info' => '彻底删除成功',
        ]);
    }
}
