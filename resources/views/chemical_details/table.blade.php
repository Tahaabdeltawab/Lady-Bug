<div class="table-responsive-sm">
    <table class="table table-striped" id="chemicalDetails-table">
        <thead>
            <tr>
                <th>Type</th>
        <th>Acidity</th>
        <th>Acidity Value</th>
        <th>Acidity Unit Id</th>
        <th>Salt Type</th>
        <th>Salt Concentration Value</th>
        <th>Salt Concentration Unit Id</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($chemicalDetails as $chemicalDetail)
            <tr>
                <td>{{ $chemicalDetail->type }}</td>
            <td>{{ $chemicalDetail->acidity }}</td>
            <td>{{ $chemicalDetail->acidity_value }}</td>
            <td>{{ $chemicalDetail->acidity_unit_id }}</td>
            <td>{{ $chemicalDetail->salt_type }}</td>
            <td>{{ $chemicalDetail->salt_concentration_value }}</td>
            <td>{{ $chemicalDetail->salt_concentration_unit_id }}</td>
                <td>
                    {!! Form::open(['route' => ['chemicalDetails.destroy', $chemicalDetail->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('chemicalDetails.show', [$chemicalDetail->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('chemicalDetails.edit', [$chemicalDetail->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>