<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                @if(auth()->user()->isCompany())
                    <div class="relative">
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Notificaciones
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="p-3 border-b text-sm">
                                        <p class="text-gray-800 mb-2">{{ $notification->data['message'] }}</p>
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('projects.show', $notification->data['project_id']) }}" class="text-blue-500 hover:underline inline-block">
                                                Ver proyecto
                                            </a>

                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">
                                                    Marcar como vista
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-3 text-sm text-gray-500">No tienes notificaciones nuevas.</div>
                                @endforelse
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                @if(auth()->user()->isCompany())
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-4 py-2 font-medium text-base text-gray-800 flex items-center">
                            Notificaciones
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </div>
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <a href="{{ route('projects.show', $notification->data['project_id']) }}" class="block ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                                <span class="text-sm block text-gray-500">{{ $notification->data['message'] }}</span>
                            </a>
                        @empty
                            <div class="ps-3 pe-4 py-2 text-sm text-gray-500">
                                No tienes notificaciones nuevas.
                            </div>
                        @endforelse
                    </div>
                @endif
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
