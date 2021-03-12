<div class="table-responsive-sm">
    <table class="table table-striped" id="saltDetails-table">
        <thead>
            <tr>
                <th>Type</th>
        <th>Ph</th>
        <th>Co3</th>
        <th>Hco3</th>
        <th>Cl</th>
        <th>So4</th>
        <th>Ca</th>
        <th>Mg</th>
        <th>K</th>
        <th>Na</th>
        <th>Na2Co3</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($saltDetails as $saltDetail)
            <tr>
                <td>{{ $saltDetail->type }}</td>
            <td>{{ $saltDetail->PH }}</td>
            <td>{{ $saltDetail->CO3 }}</td>
            <td>{{ $saltDetail->HCO3 }}</td>
            <td>{{ $saltDetail->Cl }}</td>
            <td>{{ $saltDetail->SO4 }}</td>
            <td>{{ $saltDetail->Ca }}</td>
            <td>{{ $saltDetail->Mg }}</td>
            <td>{{ $saltDetail->K }}</td>
            <td>{{ $saltDetail->Na }}</td>
            <td>{{ $saltDetail->Na2CO3 }}</td>
                <td>
                    {!! Form::open(['route' => ['saltDetails.destroy', $saltDetail->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('saltDetails.show', [$saltDetail->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('saltDetails.edit', [$saltDetail->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>