@extends('layouts.app')
@section('content')
<div class="row">
<div class="col-sm-12">
  <h1 class="text-center my-4">To-do list 
  </h1>
  
  <!-- alert -->
  <div id="div_alert" style="display:none;" class="alert alert-success" role="alert">
    <b><div class="div_alert_content">
    </div></b>
  </div>
  <!-- /alert -->

</div>
<div class="col-sm-12 my-4">
<button class="text-right btn btn-success" data-toggle="modal" data-target="#modal_create_todo">
  <i class="fas fa-plus mr-2"></i>新增...

</button>
</div>



<!-- #modal_create_todo -->
<div class="modal fade" id="modal_create_todo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">新增待辦事項</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_create_todo">
          <div class="form-group row">
            <label for="input_title" class="col-sm-2 col-form-label">標題</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="input_title" name="title">
            </div>
          </div>
          <div class="form-group row">
            <label for="input_content" class="col-sm-2 col-form-label">內容</label>
            <div class="col-sm-10">
              <textarea name="content" class="form-control" id="input_content" cols="20" rows="5"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="input_due" class="col-sm-2 col-form-label">日期</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="input_due" name="due">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <span id="span_ajax_loader" class="d-none">
          請稍候...
          <img src="/img/ajax-loader.gif" class="img-responsive" style="width: 20px;" alt="">
        </span>
        <span id="span_msg"></span>
        <button type="button" id="btn_cancel" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" id="btn_store_todo" class="btn btn-info">
          新增
        </button>
      </div>
    </div>
  </div>
</div>
<!-- /#modal_create_todo -->

<script>

  //https://hackmd.io/b9VDI2JDTs--Qp_cOrO3qg
  function reset_modal(modal_id_with_hash){
    $(modal_id_with_hash+" #span_ajax_loader").addClass("d-none");
    $(modal_id_with_hash+" #span_msg").html("");
    $("#modal_create_todo #span_msg").removeClass();

    $(':input',modal_id_with_hash)
      .not(':button, :submit, :reset, :hidden')
      .val('')
      .prop('checked', false)
      .prop('selected', false);
  }
  // function toggle_modal_btn(modal_id_with_hash){
  //   $("#modal_create_todo button").attr("disabled",function(index,attr){
  //     attr=="disabled" ? null : "disabled";
  //   })
  // }

  $(document).ready(function(){

  //clear input when close modal_create_todo
  $("#modal_create_todo button[ data-dismiss='modal']").on("click",function(){
    reset_modal('#modal_create_todo');
  })
  $("#modal_create_todo #btn_cancel").trigger("click");

  //store new todo
  $("#btn_store_todo").on("click",function(){

    $("#modal_create_todo #span_ajax_loader").removeClass("d-none");
    $("#modal_create_todo #span_msg").removeClass().html("");
    $("#modal_create_todo button").attr("disabled","disabled");



    //https://ithelp.ithome.com.tw/articles/10212120
    axios({
      method: 'POST',
      url: "{{ route('todo.store') }}",
      data: {
        form_data: $('#form_create_todo').serializeArray()
      }
    })
    .then(function(data){
      console.log('success');
      console.log(data);
      $("#modal_create_todo button").removeAttr("disabled");
      $("#modal_create_todo #btn_cancel").trigger("click");
      $("#div_alert .div_alert_content").html("待辦事項已新增");
      $("#div_alert").fadeIn("2000",function(){
        $(this).delay(1500).fadeOut("1000",function(){
        $("#div_alert .div_alert_content").html("");
      });
      })

    })
    .catch(function(data){
      console.log('failed');
      console.log(data);
      $("#modal_create_todo button").removeAttr("disabled");
      $("#modal_create_todo #span_msg").html("發生錯誤,請聯絡系統管理員");
      $("#modal_create_todo #span_msg").addClass("text-danger");
    })
    .finally(function(){
      $("#modal_create_todo #span_ajax_loader").addClass("d-none");
    })

  })

      
})//end document ready


</script>

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
