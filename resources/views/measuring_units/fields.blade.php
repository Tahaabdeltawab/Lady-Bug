<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Measurable Field -->
<div class="form-group col-sm-6">
    {!! Form::label('measurable', 'Measurable:') !!}
    {!! Form::text('measurable', null, ['class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('measuringUnits.index') }}" class="btn btn-secondary">Cancel</a>
</div>
