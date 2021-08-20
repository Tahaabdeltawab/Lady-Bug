<div class="table-responsive-sm">
    <table class="table table-striped" id="reports-table">
        <thead>
            <tr>
                <th>Report Type Id</th>
        <th>Reportable Type</th>
        <th>Reportable Id</th>
        <th>Description</th>
        <th>Image</th>
        <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $report->report_type_id }}</td>
            <td>{{ $report->reportable_type }}</td>
            <td>{{ $report->reportable_id }}</td>
            <td>{{ $report->description }}</td>
            <td>{{ $report->image }}</td>
            <td>{{ $report->status }}</td>
                <td>
                    {!! Form::open(['route' => ['reports.destroy', $report->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('reports.show', [$report->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('reports.edit', [$report->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>