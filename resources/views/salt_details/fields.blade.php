<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Ph Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PH', 'Ph:') !!}
    {!! Form::text('PH', null, ['class' => 'form-control']) !!}
</div>

<!-- Co3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CO3', 'Co3:') !!}
    {!! Form::text('CO3', null, ['class' => 'form-control']) !!}
</div>

<!-- Hco3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('HCO3', 'Hco3:') !!}
    {!! Form::text('HCO3', null, ['class' => 'form-control']) !!}
</div>

<!-- Cl Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Cl', 'Cl:') !!}
    {!! Form::text('Cl', null, ['class' => 'form-control']) !!}
</div>

<!-- So4 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SO4', 'So4:') !!}
    {!! Form::text('SO4', null, ['class' => 'form-control']) !!}
</div>

<!-- Ca Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Ca', 'Ca:') !!}
    {!! Form::text('Ca', null, ['class' => 'form-control']) !!}
</div>

<!-- Mg Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Mg', 'Mg:') !!}
    {!! Form::text('Mg', null, ['class' => 'form-control']) !!}
</div>

<!-- K Field -->
<div class="form-group col-sm-6">
    {!! Form::label('K', 'K:') !!}
    {!! Form::text('K', null, ['class' => 'form-control']) !!}
</div>

<!-- Na Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Na', 'Na:') !!}
    {!! Form::text('Na', null, ['class' => 'form-control']) !!}
</div>

<!-- Na2Co3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Na2CO3', 'Na2Co3:') !!}
    {!! Form::text('Na2CO3', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('saltDetails.index') }}" class="btn btn-secondary">Cancel</a>
</div>
