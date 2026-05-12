<section>
    <header class="mb-8 border-b border-gray-100 pb-6">
        <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
            {{ __('Currículum Vitae (CV)') }}
        </h3>
        <p class="text-lg text-gray-600 leading-relaxed">
            {{ __('Gestiona tu currículum. Es obligatorio tener uno subido en formato PDF para poder postularte a los proyectos.') }}
        </p>
    </header>

    {{-- Mostrar mensajes de estado con auto-cierre --}}
    @if (session('status') === 'cv-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="mb-6 text-sm font-semibold text-emerald-700 flex items-center bg-emerald-50 px-4 py-3 rounded-xl border border-emerald-100 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            ¡Tu currículum se ha actualizado correctamente!
        </p>
    @elseif (session('status') === 'cv-deleted')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="mb-6 text-sm font-semibold text-red-700 flex items-center bg-red-50 px-4 py-3 rounded-xl border border-red-100 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Tu currículum ha sido eliminado.
        </p>
    @endif

    @error('cv_file')
        <p class="mb-6 text-sm font-semibold text-red-700 flex items-center bg-red-50 px-4 py-3 rounded-xl border border-red-100 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $message }}
        </p>
    @enderror

    <div>
        {{-- Tarjeta si el CV ya existe --}}
        @if (auth()->user()->profile && auth()->user()->profile->cv_pdf_path)
            <div class="mb-8 p-5 sm:p-6 bg-blue-50 border border-blue-100 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 transition-all shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-white rounded-lg shadow-sm text-blue-600 border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-base text-gray-900 font-extrabold mb-0.5">Ya tienes un CV subido.</p>
                        <a href="{{ Storage::url(auth()->user()->profile->cv_pdf_path) }}" target="_blank" class="text-blue-700 hover:text-blue-800 font-semibold text-sm hover:underline flex items-center transition-colors">
                            Ver CV actual
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Botón para eliminar el CV --}}
                <form method="POST" action="{{ route('profile.cv.delete') }}" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar tu CV?')" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-bold hover:bg-red-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('Quitar CV') }}
                    </button>
                </form>
            </div>
        @else
            {{-- Tarjeta si no hay CV --}}
            <div class="mb-8 p-4 bg-yellow-50 border border-yellow-100 rounded-xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-sm text-yellow-800 font-semibold">Aún no has subido ningún currículum.</p>
            </div>
        @endif

        {{-- Formulario para subir/reemplazar el CV --}}
        <form method="POST" action="{{ route('profile.cv.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="cv_file" class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wider">
                    {{ __('Subir nuevo CV (Solo PDF, Max: 2MB)') }}
                </label>
                <input
                    id="cv_file"
                    name="cv_file"
                    type="file"
                    accept="application/pdf"
                    class="block w-full text-sm text-gray-600 bg-white border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-600 focus:outline-none file:mr-4 file:py-3 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer"
                    required
                />
            </div>

            <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    {{ __('Guardar CV') }}
                </button>
            </div>
        </form>
    </div>
</section>
