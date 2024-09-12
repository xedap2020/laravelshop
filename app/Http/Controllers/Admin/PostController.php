<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PostsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    const POSTS_PATH = '/admin/posts';
    const DEFAULT_DATE = 'NaN-NaN-NaN NaN:NaN:NaN';

    public function __construct()
    {
        $this->middleware('permission:create-post')->only('store', 'create');
        $this->middleware('permission:edit-post')->only('update', 'edit');
        $this->middleware('permission:delete-post')->only('destroy');
        $this->middleware('permission:view-post')->only('index');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = Post::query()->with('users')->select(['id', 'image', 'title', 'description', 'author_id', 'status', 'created_at', 'updated_at', 'slug', 'deleted_at']);
            if ($request->filled('title')) {
                $model = $model->where('title', 'like', '%' . $request->title . '%');
            }
            $model = $model->when($request->filled('author_name'), function ($query) use ($request) {
                $userIds = User::where('name', 'like', '%' . $request->author_name . '%')
                    ->pluck('id');
                return $query->whereIn('author_id', $userIds);
            });
            if ($request->filled('status')) {
                $model = $model->withTrashed()->where('status', $request->status);
            } else {
                $model = $model->where('status', '<>', 4);
            }
            if ($request->filled('started_at') && ($request->started_at != PostController::DEFAULT_DATE)) {
                $model = $model->whereDate('created_at', '>=', $request->started_at);
            }
            if ($request->filled('ended_at') && ($request->ended_at != PostController::DEFAULT_DATE)) {
                $model = $model->whereDate('created_at', '<=', $request->ended_at);
            }
            return DataTables::of($model)
                ->editColumn('status', function ($post) {
                    $message = [
                        1 => 'Hoạt động',
                        2 => 'Không hoạt động',
                        3 => 'Đợi',
                        4 => 'Thùng rác'
                    ];
                    return $message[$post->status];
                })
                ->editColumn('author_id', function ($post) {
                    return $post->users ? $post->users->name : '';
                })
                ->addColumn('action', function ($post) {
                    return view('admin.posts.action', ['post' => $post])->render();
                })
                ->addColumn('image', function ($row) {
                    return '<img class="img-thumbnail user-image-45" src="' . $row->image . '" alt="' . $row->title . '">';
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" name="ids_post" class="checkbox_ids" value="' . $row->id . '"/>';
                })
                ->rawColumns(['image', 'checkbox', 'action'])
                ->setRowId('id')
                ->make(true);
        }
        return view('admin.posts.index');
    }

    public function create()
    {
        $users = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $permissionName = 'create-post';

        // get all user has create-post permission
        $usersWithPermission = $users->filter(function ($user) use ($permissionName) {
            return $user->hasPermissionTo($permissionName);
        });

        return view('admin.posts.create_edit', [
            'users' => $usersWithPermission
        ]);
    }

    public function store(Request $request)
    {
        if ($request->filled('filepath')) {
            $image_path = $request->input('filepath');
            $image_path = explode('http://localhost:8000', $image_path)[1];
        } else {
            $image_path = '/storage/photos/posts/default_post.png';
        }
        $description = $request->input('description');
        $content = $request->input('content');

        $request->validate([
//            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
//            'title' => 'required|unique:products',
        ]);

        Post::create([
            'title' => $request->input('title'),
            'description' => $description,
            'content' => $content,
            'image' => $image_path,
            'author_id' => $request->input('author_id'),
            'status' => 1,
        ]);

        return redirect(PostController::POSTS_PATH)->with('success', 'Tạo bài thành công');
    }

    public function edit($id)
    {
        $post = Post::select(['id', 'title', 'image', 'description', 'content', 'author_id', 'status', 'created_at', 'updated_at', 'slug'])
            ->withTrashed()
            ->where('id', $id)
            ->first();
        $users = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $user = User::select(['id', 'name'])->where('id', $post->author_id)->first();

        return view('admin.posts.create_edit', [
            'post' => $post,
            'users' => $users,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {

        $description = $request->input('description');

        $content = $request->input('content');
        $post = Post::where('id', $id)->withTrashed()->first();

        if ($request->filled('filepath')) {
            $image_path = $request->input('filepath');
            $image_path = explode('http://localhost:8000', $image_path)[1];
        } else {
            $image_path = $post->image;
        }

        $post->update([
            'title' => $request->input('title'),
            'description' => $description,
            'content' => $content,
            'image' => $image_path,
            'author_id' => $request->input('author_id'),
            'status' => $request->input('status'),
            'updated_at' => now(),
            'deleted_at' => null
        ]);

        return redirect(PostController::POSTS_PATH)->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy($id)
    {
        $array_id = explode(',', $id);
        $posts = Post::withTrashed()->whereIn('id', $array_id)->get();
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
            'success' => 1,
            'message' => "Thành công",
        ]);
    }
}
