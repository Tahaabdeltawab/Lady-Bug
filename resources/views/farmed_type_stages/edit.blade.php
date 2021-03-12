@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{!! route('farmedTypeStages.index') !!}">Farmed Type Stage</a>
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
                              <strong>Edit Farmed Type Stage</strong>
                          </div>
                          <div class="card-body">
                              {!! Form::model($farmedTypeStage, ['route' => ['farmedTypeStages.update', $farmedTypeStage->id], 'method' => 'patch']) !!}

                              @include('farmed_type_stages.fields')

                              {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
         </div>
    </div>
@endsection