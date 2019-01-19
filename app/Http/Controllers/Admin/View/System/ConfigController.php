<?php

namespace App\Http\Controllers\Admin\View\System;

use App\Events\OperationLogEvent;
use App\Helpers\AdminResponseFactory;
use App\Models\Orm\SystemConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\View\ViewController;

class ConfigController extends ViewController
{
    /**
     * 配置列表
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        // 查询条件
        $nid = $request->input('nid');

        // 查询Query
        $query = SystemConfig::when($nid, function ($query) use ($nid) {
            return $query->where('nid', 'like', '%' . $nid . '%');
        });
        // 数据集合
        $total = $query->count();
        $results = $query->offset($requests['pageSize'] * ($requests['pageCurrent'] - 1))
                ->orderBy($requests['orderField'], $requests['orderDirection'])
                ->limit($requests['pageSize'])
                ->get()->toArray();

        return view('admin_modules.system.config.index', [
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 编辑页
     * @param Request $request
     * @return type
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $model = SystemConfig::find($id);
        $data = $model->toArray();
        if ($this->isPostMethod($request)) {
            $model->remark = $request->input('remark');
            $model->nid = $data['nid'];
            $model->value = $request->input('value')?: $data['value'];
            $model->status = $request->input('status');
            $model->update_at = date('Y-m-d H:i:s', time());
            $model->update_user_id = $request->user()->id;
            if ($model->save()) {
                event(new OperationLogEvent(402, json_encode($model->toArray())));
                return AdminResponseFactory::ok('system-config');
            }
        }
        return view('admin_modules.system.config.edit', ['data' => $data]);
    }

    /**
     * 创建页面
     * @param Request $request
     * @return type
     */
    public function create(Request $request)
    {
        $data = [];
        if ($this->isPostMethod($request)) {
            $model = new SystemConfig();
            $model->remark = $request->input('remark') ?: '';
            $model->nid = $request->input('nid') ? 'con_' . $request->input('nid') : '';
            $model->value = $request->input('value') ?: '';
            $model->status = $request->input('status') ?: 0;
            $model->create_at = $model->update_at = date('Y-m-d H:i:s', time());
            $model->update_user_id = $request->user()->id;
            if ($model->save()) {
                event(new OperationLogEvent(400, json_encode($model->toArray())));
                return AdminResponseFactory::ok('system-config');
            }
        }
        return view('admin_modules.system.config.create', ['data' => $data]);
    }

    /**
     * 删除
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $oldInfo = SystemConfig::find($id);
            $result = $oldInfo->delete();
            event(new OperationLogEvent(401, json_encode($oldInfo->toArray())));
            return $result ? AdminResponseFactory::handleOk() : AdminResponseFactory::error();
        }
    }
}
