<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="py-10">
        {{-- Limitamos a max-w-4xl para que los formularios del perfil no queden excesivamente anchos --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Actualizar Información del Perfil --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Gestión del CV (Solamente visible para Estudiantes) --}}
            @if (auth()->user()->isStudent())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">
                    <div class="max-w-2xl">
                        @include('profile.partials.manage-cv-form')
                    </div>
                </div>
            @endif

            {{-- Actualizar Contraseña --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 transition-all hover:shadow-md">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Eliminar Cuenta (Zona de Peligro) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-8 sm:p-10 transition-all hover:shadow-md">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
