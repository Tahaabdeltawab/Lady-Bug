<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $serviceTask->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $serviceTask->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $serviceTask->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $serviceTask->name }}</p>
</div>

<!-- Start At Field -->
<div class="form-group">
    {!! Form::label('start_at', 'Start At:') !!}
    <p>{{ $serviceTask->start_at }}</p>
</div>

<!-- Notify At Field -->
<div class="form-group">
    {!! Form::label('notify_at', 'Notify At:') !!}
    <p>{{ $serviceTask->notify_at }}</p>
</div>

<!-- Farm Id Field -->
<div class="form-group">
    {!! Form::label('farm_id', 'Farm Id:') !!}
    <p>{{ $serviceTask->farm_id }}</p>
</div>

<!-- Service Table Id Field -->
<div class="form-group">
    {!! Form::label('service_table_id', 'Service Table Id:') !!}
    <p>{{ $serviceTask->service_table_id }}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{{ $serviceTask->type }}</p>
</div>

<!-- Quantity Field -->
<div class="form-group">
    {!! Form::label('quantity', 'Quantity:') !!}
    <p>{{ $serviceTask->quantity }}</p>
</div>

<!-- Quantity Unit Id Field -->
<div class="form-group">
    {!! Form::label('quantity_unit_id', 'Quantity Unit Id:') !!}
    <p>{{ $serviceTask->quantity_unit_id }}</p>
</div>

<!-- Due At Field -->
<div class="form-group">
    {!! Form::label('due_at', 'Due At:') !!}
    <p>{{ $serviceTask->due_at }}</p>
</div>

<!-- Done Field -->
<div class="form-group">
    {!! Form::label('done', 'Done:') !!}
    <p>{{ $serviceTask->done }}</p>
</div>

