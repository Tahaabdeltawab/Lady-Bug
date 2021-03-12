<div class="table-responsive-sm">
    <table class="table table-striped" id="buyingNotes-table">
        <thead>
            <tr>
                <th>Content</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($buyingNotes as $buyingNote)
            <tr>
                <td>{{ $buyingNote->content }}</td>
                <td>
                    {!! Form::open(['route' => ['buyingNotes.destroy', $buyingNote->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('buyingNotes.show', [$buyingNote->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('buyingNotes.edit', [$buyingNote->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>