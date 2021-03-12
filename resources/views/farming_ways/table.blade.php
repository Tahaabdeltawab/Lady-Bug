<div class="table-responsive-sm">
    <table class="table table-striped" id="farmingWays-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Type</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farmingWays as $farmingWay)
            <tr>
                <td>{{ $farmingWay->name }}</td>
            <td>{{ $farmingWay->type }}</td>
                <td>
                    {!! Form::open(['route' => ['farmingWays.destroy', $farmingWay->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmingWays.show', [$farmingWay->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmingWays.edit', [$farmingWay->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>