@extends('layout')

@section('content')
<div class="container">
    <h1>Editar Nota - {{ $nota->inscripcion->alumno->nombres }} {{ $nota->inscripcion->alumno->apellidos }}</h1>

    <form method="POST" action="{{ route('notas.update',$nota->id) }}">
        @csrf @method('PUT')
        <div class="row">
            @for($p=1;$p<=4;$p++)
                <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>Periodo {{ $p }}</h5>
                    <div class="mb-2">
                        <label class="form-label">Laboratorio (10%)</label>
                        <input type="number" min="0" max="10" step="0.01" name="lab_p{{ $p }}" class="form-control nota-input" value="{{ old('lab_p'.$p, $nota->{'lab_p'.$p}) }}" aria-label="Laboratorio periodo {{ $p }}">
                    </div>
                    <div>
                        <label class="form-label">Parcial (15%)</label>
                        <input type="number" min="0" max="10" step="0.01" name="parc_p{{ $p }}" class="form-control nota-input" value="{{ old('parc_p'.$p, $nota->{'parc_p'.$p}) }}" aria-label="Parcial periodo {{ $p }}">
                    </div>
                </div>
        </div>
        @endfor
</div>

<div class="mb-3">
    <strong>Promedio calculado: </strong> <span id="avg" class="badge bg-secondary text-white">{{ number_format($nota->promedio(),2) }}</span>
</div>

<button class="btn btn-primary">Guardar</button>
</form>
</div>
@endsection

@section('scripts')
<script>
    function calc() {
        let sumLab = 0,
            sumPar = 0;
        for (let p = 1; p <= 4; p++) {
            let lab = parseFloat($('[name=lab_p' + p + ']').val());
            let par = parseFloat($('[name=parc_p' + p + ']').val());
            lab = isFinite(lab) ? lab : 0;
            par = isFinite(par) ? par : 0;
            sumLab += lab;
            sumPar += par;
        }
        let avg = (sumLab * 0.10 + sumPar * 0.15);
        const $el = $('#avg');
        let display = isFinite(avg) ? avg.toFixed(2) : '0.00';
        $el.text(display);
        $el.removeClass('bg-success bg-warning bg-danger bg-secondary text-white text-dark');
        if (avg >= 80) {
            $el.addClass('bg-success text-white');
        } else if (avg >= 60) {
            $el.addClass('bg-warning text-dark');
        } else {
            $el.addClass('bg-danger text-white');
        }
    }
    $(function() {
        $('.nota-input').on('input', calc);
        calc();
    });
</script>
@endsection