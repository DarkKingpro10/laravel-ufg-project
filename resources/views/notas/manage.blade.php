@extends('layout')

@section('content')
<div class="container">
    <h1>Notas - {{ $alumno->nombres }} {{ $alumno->apellidos }}</h1>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('notas.storeAlumno') }}">
        @csrf
        <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
        <input type="hidden" name="ciclo_id" value="{{ $cicloId }}">

        <div class="row">
            @foreach($notas as $nota)
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>{{ $nota->inscripcion->horario->materia->nombre ?? 'Materia' }}</h5>
                    <p class="mb-2 small text-muted">{{ $nota->inscripcion->horario->docente->nombre ?? '' }} — {{ $nota->inscripcion->horario->dia }} {{ $nota->inscripcion->horario->hora_inicio }}-{{ $nota->inscripcion->horario->hora_fin }}</p>
                    @for($p=1;$p<=4;$p++)
                        <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Lab P{{ $p }} (10%)</label>
                            <input type="number" min="0" max="10" step="0.01" name="notas[{{ $nota->id }}][lab_p{{ $p }}]" value="{{ $nota->{'lab_p'.$p} }}" class="form-control nota-input" data-id="{{ $nota->id }}" aria-label="Laboratorio periodo {{ $p }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Parc P{{ $p }} (15%)</label>
                            <input type="number" min="0" max="10" step="0.01" name="notas[{{ $nota->id }}][parc_p{{ $p }}]" value="{{ $nota->{'parc_p'.$p} }}" class="form-control nota-input" data-id="{{ $nota->id }}" aria-label="Parcial periodo {{ $p }}">
                        </div>
                </div>
                @endfor
                <div class="mt-2"><strong>Promedio: </strong> <span id="avg-{{ $nota->id }}" class="badge bg-secondary text-white">{{ number_format($nota->promedio(),2) }}</span></div>
            </div>
        </div>
        @endforeach
</div>

<button class="btn btn-primary">Guardar todas las notas</button>
</form>
</div>
@endsection

@section('scripts')
<script>
    function calcNota(id) {
        let sumLab = 0,
            sumPar = 0;
        for (let p = 1; p <= 4; p++) {
            let lab = parseFloat($('[name="notas[' + id + '][lab_p' + p + ']"]').val());
            let par = parseFloat($('[name="notas[' + id + '][parc_p' + p + ']"]').val());
            lab = isFinite(lab) ? lab : 0;
            par = isFinite(par) ? par : 0;
            sumLab += lab;
            sumPar += par;
        }
        let avg = (sumLab * 0.10 + sumPar * 0.15);
        let display = isFinite(avg) ? avg.toFixed(2) : '0.00';
        const $el = $('#avg-' + id);
        $el.text(display);
        $el.removeClass('bg-success bg-warning bg-danger bg-secondary');
        if (avg >= 80) {
            $el.addClass('bg-success text-white');
        } else if (avg >= 60) {
            $el.addClass('bg-warning text-dark');
        } else {
            $el.addClass('bg-danger text-white');
        }
    }

    $(function() {
        // bind and initialize
        $('.nota-input').on('input', function() {
            calcNota($(this).data('id'));
        });

        // initial calculation for existing notas
        $('.nota-input').each(function() {
            calcNota($(this).data('id'));
        });
    });
</script>
@endsection