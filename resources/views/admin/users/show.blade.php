@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5 d-flex justify-content-center margin_bottom_detail">
        <div class="border_detail">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="{{asset('images/' . $user->image_path)}}" class="rounded-circle img-thumbnail user-image-detail" alt="User Avatar">
                </div>
                <div class="col-md-8">
                    <h2>{{ $user->name }}</h2>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ $user->role }}</p>
                    <p><strong>Phone:</strong> {{ $user->phoneNumber }}</p>
                    <p><strong>Address:</strong> {{ $user->address }}</p>
                    <p><strong>Age:</strong> {{ $user->age }}</p>
                    <p><strong>Password:</strong> {{ $user->password }}</p>
                    <a class="btn btn-primary" href="/users/{{$user->id}}/edit">Edit Profile</a>
                    <button class="btn btn-danger">Delete Profile</button>
                </div>
            </div>
        </div>
    </div>
@endsection
