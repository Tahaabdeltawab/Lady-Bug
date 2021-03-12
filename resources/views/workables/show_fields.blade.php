<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $workable->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $workable->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $workable->updated_at }}</p>
</div>

<!-- Worker Id Field -->
<div class="form-group">
    {!! Form::label('worker_id', 'Worker Id:') !!}
    <p>{{ $workable->worker_id }}</p>
</div>

<!-- Workable Id Field -->
<div class="form-group">
    {!! Form::label('workable_id', 'Workable Id:') !!}
    <p>{{ $workable->workable_id }}</p>
</div>

<!-- Workable Type Field -->
<div class="form-group">
    {!! Form::label('workable_type', 'Workable Type:') !!}
    <p>{{ $workable->workable_type }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $workable->status }}</p>
</div>

