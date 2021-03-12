<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $farmedType->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $farmedType->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $farmedType->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $farmedType->name }}</p>
</div>

<!-- Farm Activity Type Id Field -->
<div class="form-group">
    {!! Form::label('farm_activity_type_id', 'Farm Activity Type Id:') !!}
    <p>{{ $farmedType->farm_activity_type_id }}</p>
</div>

