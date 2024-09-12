<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    const USERS_PATH = '/admin/users';
    const DEFAULT_DATE = 'NaN-NaN-NaN NaN:NaN:NaN';

    public function __construct()
    {
        $this->middleware('permission:create-user')->only('store', 'create');
        $this->middleware('permission:edit-user')->only('update', 'edit');
        $this->middleware('permission:delete-user')->only('destroy');
        $this->middleware('permission:view-user')->only('index');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = User::query()
                ->select(['id', 'image_path', 'name', 'email', 'phoneNumber', 'status', 'address', 'age', 'created_at', 'updated_at']);

            if ($request->filled('name')) {
                $model = $model->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $model = $model->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('phone')) {
                $model = $model->where('phoneNumber', 'like', '%' . $request->phone . '%');
            }
            if ($request->filled('address')) {
                $model = $model->where('address', 'like', '%' . $request->address . '%');
            }
            if ($request->filled('age')) {
                $model = $model->where('age', 'like', '%' . $request->age . '%');
            }
            if ($request->filled('status')) {
                $model = $model->withTrashed()->where('status', $request->status);
            } else {
                $model = $model->where('status', '<>', 4);
            }
            if ($request->filled('started_at') && ($request->started_at != UserController::DEFAULT_DATE)) {
                $model = $model->whereDate('created_at', '>=', $request->started_at);
            }

            if ($request->filled('ended_at') && ($request->ended_at != UserController::DEFAULT_DATE)) {
                $model = $model->whereDate('created_at', '<=', $request->ended_at);
            }

            return DataTables::of($model)
                ->editColumn('status', function ($post) {
                    $statusMessages = [
                        1 => 'Hoạt động',
                        2 => 'Không hoạt động',
                        3 => 'Đợi',
                        4 => 'Xóa mềm'
                    ];
                    return $statusMessages[$post->status];
                })
                ->addColumn('action', function ($user) {
                    return view('admin.users.action', ['user' => $user]);
                })
                ->addColumn('image_path', function ($row) {
                    return '<img class="img-thumbnail user-image-45" src="' . $row->image_path . '" alt="' . $row->name . '">';
                })
                ->addColumn('roles', function ($user) {
                    $roles = $user->getRoleNames()->map(function ($roleName) {
                        return '<label class="badge bg-primary mx-1">' . $roleName . '</label>';
                    })->implode(' ');

                    return '<td>' . $roles . '</td>';
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" name="ids_user" class="checkbox_ids_users" value="' . $row->id . '"/>';
                })
                ->rawColumns(['image_path', 'roles', 'action', 'checkbox'])
                ->make();
        }
        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', [
            'roles' => $roles
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required',
            'age' => 'required|integer|min:0|max:100',
            'address' => 'required',
            'phoneNumber' => 'required',
            'roles' => 'required'
//            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        if ($request->filled('filepath')) {
            $image_path = $request->input('filepath');
            $image_path = explode('http://localhost:8000', $image_path)[1];
        } else {
            $image_path = '/storage/photos/users/default_user.jpg';
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'phoneNumber' => $request->input('phoneNumber'),
            'image_path' => $image_path,
            'status' => 1,
        ]);

        $user->syncRoles($request->roles);
        return redirect(UserController::USERS_PATH)->with('success', 'Tạo người dùng thành công');
    }
    public function edit($id){
        $user = User::withTrashed()->where('id', $id)->select(['id', 'name', 'email', 'status', 'age', 'phoneNumber', 'address',
            'image_path', 'deleted_at'])->first();
        $roles = Role::pluck('name', 'name')->all();

        $userRoles = $user->roles->pluck('name', 'name')->all();
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'email',
        ]);

        $user = User::withTrashed()->where('id', $id)->select(['id', 'name', 'email', 'status', 'age', 'phoneNumber', 'address',
            'image_path', 'deleted_at'])->first();

        if ($request->filled('filepath')) {
            $image_path = $request->input('filepath');
            $image_path = explode('http://localhost:8000', $image_path)[1];
        } else {
            $image_path = $user->image_path;
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'phoneNumber' => $request->input('phoneNumber'),
            'image_path' => $image_path,
            'status' => $request->input('status'),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $user->syncRoles($request->roles);

        return redirect(UserController::USERS_PATH)->with('success', 'Cập nhật thành công');
    }
    public function destroy($id)
    {
        $array_id = explode(',', $id);
        $posts = User::withTrashed()->whereIn('id', $array_id)->get();
        foreach ($posts as $post) {
            if (is_null($post->deleted_at)) {
                $post->update([
                    'status' => 4
                ]);
                $post->delete();
            } else {
                $post->forceDelete();
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Thành công'
        ]);
    }
}
