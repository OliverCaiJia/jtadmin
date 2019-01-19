<?php

namespace App\Http\Controllers\Admin\Rbac;

use App\Events\OperationLogEvent;
use App\Models\Admin\Department;
use App\Models\Admin\Role;
use App\Models\Admin\AdminUser as User;
use App\Strategies\AdminUserStrategy;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminUserCreateRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Http\Controllers\Admin\AdminController;
use App\Events\UserActionEvent;
use Auth;

class UserController extends AdminController
{
    protected $fields = [
        'id' => '',
        'username' => '',
        'name' => '',
        'email' => '',
        'super_user' => 0,
        'roles' => [],
        'created_by' => '',
        'department' => ''
    ];

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = User::count();

            $authId = Auth::user()->id;
            $count = User::where('id', '!=', $authId);
            $dataBuilder = User::with('creator')->where('id', '!=', $authId);

            if (!Auth::user()->super_user) {
                $subIds = AdminUserStrategy::getIdsBySubIds($authId);
                $count->whereIn('id', $subIds);
                $dataBuilder->whereIn('id', $subIds);
            }

            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = $count->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = $dataBuilder->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('email', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)
                    ->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = $count->count();
                $data['data'] = $dataBuilder->skip($start)
                    ->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }

            return response()->json($data);
        }

        return view('admin_rbac.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        $data['rolesAll'] = Role::all()->toArray();
        $data['departments'] = Department::all();

        return view('admin_rbac.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminUserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserCreateRequest $request)
    {
        if (!$request->input('roles') && !$request->input('super_user')) {
            return redirect()->back()->withErrors('最高权限和角色必须选一个');
        }

        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'super_user' => $request->get('super_user') ? 1 : 0,
            'created_by' => Auth::user()->id
        ]);

        if (is_array($request->get('roles'))) {
            $user->giveRoleTo($request->get('roles'));
        }
        event(new UserActionEvent(User::class, $user->id, 1, '添加了用户' . $user->name));
        event(new OperationLogEvent(230, json_encode($user->toArray())));

        return redirect()->route('admin.user.indexs')->withSuccess('添加成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = [];
        $departmentId = 0;
        $userRoles = $user->roles;
        if ($userRoles->isNotEmpty()) {
            foreach ($userRoles as $v) {
                $roles[] = $v->id;
            }
            $departmentId = $userRoles->first()->dpm_id;
            $data['department'] = $departmentId;
        }

        $user->roles = $roles;
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $user->$field);
        }
        $data['rolesAll'] = Role::where('dpm_id', $departmentId)->get()->toArray();
        $data['id'] = intval($id);
        $data['departments'] = Department::all();
        event(new UserActionEvent(User::class, $user->id, 3, '编辑了用户' . $user->name));

        return view('admin_rbac.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminUserUpdateRequest $request
     * @param  int                    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserUpdateRequest $request, $id)
    {
        if (!$request->input('roles') && !$request->input('super_user')) {
            return redirect()->back()->withErrors('最高权限和角色必须选一个');
        }

        $user = User::findOrFail($id);
        $updateData = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'super_user' => $request->get('super_user') ? 1 : 0
        ];

        $password = $request->get('password');
        if ($password) {
            if (strlen($password) < 8 || strlen($password) > 16) {
                return redirect()->back()->withErrors("密码长度需大于8，小于16");
            }
            // 更新密码
            $updateData['password'] = bcrypt($password);
        }

        $user->update($updateData);
        $user->giveRoleTo($request->get('roles', []));
        event(new OperationLogEvent(232, json_encode($user->toArray())));

        return redirect()->route('admin.user.indexs')->withSuccess('修改成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return $this
     * @throws \Exception
     */
    public function destroy()
    {
        return redirect()->back()->withErrors("不允许删除");
    }

    /**
     * 更改用户密码
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function changePwd()
    {
        return response('{
            "statusCode":"200",
            "message":"\u64cd\u4f5c\u6210\u529f",
            "tabid":"table, table-fixed",
            "closeCurrent":true,
            "forward":"",
            "forwardConfirm":""
        }');
    }
}
