<div class="table-responsive-sm">
    <table class="table table-striped" id="farmedTypes-table">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farmedTypes as $farmedType)
            <tr>
                <td>{{ $farmedType->name }}</td>
                <td>
                    {!! Form::open(['route' => ['farmedTypes.destroy', $farmedType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmedTypes.show', [$farmedType->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmedTypes.edit', [$farmedType->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>