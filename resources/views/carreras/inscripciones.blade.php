@extends('layout')

@section('content')
<div class="container">
    <h1>Inscripciones</h1>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="mb-3">
        <form method="GET" action="{{ route('inscripciones.index') }}">
            <label>Filtrar por carrera</label>
            <select name="carrera_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Todas --</option>
                @foreach($carreras as $c)
                <option value="{{ $c->id }}" {{ (string)$c->id === (string)$selected ? 'selected' : '' }}>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Inscribir alumno</h5>
            <form method="POST" action="{{ route('inscripciones.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Alumno</label>
                        <select name="alumno_id" class="form-control" required>
                            <option value="">-- seleccionar --</option>
                            @foreach(App\Models\Alumno::orderBy('nombres')->get() as $a)
                            <option value="{{ $a->id }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Carrera</label>
                        <select name="carrera_id" class="form-control" required>
                            <option value="">-- seleccionar --</option>
                            @foreach($carreras as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button class="btn btn-success">Inscribir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table id="alumnos-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIE</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Carreras</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->nie }}</td>
                <td>{{ $a->nombres }}</td>
                <td>{{ $a->apellidos }}</td>
                <td>
                    @foreach($a->carreras as $c)
                    <span class="badge bg-secondary">{{ $c->nombre }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach($a->carreras as $c)
                    @php $ins = App\Models\InscripcionCarrera::where('alumno_id',$a->id)->where('carrera_id',$c->id)->first(); @endphp
                    @if($ins)
                    <form action="{{ route('inscripciones.destroy', $ins->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Quitar</button>
                    </form>
                    @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        $('#alumnos-table').DataTable({
            responsive: true
        });
    });
</script>
@endsection