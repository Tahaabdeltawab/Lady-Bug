<div class="table-responsive-sm">
    <table class="table table-striped" id="homePlantPotSizes-table">
        <thead>
            <tr>
                <th>Size</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($homePlantPotSizes as $homePlantPotSize)
            <tr>
                <td>{{ $homePlantPotSize->size }}</td>
                <td>
                    {!! Form::open(['route' => ['homePlantPotSizes.destroy', $homePlantPotSize->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('homePlantPotSizes.show', [$homePlantPotSize->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('homePlantPotSizes.edit', [$homePlantPotSize->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>