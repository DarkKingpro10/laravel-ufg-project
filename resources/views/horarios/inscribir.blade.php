@extends('layout')
@section('content')
<h1>Inscribir materias por docente</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('horarios.store') }}" method="POST" id="inscribirForm">
    @csrf
    <div class="mb-3">
        <label>Docente</label>
        <select name="docente_id" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach($docentes as $d)
            <option value="{{ $d->id }}">{{ $d->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div id="entries">
        <!-- rows will be inserted here -->
    </div>

    <div class="mb-3">
        <button type="button" id="addRow" class="btn btn-secondary">Agregar materia</button>
        <button class="btn btn-primary">Inscribir</button>
    </div>
</form>

@endsection

@section('scripts')
<script>
    const materias = @json($materias);
    const dias = @json($dias);
    let count = 0;

    function addRow(data = {}) {
        if (count >= 5) return alert('Máximo 5 materias.');
        count++;
        const idx = count - 1;
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 entry';
        div.innerHTML = `
        <div class="col-md-4">
            <select name="entries[${idx}][materia_id]" class="form-select" required>
                <option value="">-- Materia --</option>
                ${materias.map(m=>`<option value="${m.id}">${m.nombre}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-2">
            <select name="entries[${idx}][dia]" class="form-select" required>
                ${dias.map(d=>`<option value="${d}">${d}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-2"><input type="time" name="entries[${idx}][hora_inicio]" class="form-control" required></div>
        <div class="col-md-2"><input type="time" name="entries[${idx}][hora_fin]" class="form-control" required></div>
        <div class="col-md-1"><input type="text" name="entries[${idx}][aula]" class="form-control" placeholder="Aula"></div>
        <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove">X</button></div>
    `;
        document.getElementById('entries').appendChild(div);
        div.querySelector('.remove').addEventListener('click', () => {
            div.remove();
            count--;
            refreshNames();
        });
    }

    function refreshNames() {
        const nodes = document.querySelectorAll('#entries .entry');
        nodes.forEach((div, i) => {
            div.querySelectorAll('select, input').forEach(inp => {
                const name = inp.name;
                const newName = name.replace(/entries\[\d+\]/, `entries[${i}]`);
                inp.name = newName;
            });
        });
    }

    document.getElementById('addRow').addEventListener('click', () => addRow());
    // add one by default
    addRow();
</script>
@endsection