<?php

namespace Wsmallnews\Shopcore\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Wsmallnews\Shopcore\Models\ShopOrder;
use Wsmallnews\Shopcore\Models\ShopProduct;
use Wsmallnews\Shopcore\Models\ShopOrderItem;

class ShopOrdersController extends CommonController
{
    /**
     * Display a listing of the resource.
     * 订单列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $merch = $this->guard()->user();
        $shopOrders = ShopOrder::with('user', "orderItem")
                    ->where('merch_id', $merch->id);

        if($request->input('order_type') == "no_pay"){              //no_pay未付款和付款中订单
            $shopOrders = $shopOrders->whereIn('is_pay', [0, 1]);
        }else if($request->input('order_type') == "no_send"){       //no_send未发货订单
            $shopOrders = $shopOrders->where('is_pay', 2)
                                    ->where('is_send', 0);
        }else if($request->input('order_type') == "no_get"){        //no_get待收货订单
            $shopOrders = $shopOrders->where('is_pay', 2)
                                    ->where('is_send', 1)
                                    ->where('is_get', 0);
        }else if($request->input('order_type') == "is_finish"){        //is_finish已完成订单
            $shopOrders = $shopOrders->where('is_pay', 2)
                                    ->where('is_send', 1)
                                    ->where('is_get', 1);
        }

        if($request->has("name") && $request->input('name')){
            $user_ids = array_column(User::where("name", "like", "%".$request->input('name')."%")->get()->toArray(), 'id');
            $shopOrders = $shopOrders->whereIn("user_id", $user_ids);
        }

        if($request->has("type") && $request->input('type') != "all"){
            $shopOrders = $shopOrders->where("type", $request->input('type'));
        }

        if($request->has("pay_type") && $request->input('pay_type')!= "all"){
            $shopOrders = $shopOrders->where("pay_type", $request->input('pay_type'));
        }

        $created_at_start = $request->input('dateRange')[0];
        $created_at_end = $request->input('dateRange')[1];
        if(!empty($request->input('dateRange')) && ($created_at_start != "null" && $created_at_end != "null")  ){
            $shopOrders = $shopOrders->whereBetween('created_at', [$created_at_start, $created_at_end]);
        }

        $shopOrders = $shopOrders->paginate($request->input('page_size', 10));


        return response()->json([
            "error" => 0,
            "info" => "订单列表查询成功",
            "result" => $shopOrders
        ]);
    }


    /**
     * Display the specified resource.
     * 商户端订单详情
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $merch = $this->guard()->user();
        $shopOrder = ShopOrder::with('user', "orderItem")
                    ->where('merch_id', $merch->id)
                    ->findOrFail($id);

        $order_product_num = 0;
        $reduce_total = 0;
        foreach ($shopOrder->orderItem as $key => $item) {
            $reduce_fee = $item->origin_price * $item->product_num;
            $reduce_total += $reduce_fee;
            $order_product_num += $item->product_num;
        }

        $data['reduce_total'] = $reduce_total;
        $data['order_product_num'] = $order_product_num;

        return response()->json([
            "error" => 0,
            "info" => "订单信息查询成功",
            "result" => $shopOrder,
            "data" => $data
        ]);
    }


}
