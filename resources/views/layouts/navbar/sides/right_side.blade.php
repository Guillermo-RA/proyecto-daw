<ul class="navbar-nav ms-auto">
    <!-- Authentication Links -->
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
        @endif

        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        @endif
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->username }}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                @can('make_orders')
                    <a class="dropdown-item" href="{{ '#' }}">
                        {{__('Mis pedidos')}}
                    </a>
                @endcan
                <a class="dropdown-item" href="{{ '#' }}">
                    {{__('Mis datos')}}
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    @endguest
    @can('create_products')
    @else

        <li class="nav-item">
            <a href="{{route('cart')}}" class="nav-link text-primary-dark position-relative">
                <i class="text-primary-dark fa fa-shopping-cart"></i>
                <span class="position-absolute badge rounded-pill bg-danger">
                        <span class="cart-number-items">0</span>
                        <span class="visually-hidden">Cart</span>
                    </span>
            </a>
        </li>
    @endcan
</ul>
