@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{!! route('farms.index') !!}">Farm</a>
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
                              <strong>Edit Farm</strong>
                          </div>
                          <div class="card-body">
                              <!-- Workers Field -->
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Workers</h4>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">


                                                    @forelse ($workers as $worker)
                                                        @php
                                                            $workable = \App\Models\Workable::where([['worker_id',$worker->id], ['workable_id',$farm->id], ['workable_type','App\Models\Farm']])->first();// the same $worker->farms->find($farm->id)->pivot
                                                            $workable_roles = \App\Models\WorkableRole::whereHas('workable_type', function($q) use($workable){// the same collect($worker->farms->find($farm->id)->pivot->workable_roles)->whereHas...
                                                                $q->where('name', $workable->workable_type);
                                                            })->get();
                                                            $wWorkerHaswRoles = collect($workable->workable_roles)->pluck('id')->all();
                                                            // $workable->workable_roles()->sync($request->workable_roles);
                                                        @endphp

                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rolesModal_{{$worker->id}}" data-worker_id="{{$worker->id}}">{{$worker->name}}</button><br><br>
                                                        <div class="modal fade" id="rolesModal_{{$worker->id}}" tabindex="-1" role="dialog" aria-labelledby="rolesModal_{{$worker->id}}Label" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="rolesModal_{{$worker->id}}Label">Roles</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        {!! Form::model($farm, ['route' => ['farms.roles.update', $workable->id], 'method' => 'post']) !!}
                                                                        @forelse ($workable_roles as $workable_role)
                                                                            <div class="form-check" style="margin: 3px 0px 3px 20px">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" name="workable_roles[]" value="{{ $workable_role->id }}" {{ in_array($workable_role->id,$wWorkerHaswRoles) ? 'checked' : '' }} class="form-check-input">
                                                                                    {{ $workable_role->name}} - {{$workable_role->workable_permissions->pluck('name')}}
                                                                                    <i class="input-helper"></i>
                                                                                </label>
                                                                            </div>
                                                                        @empty
                                                                            ----
                                                                        @endforelse
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div class="form-group col-sm-12">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                                                        </div>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @push('scripts')
                                                            <script>
                                                            $("#rolesModal_{{$worker->id}}").on('show.bs.modal', function (event) {
                                                                var button = $(event.relatedTarget)
                                                                var worker_id = button.data('worker_id')
                                                                var worker_name = button.text()
                                                                var modal = $(this)
                                                                modal.find('.modal-title').text(worker_name + " roles")
                                                                // modal.find('.modal-body input').val(worker_id)
                                                            })
                                                            </script>
                                                        @endpush
                                                    @empty
                                                        ----
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>
                </div>
         </div>
    </div>
@endsection
