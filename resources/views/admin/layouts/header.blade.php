<!-- Header -->
<header class="custom_header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark d-flex">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link header_color_text" style="margin-left: 25px" href="/">Home</a>
                    </li>
                </ul>
            </div>
            <div class="header_icon_cus p-3"><i class="fa-solid fa-magnifying-glass"></i></div>
            <div class="header_icon_cus p-3"><i class="fa-regular fa-message"></i></div>
            <div class="header_icon_cus p-3"><i class="fa-regular fa-envelope"></i></div>
            {{--            <img src="{{ asset('images/users/default_user.jpg') }}" class="img-thumbnail user-image" alt="Avatar">--}}
            <!-- Settings Dropdown -->
            <div class="dropdown" style="margin-right: 100px">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu" style="width: 50px">
                    <li><a class="dropdown-item" href="{{route('profile.edit')}}">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{route('logout')}}" onclick="event.preventDefault();
                                                this.closest('form').submit();"
                               style="margin-left: 16px"
                            >
                                Đăng xuất
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>