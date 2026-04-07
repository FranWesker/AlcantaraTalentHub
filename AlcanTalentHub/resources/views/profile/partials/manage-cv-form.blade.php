<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Currículum Vitae (CV)') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Gestiona tu currículum. Es obligatorio tener uno subido en formato PDF para poder postularte a los proyectos.') }}
        </p>
    </header>

    {{-- Mostrar mensajes de estado --}}
    @if (session('status') === 'cv-updated')
        <p class="mt-2 text-sm text-green-600 dark:text-green-400">¡Tu currículum se ha actualizado correctamente!</p>
    @elseif (session('status') === 'cv-deleted')
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tu currículum ha sido eliminado.</p>
    @endif
    @error('cv_file')
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror

    <div class="mt-6">
        @if (auth()->user()->profile && auth()->user()->profile->cv_pdf_path)
            <div class="mb-4 p-4 border rounded bg-gray-50 dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-800 dark:text-gray-200 font-semibold">Ya tienes un CV subido.</p>
                    <a href="{{ Storage::url(auth()->user()->profile->cv_pdf_path) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm underline">
                        Ver CV actual
                    </a>
                </div>

                {{-- Botón para eliminar el CV --}}
                <form method="POST" action="{{ route('profile.cv.delete') }}">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar tu CV?')">
                        {{ __('Quitar CV') }}
                    </x-danger-button>
                </form>
            </div>
        @else
            <div class="mb-4">
                <p class="text-sm text-red-600 dark:text-red-400 font-semibold">Aún no has subido ningún currículum.</p>
            </div>
        @endif

        {{-- Formulario para subir/reemplazar el CV --}}
        <form method="POST" action="{{ route('profile.cv.upload') }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div>
                <x-input-label for="cv_file" value="{{ __('Subir nuevo CV (Solo PDF, Max: 2MB)') }}" />
                <input id="cv_file" name="cv_file" type="file" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800" required />
            </div>

            <div class="flex items-center gap-4 mt-4">
                <x-primary-button>{{ __('Guardar CV') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
