@extends('layouts.admin-layout')

@section('content')
    <h2 class="my-4">Listado de Trabajadores</h2>

    <!-- Tabla de Trabajadores -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Estado de Trabajo</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workers as $worker)
                <tr>
                    <td>{{ $worker->name }}</td>
                    <td>{{ $worker->last_name ?? 'No asignado' }}</td>
                    <td>{{ ucfirst($worker->estado_trabajo) }}</td>
                    <td>{{ $worker->email }}</td>
                    <td>{{ $worker->telefono ?? 'No asignado' }}</td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $worker }})">Editar</button>

                        <!-- Formulario para eliminar trabajador -->
                        <form action="{{ route('admin.workers.destroy', $worker->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este trabajador?')">Borrar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay trabajadores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal de Edición de Trabajador -->
    <div class="modal fade" id="editWorkerModal" tabindex="-1" aria-labelledby="editWorkerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWorkerModalLabel">Editar Trabajador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="editWorkerForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Campos de Edición -->
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="editLastName" name="last_name">
                        </div>

                        <div class="mb-3">
                            <label for="editEstadoTrabajo" class="form-label">Estado de Trabajo</label>
                            <select class="form-select" id="editEstadoTrabajo" name="estado_trabajo" required>
                                <option value="trabajando">Trabajando</option>
                                <option value="descansando">Descansando</option>
                                <option value="no_trabajando">No trabajando</option>
                                <option value="de_vacaciones">De vacaciones</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editTelefono" name="telefono">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openEditModal(worker) {
            // Asignar valores del trabajador seleccionado a los campos del formulario
            document.getElementById('editName').value = worker.name;
            document.getElementById('editLastName').value = worker.last_name || '';
            document.getElementById('editEstadoTrabajo').value = worker.estado_trabajo;
            document.getElementById('editTelefono').value = worker.telefono || '';

            // Actualizar la acción del formulario para enviar la solicitud a la URL de actualización correcta
            const form = document.getElementById('editWorkerForm');
            form.action = `/admin/workers/${worker.id}`;

            // Abrir el modal
            var editWorkerModal = new bootstrap.Modal(document.getElementById('editWorkerModal'));
            editWorkerModal.show();
        }
    </script>
@endsection
