<div class="table-responsive-sm">
    <table class="table table-striped" id="saltTypes-table">
        <thead>
            <tr>
                <th>Type</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($saltTypes as $saltType)
            <tr>
                <td>{{ $saltType->type }}</td>
                <td>
                    {!! Form::open(['route' => ['saltTypes.destroy', $saltType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('saltTypes.show', [$saltType->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('saltTypes.edit', [$saltType->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>