<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $workableRole->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $workableRole->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $workableRole->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $workableRole->name }}</p>
</div>

<!-- Workable Type Field -->
<div class="form-group">
    {!! Form::label('workable_type', 'Workable Type:') !!}
    <p>{{ @$workableRole->workable_type->name }}</p>
</div>

