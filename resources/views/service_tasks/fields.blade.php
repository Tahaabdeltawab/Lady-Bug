<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Start At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_at', 'Start At:') !!}
    {!! Form::text('start_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Notify At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notify_at', 'Notify At:') !!}
    {!! Form::text('notify_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Quantity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quantity', 'Quantity:') !!}
    {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
</div>

<!-- Quantity Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quantity_unit_id', 'Quantity Unit Id:') !!}
    {!! Form::text('quantity_unit_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Due At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('due_at', 'Due At:') !!}
    {!! Form::text('due_at', null, ['class' => 'form-control','id'=>'due_at']) !!}
</div>

@push('scripts')
   <script type="text/javascript">
           $('#due_at').datetimepicker({
               format: 'YYYY-MM-DD HH:mm:ss',
               useCurrent: true,
               icons: {
                   up: "icon-arrow-up-circle icons font-2xl",
                   down: "icon-arrow-down-circle icons font-2xl"
               },
               sideBySide: true
           })
       </script>
@endpush


<!-- Done Field -->
<div class="form-group col-sm-6">
    {!! Form::label('done', 'Done:') !!}
    {!! Form::text('done', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('serviceTasks.index') }}" class="btn btn-secondary">Cancel</a>
</div>
