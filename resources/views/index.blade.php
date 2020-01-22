@extends('layouts.app')
@section('content')
<div class="row">
<div class="col-sm-12">
  <h1 class="text-center my-4">To-do list 
  </h1>
</div>
<div class="col-sm-12 my-4">
  <a href="" class="text-right btn btn-success"><i class="fas fa-plus mr-2"></i>新增待辦事項...</a>
</div>

  @if (count($todos)>0)
  @foreach ($todos as $item)
    <div class="col-sm-4 my-2">
      <div class="card">
        <div class="card-body">
           <h5 class="card-title border-bottom pb-3">
             {{ $item['title'] }}
             <a href="javascript:void(0);" class="btn btn-warning float-right">
              <i class="fas fa-trash-alt"></i>
            </a>
             <a href="javascript:void(0);" class="btn btn-info float-right mx-2">
              <i class="fas fa-pen"></i>
            </a>
          </h5>
           <p class="card-text">{{ $item['content'] }}</p>
        <a class="btn btn-success btn-sm" href="{{ route("todo.show", $item['id']) }}">detail...</a>
        </div>
        <div class="card-footer text-muted">
          {{ $item['due'] }}
        </div>
      </div>
     </div>
      @endforeach
    @else
        <div>to to list is empty...</div>
    @endif

</div>

<style>
  .card{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>


@endsection
