<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Workable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('workable_type', 'Workable Type:') !!}
    <select name="workable_type_id" class="form-control" id="workable_type">
        @foreach($workableTypes as $type)
            <option value="{{$type->id}}" {{@(($type->id == $workableRole->workable_type_id) ? "selected" : "")}} >{{$type->name}}</option>
        @endforeach
    </select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('workablePermissions.index') }}" class="btn btn-secondary">Cancel</a>
</div>
