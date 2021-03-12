@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{!! route('chemicalDetails.index') !!}">Chemical Detail</a>
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
                              <strong>Edit Chemical Detail</strong>
                          </div>
                          <div class="card-body">
                              {!! Form::model($chemicalDetail, ['route' => ['chemicalDetails.update', $chemicalDetail->id], 'method' => 'patch']) !!}

                              @include('chemical_details.fields')

                              {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
         </div>
    </div>
@endsection