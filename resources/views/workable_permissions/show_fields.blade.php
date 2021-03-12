<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $workablePermission->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $workablePermission->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $workablePermission->updated_at }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $workablePermission->name }}</p>
</div>

<!-- Workable Type Field -->
<div class="form-group">
    {!! Form::label('workable_type', 'Workable Type:') !!}
    <p>{{ @$workablePermission->workable_type->name }}</p>
</div>

