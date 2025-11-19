       {{-- Top Navbar --}}
       <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
           <div class="container-fluid">

               <span class="navbar-brand mb-0 h1"></span>



               <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">

                   {{-- Nama User --}}
                   <li class="nav-item me-3">
                       <span class="nav-link">
                           <i class="bi bi-person-circle me-1"></i>
                           {{ Auth::user()->name }}
                       </span>
                   </li>

                   {{-- Logout --}}
                   <li class="nav-item">
                       <form action="{{ route('logout') }}" method="POST">
                           @csrf
                           <button class="btn btn-outline-danger btn-sm" type="submit">
                               <i class="bi bi-box-arrow-right"></i> Logout
                           </button>
                       </form>
                   </li>

               </ul>
           </div>
       </nav>
