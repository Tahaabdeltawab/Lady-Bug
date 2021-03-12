<div class="table-responsive-sm">
    <table class="table table-striped" id="farmedTypeStages-table">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farmedTypeStages as $farmedTypeStage)
            <tr>
                <td>{{ $farmedTypeStage->name }}</td>
                <td>
                    {!! Form::open(['route' => ['farmedTypeStages.destroy', $farmedTypeStage->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmedTypeStages.show', [$farmedTypeStage->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmedTypeStages.edit', [$farmedTypeStage->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>