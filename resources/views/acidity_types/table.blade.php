<div class="table-responsive-sm">
    <table class="table table-striped" id="acidityTypes-table">
        <thead>
            <tr>
                
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($acidityTypes as $acidityType)
            <tr>
                
                <td>
                    {!! Form::open(['route' => ['acidityTypes.destroy', $acidityType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('acidityTypes.show', [$acidityType->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('acidityTypes.edit', [$acidityType->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>