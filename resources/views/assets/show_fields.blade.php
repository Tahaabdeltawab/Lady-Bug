<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $asset->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $asset->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $asset->updated_at }}</p>
</div>

<!-- Asset Name Field -->
<div class="form-group">
    {!! Form::label('asset_name', 'Asset Name:') !!}
    <p>{{ $asset->asset_name }}</p>
</div>

<!-- Asset Url Field -->
<div class="form-group">
    {!! Form::label('asset_url', 'Asset Url:') !!}
    <p>{{ $asset->asset_url }}</p>
</div>

<!-- Asset Size Field -->
<div class="form-group">
    {!! Form::label('asset_size', 'Asset Size:') !!}
    <p>{{ $asset->asset_size }}</p>
</div>

<!-- Asset Mime Field -->
<div class="form-group">
    {!! Form::label('asset_mime', 'Asset Mime:') !!}
    <p>{{ $asset->asset_mime }}</p>
</div>
<div>
    <img src="{{$asset->asset_url}}" alt="">
</div>

