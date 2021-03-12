<div class="table-responsive-sm">
    <table class="table table-striped" id="workables-table">
        <thead>
            <tr>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($workables as $workable)
            <tr>
                <td>{{ $workable->status }}</td>
                <td>
                    {!! Form::open(['route' => ['workables.destroy', $workable->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('workables.show', [$workable->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('workables.edit', [$workable->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>