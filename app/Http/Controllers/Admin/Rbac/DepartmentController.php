<?php

namespace App\Http\Controllers\Admin\Rbac;

use App\Events\OperationLogEvent;
use App\Models\Admin\Permission;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Models\Admin\Department;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Events\UserActionEvent;
use Auth;

class DepartmentController extends AdminController
{
    protected $fields = [
        'name' => '',
        'permissions' => [],
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
            $data['recordsTotal'] = Department::count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = Department::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = Role::where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = Department::count();
                $data['data'] = Department::skip($start)
                    ->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }

        return view('admin_rbac.department.index');
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
        $arr = Permission::all()->toArray();
        foreach ($arr as $v) {
            $data['permissionAll'][$v['cid']][] = $v;
        }

        return view('admin_rbac.department.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        $department = Department::create([
            'name' => $request->input('name'),
        ]);

        if (is_array($request->get('permissions'))) {
            $department->givePermissionsTo($request->get('permissions'));
        }

        event(new UserActionEvent(Department::class, $department->id, 1, "部门" . Auth::user()->username .
            "{" . Auth::user()->id . "}添加部门" . $department->name . "{" . $department->id . "}"));
        event(new OperationLogEvent(210, "添加部门" . $department->name . "{" . $department->id . "}"));

        return redirect()->route('admin.department.indexs')->withSuccess('添加成功！');
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
        $department = Department::findOrFail($id);

        $permissions = [];
        if ($department->permissions) {
            foreach ($department->permissions as $v) {
                $permissions[] = $v->id;
            }
        }

        $department->permissions = $permissions;

        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $department->$field);
        }

        $arr = Permission::all()->toArray();
        foreach ($arr as $v) {
            $data['permissionAll'][$v['cid']][] = (array) $v;
        }

        $data['id'] = intval($id);

        return view('admin_rbac.department.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DepartmentRequest|Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, $id)
    {
        $department = Department::findOrFail($id);

        $department->update([
            'name' => $request->input('name')
        ]);

        $department->givePermissionsTo($request->get('permissions', []));

        event(new UserActionEvent('\App\Models\Admin\Department', $department->id, 3, "部门" . Auth::user()->username .
            "{" . Auth::user()->id . "}编辑角色" . $department->name . "{" . $department->id . "}"));
        event(new OperationLogEvent(212, json_encode($department->toArray())));

        return redirect()->route('admin.department.indexs')->withSuccess('修改成功！');
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
        $department = Department::findOrFail($id);

        foreach ($department->permissions as $v) {
            $department->permissions()->detach($v);
        }

        if ($department) {
            $department->delete();
        } else {
            return redirect()->back()->withErrors("删除失败");
        }
        event(new UserActionEvent('\App\Models\Admin\Department', $department->id, 2, "部门" . Auth::user()->username . "{" . Auth::user()->id . "}删除角色" . $department->name . "{" . $department->id . "}"));
        event(new OperationLogEvent(211, json_encode($department->toArray())));

        return redirect()->back()->withSuccess("删除成功");
    }
}
