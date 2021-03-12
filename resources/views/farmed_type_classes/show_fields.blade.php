<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $farmedTypeClass->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $farmedTypeClass->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $farmedTypeClass->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $farmedTypeClass->name }}</p>
</div>

<!-- Farmed Type Id Field -->
<div class="form-group">
    {!! Form::label('farmed_type_id', 'Farmed Type Id:') !!}
    <p>{{ $farmedTypeClass->farmed_type_id }}</p>
</div>

