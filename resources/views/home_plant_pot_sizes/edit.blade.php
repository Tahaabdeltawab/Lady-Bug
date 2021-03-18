@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{!! route('homePlantPotSizes.index') !!}">Home Plant Pot Size</a>
          </li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
    <div class="container-fluid">
         <div class="animated fadeIn">
             @include('coreui-templates::common.errors')
             <div class="row">
                 <div class="col-lg-12">
                      <div class="card">
                          <div class="card-header">
                              <i class="fa fa-edit fa-lg"></i>
                              <strong>Edit Home Plant Pot Size</strong>
                          </div>
                          <div class="card-body">
                              {!! Form::model($homePlantPotSize, ['route' => ['homePlantPotSizes.update', $homePlantPotSize->id], 'method' => 'patch']) !!}

                              @include('home_plant_pot_sizes.fields')

                              {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
         </div>
    </div>
@endsection