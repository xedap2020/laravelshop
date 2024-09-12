@extends('admin.layouts.app')

@section('title', 'Danh sách người dùng')

@section('content')

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    className: "toastify-custom toastify-success"
                }).showToast();
            });
        </script>

    @elseif(session('delete'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Toastify({
                    text: "{{ session('delete') }}",
                    duration: 2000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    className: "toastify-custom toastify-error"
                }).showToast();
            });
        </script>
    @endif

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="text-center">Danh sách người dùng</h2>
            </div>
        </div>
        <br>
        <form id="user_search_form">
            @csrf
            <div class="flex-button">
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-check"></i></span>
                    <input type="text" class="form-control" id="name_user" name="name"
                           placeholder="Tìm theo tên" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" id="email_user" name="email"
                           placeholder="Tìm theo email" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" id="phone_user" name="phone"
                           placeholder="Tìm theo số điện thoại" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" id="address_user" name="address"
                           placeholder="Tìm theo địa chỉ" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-user"></i></span>
                    <input type="text" class="form-control" id="age_user" name="age"
                           placeholder="Tìm theo tuổi" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-brands fa-superpowers"></i></span>
                    <select class="form-control" id="status_user" name="status" aria-label="Large select example">
                        <option value="">--Tất cả trạng thái --</option>
                        <option value="1">Hoạt động</option>
                        <option value="2">Không hoạt động</option>
                        <option value="3">Đợi</option>
                        <option value="4">Thùng rác</option>
                    </select>
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1">Từ</span>
                    <input type="datetime-local" id="start_date" name="start_date" class="form-control" placeholder=""
                           aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 width-300">
                    <span class="input-group-text" id="basic-addon1">Tới</span>
                    <input type="datetime-local" id="ended_date" name="end_date" class="form-control" placeholder=""
                           aria-describedby="basic-addon1">
                </div>
            </div>
            <br>
            <div class="flex-button">
                <button type="submit" id="submit_user_search" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Tìm kiếm
                </button>
                <button class="btn btn-secondary" id="reset_btn_user">
                    <i class="fa-solid fa-xmark"></i>
                    Tải lại
                </button>
            </div>
        </form>

        <br>
        <br>
        <div style="display: flex; flex-direction: row; justify-content: flex-end; gap: 10px">
            @can('create-user')
                <a href="/admin/users/create"
                   class="btn btn-primary margin_bottom_detail">
                    <i class="fa-solid fa-plus"></i>
                    Thêm mới người dùng
                </a>
            @endcan
            @can('delete-user')
                <a id="deleteAllSelectedUser"
                   class="btn btn-danger margin_bottom_detail">
                    <i class="fa-solid fa-trash"></i>
                    Xóa những mục được chọn
                </a>
            @endcan
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="users-table" class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="" id="select_all_ids_users"/></th>
                        <th>Id</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Quyền</th>
                        <th>Tuổi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Bạn có chắc muốn xóa không?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    (Hãy vào thùng rác để xóa nếu như bạn muốn chắc chắn xóa)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" id="confirmDeleteButton_trash" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    {{--  Multiple delete  --}}
    <div class="modal fade" id="trashModal_multiple" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Bạn có chắc muốn xóa không?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    (Hãy vào thùng rác để xóa nếu như bạn muốn chắc chắn xóa)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" id="confirmDeleteButton_remove_multiple" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let datatable = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/admin/users',
                    type: 'GET',
                    data: function (d) {
                        d.name = $('#name_user').val();
                        d.email = $('#email_user').val();
                        d.phone = $('#phone_user').val();
                        d.address = $('#address_user').val();
                        d.age = $('#age_user').val();
                        d.status = $('#status_user').val();
                        d.started_at = $('#start_date').val();
                        d.ended_at = $('#ended_date').val();
                    }
                },
                scrollX: true,
                order: [[1, 'asc']],
                autoWidth: false,
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'image_path', name: 'image_path'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phoneNumber', name: 'phoneNumber'},
                    {data: 'address', name: 'address'},
                    {data: 'roles', name: 'roles'},
                    {data: 'age', name: 'age'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            ///////////////////////// RESET USER
            const reset_btn_user = document.getElementById('reset_btn_user')
            if (reset_btn_user) {
                reset_btn_user.addEventListener('click', function (event) {
                    event.preventDefault();
                    document.getElementById('name_user').value = '';
                    document.getElementById('email_user').value = '';
                    document.getElementById('phone_user').value = '';
                    document.getElementById('address_user').value = '';
                    document.getElementById('age_user').value = '';
                    document.getElementById('status_user').value = '';
                    document.getElementById('start_date').value = '';
                    document.getElementById('ended_date').value = '';

                    datatable.draw('page')
                })
            }


            ///////////////////////// SEARCH USER
            $(document).on('submit', '#user_search_form', function (e) {
                e.preventDefault();
                datatable.ajax.reload()
            })

            ////////// DELETE USER
            $(document).on('click', '.delete_button_user', function (event) {
                event.preventDefault();
                let user_id = $(this).val();
                $('#deleteModal').modal('show')
                $('#confirmDeleteButton_trash').val(user_id)
            });

            $('#confirmDeleteButton_trash').on('click', function (event) {
                event.preventDefault();
                let user_id = $(this).val();

                $.ajax({
                    url: `/admin/users/` + user_id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        let errorMessage = xhr.status + ': ' + xhr.statusText
                        console.error('AJAX Error: ' + errorMessage);

                        $('#deleteModal').modal('hide')
                        let existingToast = document.querySelector(".toastify");
                        if (existingToast) {
                            existingToast.remove();
                        }
                        Toastify({
                            text: "Đã xảy ra lỗi khi xóa bài viết",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            className: "toastify-custom toastify-error"
                        }).showToast();
                        datatable.draw('page');
                    },
                    success: function (data) {
                        $('#deleteModal').modal('hide')
                        let existingToast = document.querySelector(".toastify");
                        if (existingToast) {
                            existingToast.remove();
                        }
                        Toastify({
                            text: "Xóa thành công",
                            duration: 2000,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "right", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            className: "toastify-custom toastify-error"
                        }).showToast();

                        datatable.draw('page');
                    }
                })
            })

            ////////////// DELETE MULTIPLE USER
            $(document).on('click', '#select_all_ids_users', function () {
                $(".checkbox_ids_users").prop('checked', $(this).prop('checked'));
            });
            $(document).on('click', '#deleteAllSelectedUser', function (e) {
                e.preventDefault();

                let all_ids = []
                $('input:checked[name=ids_user]:checked').each(function () {
                    all_ids.push($(this).val())
                })

                all_ids = all_ids.join(",")

                $('#trashModal_multiple').modal('show')
                $('#confirmDeleteButton_remove_multiple').val(all_ids)
            })
            $(document).on('click', '#confirmDeleteButton_remove_multiple', function (e) {
                e.preventDefault();

                let user_id = $(this).val();

                $.ajax({
                    url: `/admin/users/` + user_id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (xhr, status, error) {
                        $('#trashModal_multiple').modal('hide')
                        let existingToast = document.querySelector(".toastify");
                        if (existingToast) {
                            existingToast.remove();
                        }
                        Toastify({
                            text: "Đã xảy ra lỗi khi xóa bài viết",
                            duration: 2000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            className: "toastify-custom toastify-error"
                        }).showToast();

                        datatable.draw(false)
                    },
                    success: function () {
                        $('#trashModal_multiple').modal('hide')
                        let existingToast = document.querySelector(".toastify");
                        if (existingToast) {
                            existingToast.remove();
                        }
                        Toastify({
                            text: "Xóa thành công",
                            duration: 2000,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "right", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            className: "toastify-custom toastify-success"
                        }).showToast();

                        datatable.draw(false)
                    }
                })

            })

        })

    </script>
@endpush
