<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Acidity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('acidity', 'Acidity:') !!}
    {!! Form::text('acidity', null, ['class' => 'form-control']) !!}
</div>

<!-- Acidity Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('acidity_value', 'Acidity Value:') !!}
    {!! Form::text('acidity_value', null, ['class' => 'form-control']) !!}
</div>

<!-- Acidity Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('acidity_unit_id', 'Acidity Unit Id:') !!}
    {!! Form::text('acidity_unit_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Salt Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salt_type', 'Salt Type:') !!}
    {!! Form::text('salt_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Salt Concentration Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salt_concentration_value', 'Salt Concentration Value:') !!}
    {!! Form::text('salt_concentration_value', null, ['class' => 'form-control']) !!}
</div>

<!-- Salt Concentration Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salt_concentration_unit_id', 'Salt Concentration Unit Id:') !!}
    {!! Form::text('salt_concentration_unit_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('chemicalDetails.index') }}" class="btn btn-secondary">Cancel</a>
</div>
