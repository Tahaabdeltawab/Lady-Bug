<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $measuringUnit->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $measuringUnit->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $measuringUnit->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $measuringUnit->name }}</p>
</div>

<!-- Measurable Field -->
<div class="form-group">
    {!! Form::label('measurable', 'Measurable:') !!}
    <p>{{ $measuringUnit->measurable }}</p>
</div>

