<div class="table-responsive-sm">
    <table class="table table-striped" id="taskTypes-table">
        <thead>
            <tr>
                
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($taskTypes as $taskType)
            <tr>
                
                <td>
                    {!! Form::open(['route' => ['taskTypes.destroy', $taskType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('taskTypes.show', [$taskType->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('taskTypes.edit', [$taskType->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>