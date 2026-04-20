<div class="row">
    <div class="col-md-4 mb-3">
        <label for="nombres" class="form-label">Nombres</label>
        <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres', $alumno->nombres ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="apellidos" class="form-label">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos', $alumno->apellidos ?? '') }}" required>
    </div>
    <div class="mb-3 col-md-4">
        <label for="nie" class="form-label">NIE</label>
        <input type="text" name="nie" id="nie" class="form-control" value="{{ old('nie', $alumno->nie ?? '') }}" required>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-6">
        <label for="edad" class="form-label">Edad</label>
        <input type="number" name="edad" id="edad" class="form-control" value="{{ old('edad', $alumno->edad ?? '') }}" required>
    </div>
    <div class="mb-3 col-md-6">
        <label for="sexo" class="form-label">Sexo</label>
        <select name="sexo" id="sexo" class="form-select">
            <option value="" {{ old('sexo', $alumno->sexo ?? '') == '' ? 'selected' : '' }}>-- Seleccione --</option>
            <option value="M" {{ old('sexo', $alumno->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ old('sexo', $alumno->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
        </select>
    </div>
</div>



<div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $alumno->direccion ?? '') }}">
</div>

<div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $alumno->telefono ?? '') }}">
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $alumno->email ?? '') }}">
</div>

<div class="mb-3">
    <label for="responsable" class="form-label">Responsable</label>
    <input type="text" name="responsable" id="responsable" class="form-control" value="{{ old('responsable', $alumno->responsable ?? '') }}">
</div>

<button type="submit" class="btn btn-primary">Guardar</button>