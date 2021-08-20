<!-- Report Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('report_type_id', 'Report Type Id:') !!}
    {!! Form::text('report_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Reportable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reportable_type', 'Reportable Type:') !!}
    {!! Form::text('reportable_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Reportable Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reportable_id', 'Reportable Id:') !!}
    {!! Form::text('reportable_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::text('image', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Cancel</a>
</div>
