<div class="table-responsive-sm">
    <table class="table table-striped" id="locations-table">
        <thead>
            <tr>
                <th>Latitude</th>
        <th>Longitude</th>
        <th>Country</th>
        <th>City</th>
        <th>District</th>
        <th>Details</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($locations as $location)
            <tr>
                <td>{{ $location->latitude }}</td>
            <td>{{ $location->longitude }}</td>
            <td>{{ $location->country }}</td>
            <td>{{ $location->city }}</td>
            <td>{{ $location->district }}</td>
            <td>{{ $location->details }}</td>
                <td>
                    {!! Form::open(['route' => ['locations.destroy', $location->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('locations.show', [$location->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('locations.edit', [$location->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>