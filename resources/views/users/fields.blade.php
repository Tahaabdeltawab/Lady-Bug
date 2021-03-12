<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {{-- {!! Form::text('password', null, ['class' => 'form-control']) !!} --}}
    <input type="text" name="password" class="form-control" id="password" >
</div>
{{--
<!-- Roles Field -->
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Roles</h4>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        @forelse ($workableRoles as $item)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="workableRoles[]" value="{{ $item->id }}" {{ @in_array($item->id,$wuserHaswRoles) ? 'checked' : '' }} class="form-check-input">
                                    {{ $item->name}}
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        @empty
                            ----
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 --}}
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
</div>
