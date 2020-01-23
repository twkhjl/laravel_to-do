@if (count($todos)>0)
  @foreach ($todos as $item)
    <div class="col-sm-4 my-2">
      <div class="card">
        <div class="card-body">
          <h3 class="card-title border-bottom pb-3">
            {{ $item['title'] }}
          </h3>

           <p class="card-text">{{ $item['content'] }}</p>
           
           <div class="d-flex justify-content-between align-items-start">
              <a class="btn btn-success" href="{{ route("todo.show", $item['id']) }}">detail...</a>
              <div class="d-flex justify-content-between align-items-start">
                <form method="POST" action="{{ route('todo.destroy',$item['id']) }}" class="mx-1">
                  @csrf
                  @method("DELETE")
                  <button type="submit" onclick="return confirm('即將刪除,是否確定?')" 
                  class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
                <a href="javascript:void(0);" class="btn btn-info mx-1">
                  <i class="fas fa-pen"></i>
                </a>
              </div>
          </div>
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

<style>
.card{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>