<div class="table-responsive-sm">
    <table class="table table-striped" id="farmedTypeClasses-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Farmed Type Id</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farmedTypeClasses as $farmedTypeClass)
            <tr>
                <td>{{ $farmedTypeClass->name }}</td>
            <td>{{ $farmedTypeClass->farmed_type_id }}</td>
                <td>
                    {!! Form::open(['route' => ['farmedTypeClasses.destroy', $farmedTypeClass->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmedTypeClasses.show', [$farmedTypeClass->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmedTypeClasses.edit', [$farmedTypeClass->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>