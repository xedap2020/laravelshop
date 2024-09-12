<div class="d-flex align-items-center">
    @can('edit-user')
        <a href="/admin/users/{{$user->id}}/edit" class="btn btn-primary btn-sm mr-2"><i class="fa-solid fa-wrench"></i></a>
    @endcan
    @can('delete-user')
        <button style="margin-left: 6px" value="{{$user->id}}" data-id="{{$user->id}}"
                class="btn btn-danger btn-sm delete_button_user"><i class="fa-solid fa-trash"></i>
        </button>
    @endcan
</div>
