<?php

namespace App\Http\Controllers\Admin\Rbac;

use App\Events\OperationLogEvent;
use App\Models\Admin\Department;
use App\Models\Admin\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RoleCreateRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Models\Admin\Role;
use Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Events\UserActionEvent;
use DB;

class RoleController extends AdminController
{
    protected $fields = [
        'name' => '',
        'description' => '',
        'permissions' => [],
        'dpm_id' => ''
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
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = Role::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Role::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Role::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('description', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Role::count();
                $data['data'] = Role::
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        return view('admin_rbac.role.index');
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
        $data['permissionAll'] = [];
        $data['departments'] = Department::all();

        return view('admin_rbac.role.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        $role = new Role();
        foreach (array_keys($this->fields) as $field) {
            $role->$field = $request->get($field);
        }
        unset($role->permissions);
        $role->created_at = date('Y-m-d H:i:s');
        $role->updated_at = date('Y-m-d H:i:s');
        $role->save();
        if (is_array($request->get('permissions'))) {
            $role->givePermissionsTo($request->get('permissions'));
        }
        event(new UserActionEvent('\App\Models\Admin\Role', $role->id, 1, "用户" . Auth::user()->username . "{" . Auth::user()->id . "}添加角色" . $role->name . "{" . $role->id . "}"));
        event(new OperationLogEvent(220, "添加角色" . $role->name . "{" . $role->id . "}"));

        return redirect('/admin/role')->withSuccess('添加成功！');
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
        $role = Role::find($id);
        if (!$role) {
            return redirect('/admin/role')->withErrors("找不到该角色!");
        }

        $permissions = [];
        if ($role->permissions) {
            foreach ($role->permissions as $v) {
                $permissions[] = $v->id;
            }
        }
        $role->permissions = $permissions;
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $role->$field);
        }
        $arr = DB::table('admin_department_permission')
            ->rightJoin('admin_permissions', 'permission_id', 'id')
            ->where('dpm_id', $role->dpm_id)
            ->orWhere('cid', 0)
            ->get()
            ->toArray();

        foreach ($arr as $v) {
            $data['permissionAll'][$v->cid][] = (array) $v;
        }

        $data['id'] = intval($id);
        $data['departments'] = Department::all();

        return view('admin_rbac.role.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest|Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::find($id);
        foreach (array_keys($this->fields) as $field) {
            $role->$field = $request->get($field);
        }
        unset($role->permissions);
        $role->updated_at = date('Y-m-d H:i:s');
        $role->save();

        $role->givePermissionsTo($request->get('permissions', []));
        event(new UserActionEvent('\App\Models\Admin\Role', $role->id, 3, "用户" . Auth::user()->username .
            "{" . Auth::user()->id . "}编辑角色" . $role->name . "{" . $role->id . "}"));
        event(new OperationLogEvent(222, json_encode($role->toArray())));

        return redirect('/admin/role')->withSuccess('修改成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return $this
     * @throws \Exception
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        foreach ($role->users as $v) {
            $role->users()->detach($v);
        }

        foreach ($role->permissions as $v) {
            $role->permissions()->detach($v);
        }

        if ($role) {
            $role->delete();
        } else {
            return redirect()->back()->withErrors("删除失败");
        }
        event(new UserActionEvent('\App\Models\Admin\Role', $role->id, 2, "用户" . Auth::user()->username .
            "{" . Auth::user()->id . "}删除角色" . $role->name . "{" . $role->id . "}"));
        event(new OperationLogEvent(221, json_encode($role->toArray())));

        return redirect()->back()->withSuccess("删除成功");
    }

    public function getRoles($id)
    {
        $roles = Role::where('dpm_id', $id)->get()->toArray();

        return view('admin_rbac.user.role', compact('roles'));
    }
}
