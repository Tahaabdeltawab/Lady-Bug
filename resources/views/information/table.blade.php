<div class="table-responsive-sm">
    <table class="table table-striped" id="information-table">
        <thead>
            <tr>
                <th>Title</th>
        <th>Content</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($information as $information)
            <tr>
                <td>{{ $information->title }}</td>
            <td>{{ $information->content }}</td>
                <td>
                    {!! Form::open(['route' => ['information.destroy', $information->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('information.show', [$information->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('information.edit', [$information->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>