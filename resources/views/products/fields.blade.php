<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- City Field -->
<div class="form-group col-sm-6">
    {!! Form::label('city', 'City:') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
</div>

<!-- District Field -->
<div class="form-group col-sm-6">
    {!! Form::label('district', 'District:') !!}
    {!! Form::text('district', null, ['class' => 'form-control']) !!}
</div>

<!-- Seller Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('seller_mobile', 'Seller Mobile:') !!}
    {!! Form::text('seller_mobile', null, ['class' => 'form-control','maxlength' => 20]) !!}
</div>

<!-- Sold Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sold', 'Sold:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('sold', 0) !!}
        {!! Form::checkbox('sold', '1', null) !!}
    </label>
</div>


<!-- Other Links Field -->
<div class="form-group col-sm-6">
    {!! Form::label('other_links', 'Other Links:') !!}
    {!! Form::text('other_links', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
</div>
