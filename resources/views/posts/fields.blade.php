<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control','maxlength' => 200]) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-6">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::text('content', null, ['class' => 'form-control']) !!}
</div>

<!-- Author Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('author_id', 'Author Id:') !!}
    {!! Form::text('author_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Farm Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farm_id', 'Farm Id:') !!}
    {!! Form::text('farm_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Farmed Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('farmed_type_id', 'Farmed Type Id:') !!}
    {!! Form::text('farmed_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Post Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('post_type_id', 'Post Type Id:') !!}
    {!! Form::text('post_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Solved Field -->
<div class="form-group col-sm-6">
    {!! Form::label('solved', 'Solved:') !!}
    {!! Form::text('solved', null, ['class' => 'form-control']) !!}
</div>

<!-- Assets  -->
<div class="form-group col-sm-6">
    {!! Form::label('asset', 'Asset Url:') !!}
    <input type="file" name="assets[]" multiple>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
</div>
