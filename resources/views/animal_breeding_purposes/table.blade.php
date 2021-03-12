<div class="table-responsive-sm">
    <table class="table table-striped" id="animalBreedingPurposes-table">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($animalBreedingPurposes as $animalBreedingPurpose)
            <tr>
                <td>{{ $animalBreedingPurpose->name }}</td>
                <td>
                    {!! Form::open(['route' => ['animalBreedingPurposes.destroy', $animalBreedingPurpose->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('animalBreedingPurposes.show', [$animalBreedingPurpose->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('animalBreedingPurposes.edit', [$animalBreedingPurpose->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>