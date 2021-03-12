<div class="table-responsive-sm">
    <table class="table table-striped" id="serviceTasks-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Start At</th>
        <th>Notify At</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Quantity Unit Id</th>
        <th>Due At</th>
        <th>Done</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($serviceTasks as $serviceTask)
            <tr>
                <td>{{ $serviceTask->name }}</td>
            <td>{{ $serviceTask->start_at }}</td>
            <td>{{ $serviceTask->notify_at }}</td>
            <td>{{ $serviceTask->type }}</td>
            <td>{{ $serviceTask->quantity }}</td>
            <td>{{ $serviceTask->quantity_unit_id }}</td>
            <td>{{ $serviceTask->due_at }}</td>
            <td>{{ $serviceTask->done }}</td>
                <td>
                    {!! Form::open(['route' => ['serviceTasks.destroy', $serviceTask->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceTasks.show', [$serviceTask->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('serviceTasks.edit', [$serviceTask->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>