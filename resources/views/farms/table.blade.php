<div class="table-responsive-sm">
    <table class="table table-striped" id="farms-table">
        <thead>
            <tr>
                <th>Real</th>
        <th>Archived</th>
        <th>Location</th>
        <th>Farming Date</th>
        <th>Farming Compatibility</th>
        <th>Home Plant Pot Size</th>
        <th>Area</th>
        <th>Area Unit Id</th>
        <th>Soil Detail Id</th>
        <th>Irrigation Water Detail Id</th>
        <th>Animal Drink Water Salt Detail Id</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($farms as $farm)
            <tr>
                <td>{{ $farm->real }}</td>
            <td>{{ $farm->archived }}</td>
            <td>{{ $farm->location }}</td>
            <td>{{ $farm->farming_date }}</td>
            <td>{{ $farm->farming_compatibility }}</td>
            <td>{{ $farm->home_plant_pot_size }}</td>
            <td>{{ $farm->area }}</td>
            <td>{{ $farm->area_unit_id }}</td>
            <td>{{ $farm->soil_detail_id }}</td>
            <td>{{ $farm->irrigation_water_detail_id }}</td>
            <td>{{ $farm->animal_drink_water_salt_detail_id }}</td>
                <td>
                    {!! Form::open(['route' => ['farms.destroy', $farm->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farms.show', [$farm->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farms.edit', [$farm->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>