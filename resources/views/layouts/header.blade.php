<header class="fixed-top bg-dark p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                Ardhuy
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Objek Wisata</a></li>
                <li><a href="#" class="nav-link px-2 link-light">Berita</a></li>
            </ul>



            @auth
            <div class="dropdown text-end">
                <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{auth()->user()->karyawans ? auth()->user()->karyawans->nama_lengkap : auth()->user()->pelanggans->nama_lengkap}}
                </a>
                <ul class="dropdown-menu text-small">
                    @if(auth()->user()->level !== 'pelanggan')
                    <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>

                    @endif
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{Route('logout')}}">Sign out</a></li>
                </ul>
            </div>
            @else
            <a href="{{Route('login')}}" class="btn btn-outline-light me-2">Login</a>
            @endauth
        </div>
    </div>
</header>