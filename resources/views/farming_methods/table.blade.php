<div class="table-responsive-sm">
    <table class="table table-striped" id="farmingMethods-table">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farmingMethods as $farmingMethod)
            <tr>
                <td>{{ $farmingMethod->name }}</td>
                <td>
                    {!! Form::open(['route' => ['farmingMethods.destroy', $farmingMethod->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmingMethods.show', [$farmingMethod->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmingMethods.edit', [$farmingMethod->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>