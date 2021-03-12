<div class="table-responsive-sm">
    <table class="table table-striped" id="products-table">
        <thead>
            <tr>
                <th>Price</th>
        <th>Description</th>
        <th>Name</th>
        <th>City</th>
        <th>District</th>
        <th>Seller Mobile</th>
        <th>Sold</th>
        <th>Other Links</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->price }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->city }}</td>
            <td>{{ $product->district }}</td>
            <td>{{ $product->seller_mobile }}</td>
            <td>{{ $product->sold }}</td>
            <td>{{ $product->other_links }}</td>
                <td>
                    {!! Form::open(['route' => ['products.destroy', $product->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('products.show', [$product->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('products.edit', [$product->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>