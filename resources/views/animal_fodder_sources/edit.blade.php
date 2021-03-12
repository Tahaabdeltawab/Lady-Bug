@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{!! route('animalFodderSources.index') !!}">Animal Fodder Source</a>
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
                              <strong>Edit Animal Fodder Source</strong>
                          </div>
                          <div class="card-body">
                              {!! Form::model($animalFodderSource, ['route' => ['animalFodderSources.update', $animalFodderSource->id], 'method' => 'patch']) !!}

                              @include('animal_fodder_sources.fields')

                              {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
         </div>
    </div>
@endsection