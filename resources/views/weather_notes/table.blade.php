<div class="table-responsive-sm">
    <table class="table table-striped" id="weatherNotes-table">
        <thead>
            <tr>
                <th>Content</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($weatherNotes as $weatherNote)
            <tr>
                <td>{{ $weatherNote->content }}</td>
                <td>
                    {!! Form::open(['route' => ['weatherNotes.destroy', $weatherNote->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('weatherNotes.show', [$weatherNote->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('weatherNotes.edit', [$weatherNote->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>