<nav x-data="{ open: false }" class="w-full bg-white border-b border-gray-100">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('technicien.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo de l'application" class="h-20 w-auto">
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex space-x-6 items-center">
                <x-nav-link :href="route('technicien.dashboard')" :active="request()->routeIs('technicien.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('technicien.entretien')" :active="request()->routeIs('technicien.entretien')">
                    {{ __('Entretiens') }}
                </x-nav-link>
                <x-nav-link :href="route('technicien.carte')" :active="request()->routeIs('technicien.carte')">
                    {{ __('Carte') }}
                </x-nav-link>
            </div>

            <!-- Profil + Mobile menu -->
            <div class="flex items-center">
                <!-- Profil -->
                <div class="hidden md:flex md:items-center md:ms-4">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 bg-white">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Déconnexion') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobile button -->
                <div class="md:hidden ms-2">
                    <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden px-4 pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('technicien.dashboard')" :active="request()->routeIs('technicien.dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('technicien.entretien')" :active="request()->routeIs('technicien.entretien')">
            {{ __('Entretiens') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('technicien.carte')" :active="request()->routeIs('technicien.carte')">
            {{ __('Carte') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('profile.edit')">
            {{ __('Profil') }}
        </x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Déconnexion') }}
            </x-responsive-nav-link>
        </form>
    </div>
</nav>
