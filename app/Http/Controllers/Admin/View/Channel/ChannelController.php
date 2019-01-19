<?php

namespace App\Http\Controllers\Admin\View\Channel;

use App\Constants\ChannelConstant;
use App\Events\OperationLogEvent;
use App\Helpers\AdminResponseFactory;
use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Chain\ChannelCreation\DoCreateChannelHandler;
use App\Models\Chain\ChannelDelete\DoDeleteChannelHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Models\Factory\Saas\User\UserFactory;
use App\Models\Orm\SaasChannel;
use Illuminate\Http\Request;

class ChannelController extends ViewController
{
    /**
     * 列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $channelNid = $request->input('channel_nid');
        $channelName = $request->input('name');

        // 查询Query
        $query = SaasChannel::when($channelNid, function ($query) use ($channelNid) {
            return $query->where('nid', 'like', '%' . $channelNid . '%');
        })->when($channelName, function ($query) use ($channelName) {
            return $query->where('name', 'like', '%' . $channelName . '%');
        });
        $query->where('is_deleted', '=', ChannelConstant::SAAS_CHANNEL_DELETED_FALSE);

        //结果集合
        $total = $query->count();
        $results = $this->getResults($query, $requests)->toArray();
        return view('admin_modules.channel.index', [
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 新增渠道
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function append(Request $request)
    {
        if ($this->isPostMethod($request)) {
            $requests = $this->getRequests($request);
            $channelHandle = new DoCreateChannelHandler($requests);
            $res = $channelHandle->handleRequest();
            if (isset($res['error'])) {
                return AdminResponseFactory::error($res['error']);
            }
            return AdminResponseFactory::ok("channel-list");
        }
        $saasInfo = UserFactory::getAllSaasForDisplay();
        return view('admin_modules.channel.append', ['saasInfo' => $saasInfo]);
    }

    /**
     * 删除渠道
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $requests = $this->getRequests($request);
        $rechargeHandle = new DoDeleteChannelHandler($requests);
        $res = $rechargeHandle->handleRequest();
        if (isset($res['error'])) {
            return AdminResponseFactory::error($res['error']);
        }
        return AdminResponseFactory::dialogOk("channel-list");
    }

    /**
     * 查看渠道详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $userId = $request->input('id');
        $data = ChannelFactory::getChannelInfoById($userId);
        return view('admin_modules.channel.detail', ['item' => $data]);
    }

    /**
     * 编辑渠道信息
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $channelId = $request->input('id');
        if ($this->isPostMethod($request)) {
            $requests = $this->getRequests($request);
            $oldInfo = ChannelFactory::getChannelInfoById($channelId);
            $res = ChannelFactory::updateChannelById($requests);
            if (empty($res)) {
                return AdminResponseFactory::error($res['error']);
            }
            event(new OperationLogEvent(103, 'old_info:' . json_encode($oldInfo) .
                ',new_info:' . json_encode($requests)));
            return AdminResponseFactory::ok("channel-list");
        }
        $data = ChannelFactory::getChannelInfoById($channelId);
        return view('admin_modules.channel.edit', ['item' => $data]);
    }
}
