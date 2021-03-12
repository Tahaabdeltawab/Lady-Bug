<!-- Real Field -->
<div class="form-group col-sm-6">
    {!! Form::label('real', 'Real:') !!}
    {!! Form::text('real', null, ['class' => 'form-control']) !!}
</div>

<!-- Archived Field -->
<div class="form-group col-sm-6">
    {!! Form::label('archived', 'Archived:') !!}
    {!! Form::text('archived', null, ['class' => 'form-control']) !!}
</div>

<!-- Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('location', 'Location:') !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
</div>

<!-- Farming Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farming_date', 'Farming Date:') !!}
    {!! Form::text('farming_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Farming Compatibility Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farming_compatibility', 'Farming Compatibility:') !!}
    {!! Form::text('farming_compatibility', null, ['class' => 'form-control']) !!}
</div>

<!-- Home Plant Pot Size Field -->
<div class="form-group col-sm-6">
    {!! Form::label('home_plant_pot_size', 'Home Plant Pot Size:') !!}
    {!! Form::text('home_plant_pot_size', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area', 'Area:') !!}
    {!! Form::text('area', null, ['class' => 'form-control']) !!}
</div>

<!-- Area Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_unit_id', 'Area Unit Id:') !!}
    {!! Form::text('area_unit_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Soil Detail Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('soil_detail_id', 'Soil Detail Id:') !!}
    {!! Form::text('soil_detail_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Irrigation Water Detail Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('irrigation_water_detail_id', 'Irrigation Water Detail Id:') !!}
    {!! Form::text('irrigation_water_detail_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Animal Drink Water Salt Detail Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('animal_drink_water_salt_detail_id', 'Animal Drink Water Salt Detail Id:') !!}
    {!! Form::text('animal_drink_water_salt_detail_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('farmns.index') }}" class="btn btn-secondary">Cancel</a>
</div>
