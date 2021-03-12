<!-- Asset Name Field -->
{{--<div class="form-group col-sm-6">
    {!! Form::label('asset_name', 'Asset Name:') !!}
    {!! Form::text('asset_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Asset Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('asset_url', 'Asset Url:') !!}
    {!! Form::text('asset_url', null, ['class' => 'form-control']) !!}
</div>--}}

<div class="form-group col-sm-6">
    {!! Form::label('asset', 'Asset Url:') !!}
    {!! Form::file('asset', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('assets.index') }}" class="btn btn-secondary">Cancel</a>
</div>
