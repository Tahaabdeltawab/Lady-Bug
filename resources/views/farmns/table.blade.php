<div class="table-responsive-sm">
    <table class="table table-striped" id="farmns-table">
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
        @foreach($farmns as $farmn)
            <tr>
                <td>{{ $farmn->real }}</td>
            <td>{{ $farmn->archived }}</td>
            <td>{{ $farmn->location }}</td>
            <td>{{ $farmn->farming_date }}</td>
            <td>{{ $farmn->farming_compatibility }}</td>
            <td>{{ $farmn->home_plant_pot_size }}</td>
            <td>{{ $farmn->area }}</td>
            <td>{{ $farmn->area_unit_id }}</td>
            <td>{{ $farmn->soil_detail_id }}</td>
            <td>{{ $farmn->irrigation_water_detail_id }}</td>
            <td>{{ $farmn->animal_drink_water_salt_detail_id }}</td>
                <td>
                    {!! Form::open(['route' => ['farmns.destroy', $farmn->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('farmns.show', [$farmn->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('farmns.edit', [$farmn->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>