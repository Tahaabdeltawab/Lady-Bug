<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $report->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $report->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $report->updated_at }}</p>
</div>

<!-- Report Type Id Field -->
<div class="form-group">
    {!! Form::label('report_type_id', 'Report Type Id:') !!}
    <p>{{ $report->report_type_id }}</p>
</div>

<!-- Reportable Type Field -->
<div class="form-group">
    {!! Form::label('reportable_type', 'Reportable Type:') !!}
    <p>{{ $report->reportable_type }}</p>
</div>

<!-- Reportable Id Field -->
<div class="form-group">
    {!! Form::label('reportable_id', 'Reportable Id:') !!}
    <p>{{ $report->reportable_id }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $report->description }}</p>
</div>

<!-- Image Field -->
<div class="form-group">
    {!! Form::label('image', 'Image:') !!}
    <p>{{ $report->image }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $report->status }}</p>
</div>

