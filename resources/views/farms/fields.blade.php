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


@if(CheckPermission::instance()->allowed_permissions('edit-users', auth()->id(), @$farm->id))
    <!-- Workers Field -->
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Workers</h4>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            @forelse ($workers as $worker)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="workers[]" value="{{ $worker->id }}" {{ @in_array($worker->id,$workableHasWorkers) ? 'checked' : '' }} class="form-check-input">
                                        {{ $worker->name}}
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                                @php
                                    $wWorkerHaswRoles = (request()->is('*/edit') && $worker->farms->find($farm->id)) ? $worker->farms->find($farm->id)->pivot->workable_roles->pluck('id')->all() : [];
                                @endphp
                                @forelse ($workable_roles as $workable_role)
                                    <div class="form-check ml-3" @if($loop->last) style="margin-bottom: 30px" @endif>
                                        <label class="form-check-label">
                                        <input type="checkbox" name="workable_roles_{{$worker->id}}[]" value="{{ $workable_role->id }}" {{ @in_array($workable_role->id,$wWorkerHaswRoles) ? 'checked' : '' }} class="form-check-input">
                                            {{ $workable_role->name}} - {{$workable_role->workable_permissions->pluck('name')}}
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                @empty
                                    ----
                                @endforelse

                            @empty
                                ----
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('farms.index') }}" class="btn btn-secondary">Cancel</a>
</div>
