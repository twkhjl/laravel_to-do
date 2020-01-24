@extends('layouts.app')
@section('content')
<div class="row">
<div class="col-sm-12">


  <!-- alert -->
  <div id="div_alert" style="position:fixed; z-index:99;top:10%;left:auto;display:none;" class="alert alert-success" role="alert">
    <b><div class="div_alert_content">
    </div></b>
  </div>
  <!-- /alert -->

</div>

<!-- #modal_create_todo -->
<div class="col-sm-12 my-4">
<button id= "btn_create_todo" class="text-right btn btn-success" data-toggle="modal" data-target="#modal_create_todo">
  <i class="fas fa-plus mr-2"></i>新增...
</button>
</div>

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
          {{-- <div class="form-group row">
            <label for="input_due" class="col-sm-2 col-form-label">日期</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="input_due" name="due">
            </div>
          </div> --}}
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
{{-- /#modal_create_todo --}}
{{--  #modal_create_todo script --}}
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

  //script init
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

      //update todo list
      axios({
        method:'GET',
        dataType : "html",
        url: `{{ route("todo.index") }}`
      })
      .then(function(res){

        $("#div_todos").fadeOut("300",function(){
          // $(this).html(res.data).delay(100).fadeIn("300")
          $(this).html(res.data).fadeIn("300")
        })

        $("#modal_create_todo button").removeAttr("disabled");
        $("#modal_create_todo #btn_cancel").trigger("click");
        $("#div_alert .div_alert_content").html("待辦事項已新增");
        $("#div_alert").fadeIn("2000",function(){
          $(this).delay(1500).fadeOut("1000",function(){
            $("#div_alert .div_alert_content").html("");
          });
        })

      }).catch(function(data){
          console.log('err: failure while updating todo list');
          console.log(data);
          $("#modal_create_todo button").removeAttr("disabled");
          $("#modal_create_todo #span_msg").html("發生錯誤,請聯絡系統管理員");
          $("#modal_create_todo #span_msg").addClass("text-danger");
        });
      //end update todo list


    }).catch(function(data){
    console.log('err: failure while create new todo');
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
{{-- /#modal_create_todo script --}}

{{-- ======================================================= --}}



</div>
  <div class="row" id="div_todos">
    @include('todos.inc.cards',$todos)
  </div>



@endsection
