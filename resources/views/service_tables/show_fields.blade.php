<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $serviceTable->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $serviceTable->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $serviceTable->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $serviceTable->name }}</p>
</div>

<!-- Farm Id Field -->
<div class="form-group">
    {!! Form::label('farm_id', 'Farm Id:') !!}
    <p>{{ $serviceTable->farm_id }}</p>
</div>

