@extends('layouts.app')

@section('content')
  <div class="container-fluid">
        <div class="animated fadeIn">
             <div class="row">

            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/app.js')}}"></script>
<script>
    Echo.channel('new_comment_channel').listen('NewCommentEvent', function(data){
        console.log(data.comment);
    })

    Echo.channel('new_comment_channel2').listen('NewCommentEvent2', function(data){
        console.log(data.comment);
    })
</script>
@endsection
