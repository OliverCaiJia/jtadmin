<?php

namespace App\Http\Controllers\Admin\View\Datagrid;

use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Orm\SaasChannel;
use App\Models\Orm\UserOrder;
use Illuminate\Http\Request;

class ChannelController extends ViewController
{
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $createdAtFrom = $request->input('start') ?: date('Y:m:d'). ' 00:00:00';
        $createdAtTo = $request->input('end') ?: date('Y:m:d'). ' 23:59:59';
        $channelName = $request->input('channel_name') ?: '';

        //查询
        $query = UserOrder::when($channelName, function ($query) use ($channelName) {
            $channelId = SaasChannel::where('name', '=', $channelName)->value('id');
            return $query->where(['channel_id' => $channelId]);
        })->when($createdAtFrom, function ($query) use ($createdAtFrom) {
            return $query->where('created_at', '>=', $createdAtFrom);
        })->when($createdAtTo, function ($query) use ($createdAtTo) {
            return $query->where('created_at', '<=', $createdAtTo);
        });

        $sum = $query->sum('order_price');
        $total = $query->count();
        $requests['orderField'] = $request->input('orderField') ?: 'id';
        $items = $this->getResults($query, $requests);
        $pageSize = $requests['pageSize'];
        $pageCurrent = $requests['pageCurrent'];

        return view('admin_modules.datagrid.channel.index', compact(
            'sum',
            'items',
            'total',
            'pageSize',
            'pageCurrent',
            'channelName'
        ));
    }
}
