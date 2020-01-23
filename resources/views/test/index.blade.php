@extends('layouts.app')

@section('content')
  <h2>test index</h2>
  <div>testing ajax</div>

<script>
  $( document ).ready(function() {
    url="{{ route('todo.show',1) }}";
    $.ajax({
      url: url,
      method:"GET",
      dataType:"json"
    })
    .then(function(data){
      console.log("success");
      console.log(data);

    })
    .fail(function(data){
      console.log("failed");
      console.log(data);
      
    })
    
  });
</script>
 
    
@endsection
