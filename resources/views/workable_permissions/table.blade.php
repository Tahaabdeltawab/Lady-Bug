<div class="table-responsive-sm">
    <table class="table table-striped" id="workablePermissions-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Workable Type</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($workablePermissions as $workablePermission)
            <tr>
                <td>{{ $workablePermission->name }}</td>
            <td>{{ @$workablePermission->workable_type->name }}</td>
                <td>
                    {!! Form::open(['route' => ['workablePermissions.destroy', $workablePermission->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('workablePermissions.show', [$workablePermission->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('workablePermissions.edit', [$workablePermission->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
