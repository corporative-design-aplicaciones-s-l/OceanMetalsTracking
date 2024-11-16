@extends(Auth::user()->role === 'admin' ? 'layouts.admin-layout' : 'layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Perfil de Usuario</h2>

        <div class="row">
            <!-- Tarjeta de Datos del Usuario -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Datos del Usuario</span>
                        <!-- Botón para editar -->
                        <button id="editButton" class="btn btn-sm btn-outline-secondary" onclick="toggleEditMode()">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Modo Visualización -->
                        <div id="viewMode">
                            <p><strong>Nombre:</strong> <span id="displayName">{{ $user->name }}</span></p>
                            <p><strong>Apellidos:</strong> <span id="displayLastName">{{ $user->last_name }}</span></p>
                            <p><strong>Correo Electrónico:</strong> <span id="displayEmail">{{ $user->email }}</span></p>
                            <p><strong>Teléfono:</strong> <span id="displayPhone">{{ $user->telefono }}</span></p>
                        </div>
                        <!-- Modo Edición -->
                        <form id="editMode" action="{{ route('profile.update') }}" method="POST" class="d-none">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Apellidos</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                    required>
                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('phone', $user->telefono) }}" required>
                                @error('telefono')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Campos de Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no
                                    cambiarla)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Calendario -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">Calendario del Mes en Curso</div>
                    <div class="card-body">
                        <!-- Aquí es donde se mostrará el calendario -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleEditMode() {
            // Alterna entre el modo de visualización y el modo de edición
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            viewMode.classList.toggle('d-none');
            editMode.classList.toggle('d-none');
        }
    </script>
    <script src="{{ asset('js/calendar.js') }}"></script>
@endsection
