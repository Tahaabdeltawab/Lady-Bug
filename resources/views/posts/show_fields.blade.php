<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $post->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $post->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $post->updated_at }}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{{ $post->title }}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! Form::label('content', 'Content:') !!}
    <p>{{ $post->content }}</p>
</div>

<!-- Author Id Field -->
<div class="form-group">
    {!! Form::label('author_id', 'Author Id:') !!}
    <p>{{ $post->author_id }}</p>
</div>

<!-- Farm Id Field -->
<div class="form-group">
    {!! Form::label('farm_id', 'Farm Id:') !!}
    <p>{{ $post->farm_id }}</p>
</div>

<!-- Farmed Type Id Field -->
<div class="form-group">
    {!! Form::label('farmed_type_id', 'Farmed Type Id:') !!}
    <p>{{ $post->farmed_type_id }}</p>
</div>

<!-- Post Type Id Field -->
<div class="form-group">
    {!! Form::label('post_type_id', 'Post Type Id:') !!}
    <p>{{ $post->post_type_id }}</p>
</div>

<!-- Solved Field -->
<div class="form-group">
    {!! Form::label('solved', 'Solved:') !!}
    <p>{{ $post->solved }}</p>
</div>

