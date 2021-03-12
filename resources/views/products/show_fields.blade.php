<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $product->id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $product->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $product->updated_at }}</p>
</div>

<!-- Price Field -->
<div class="form-group">
    {!! Form::label('price', 'Price:') !!}
    <p>{{ $product->price }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $product->description }}</p>
</div>

<!-- Seller Id Field -->
<div class="form-group">
    {!! Form::label('seller_id', 'Seller Id:') !!}
    <p>{{ $product->seller_id }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $product->name }}</p>
</div>

<!-- City Field -->
<div class="form-group">
    {!! Form::label('city', 'City:') !!}
    <p>{{ $product->city }}</p>
</div>

<!-- District Field -->
<div class="form-group">
    {!! Form::label('district', 'District:') !!}
    <p>{{ $product->district }}</p>
</div>

<!-- Seller Mobile Field -->
<div class="form-group">
    {!! Form::label('seller_mobile', 'Seller Mobile:') !!}
    <p>{{ $product->seller_mobile }}</p>
</div>

<!-- Sold Field -->
<div class="form-group">
    {!! Form::label('sold', 'Sold:') !!}
    <p>{{ $product->sold }}</p>
</div>

<!-- Other Links Field -->
<div class="form-group">
    {!! Form::label('other_links', 'Other Links:') !!}
    <p>{{ $product->other_links }}</p>
</div>

