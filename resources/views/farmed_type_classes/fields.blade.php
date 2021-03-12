<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Farmed Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farmed_type_id', 'Farmed Type Id:') !!}
    {!! Form::text('farmed_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('farmedTypeClasses.index') }}" class="btn btn-secondary">Cancel</a>
</div>
