<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" aria-label="Volver al Panel" class="focus:outline-none focus:ring-2 focus:ring-blue-600 rounded transition-transform hover:scale-105">
                        <span class="text-xl font-bold text-blue-800 tracking-tighter">A<span class="text-blue-600">TH</span></span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-sm font-medium">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->isStudent())
                        <x-nav-link :href="route('profile.skills')" :active="request()->routeIs('profile.skills')" class="text-sm font-medium">
                            {{ __('Mis Habilidades') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                @if(auth()->user()->isCompany())
                    <div class="relative">
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 transition ease-in-out duration-150">
                                    Notificaciones
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="ml-1.5 bg-red-500 text-white text-[10px] font-bold tracking-wide rounded-full px-2 py-0.5 shadow-sm">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="p-4 border-b border-gray-50 text-sm hover:bg-gray-50 transition-all">
                                        <p class="text-gray-800 font-medium mb-3">{{ $notification->data['message'] }}</p>
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('projects.applicants', $notification->data['project_id']) }}" class="text-emerald-600 hover:text-emerald-700 font-semibold hover:underline inline-block transition-colors">
                                                Ver proyecto
                                            </a>

                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                                                    Marcar como vista
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-sm font-medium text-gray-500">
                                        No tienes notificaciones nuevas.
                                    </div>
                                @endforelse
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-600 transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-400 font-semibold uppercase tracking-widest">Gestionar Cuenta</div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-600 hover:text-red-800">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white shadow-lg absolute w-full z-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->isStudent())
                <x-responsive-nav-link :href="route('profile.skills')" :active="request()->routeIs('profile.skills')">
                    {{ __('Mis Habilidades') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-2 border-t border-gray-200">
            <div class="px-4 py-2">
                <div class="font-bold text-base text-gray-900">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                @if(auth()->user()->isCompany())
                    <div class="border-y border-gray-100 bg-gray-50 mt-2">
                        <div class="px-4 py-3 font-bold text-base text-gray-900 flex items-center">
                            Notificaciones
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="ml-2 bg-red-500 text-white text-[10px] font-bold tracking-wide rounded-full px-2 py-0.5 shadow-sm">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </div>
                        <div class="bg-white">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <a href="{{ route('projects.applicants', $notification->data['project_id']) }}" class="block ps-3 pe-4 py-3 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 hover:border-emerald-500 focus:outline-none focus:text-emerald-800 focus:bg-emerald-50 focus:border-emerald-500 transition-all">
                                    <span class="text-sm block text-gray-700">{{ $notification->data['message'] }}</span>
                                </a>
                            @empty
                                <div class="ps-4 pe-4 py-3 text-sm font-medium text-gray-500">
                                    No tienes notificaciones nuevas.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <div class="pt-2">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-red-600 font-semibold hover:bg-red-50">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
