<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Workable Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('workable_type', 'Workable Type:') !!}
    <select name="workable_type_id" class="form-control" id="workable_type">
        @foreach($workableTypes as $type)
            <option value="{{$type->id}}" {{@(($type->id == $workableRole->workable_type_id) ? "selected" : "")}} >{{$type->name}}</option>
        @endforeach
    </select>
</div>
{{-- @if(Request::is('*')) --}}
    @push('scripts')
        <script>
        $(document).ready(function(){
            $(`div#workablePermissions input`).parent().parent().hide()

                $.ajaxSetup({
                        headers: {
                            // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        }
                    });

                $(document).on('change','select#workable_type',function(e){
                    // e.preventDefault();
                    workable_type_id = $(this).val();
                    workable_type_name = $(this).find(`option[value="${workable_type_id}"]`).text();
                    var data = {"workable_type_id" : ""+workable_type_id+"", "workable_type_name" : ""+workable_type_name+""};
                    var url  = "{{route('farms.permByWtype')}}";
                    var workablePermissions;
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                        success: function(data) {
                            $(`div#workablePermissions input`).parent().parent().hide()
                            $.each(data, function (key, val) {
                                $(`div#workablePermissions input[value="${val.id}"]`).parent().parent().show()
                            });

                        },
                        error: function(xhr) {
                            var response = $.parseJSON(xhr.responseText);
                            console.log(response)
                            $.each(response.errors, function (key, val) {

                            });
                        },
                        complete: function(data) {
                            // console.log(data)
                        },
                        dataType: 'json'
                    });
                })
                $('select#workable_type').trigger('change')
            })
        </script>
    @endpush
{{-- @endif --}}


<!-- Permissions Field -->
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Permissions</h4>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        @forelse ($workablePermissions as $item)
                            <div class="form-check" id="workablePermissions">
                                <label class="form-check-label">
                                    <input id="workablePermission_{{$item->id}}" type="checkbox" name="workablePermissions[]" value="{{ $item->id }}" {{ @in_array($item->id,$wroleHaswPermissions) ? 'checked' : '' }} class="form-check-input">
                                    {{ $item->name." (".$item->workable_type->name.")"}}
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        @empty
                            ----
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('workableRoles.index') }}" class="btn btn-secondary">Cancel</a>
</div>
