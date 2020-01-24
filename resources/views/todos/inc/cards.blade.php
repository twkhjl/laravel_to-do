
@if (count($todos)>0)
  @foreach ($todos as $item)
    <div class="col-md-4 my-2">
      <div class="card" id="card_{{ $item["id"] }}">
        <div class="card-body d-none" id="card_body_edit_{{ $item["id"] }}">

          <form id="card_body_form_{{ $item["id"] }}">
            <div class="d-none">
              <div id="old_title">{{ $item["title"] }}</div>
              <div id="old_content">{{ $item["content"] }}</div>
            </div>
{{-- 
            <div class="form-group">
              <label>id</label>
              <input type="text" name="id" class="form-control" value="{{ $item["id"] }}" readonly>
            </div> --}}

            <div class="form-group">
              <label for="title">標題</label>
              <input type="text" class="form-control" name="title" id="title" value="{{ $item["title"] }}">
            </div>
            <div class="form-group">
              <label for="title">內容</label>
              <textarea class="form-control" name="content" id="content" rows="3">{{ $item["content"] }}</textarea>
            </div>
          </form>

          <div class="d-flex justify-content-end align-items-start">
            <button class="btn btn-success mx-1" onclick="update_todo(this,{{ $item['id'] }})">
              <i class="fas fa-check"></i>更改
            </button>
            <button class="btn btn-secondary mx-1" onclick="revert_todo(this,{{ $item['id'] }})">
              <i class="fas fa-undo-alt"></i>返回
            </button>
          </div>
        </div>

        <div class="card-body" id="card_body_show_{{ $item['id'] }}">
          <h3 class="card-title border-bottom pb-3">
            {{ $item['title'] }}
          </h3>

           <p class="card-text">{{ $item['content'] }}</p>
           
           <div class="d-flex justify-content-between align-items-start" id="div_card_btn_container">
              <a class="btn btn-success" href="{{ route("todo.show", $item['id']) }}">detail...</a>
              <div class="d-flex justify-content-between align-items-start">
                <button onclick="destroy_todo(this,{{ $item['id'] }})" 
                class="btn btn-danger">
                  <i class="fas fa-trash-alt"></i>
                </button>
                <button onclick="edit_todo(this,{{ $item['id'] }})" class="btn btn-info mx-1">
                  <i class="fas fa-pen"></i>
                </button>
              </div>
          </div>
        </div>
      </div>
     </div>
      @endforeach
    @else
    <div class="col-sm-12">
      <div>沒有任何待辦事項...</div>
    </div>
    @endif

<style>
.card{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>

<script>
// function
function edit_todo(el,id)
{

  $(`#card_body_form_${id} #title`).val($(`#card_body_form_${id} #old_title`).html());
  $(`#card_body_form_${id} #content`).val($(`#card_body_form_${id} #old_content`).html());
  
  $(`#card_body_show_${id}`).addClass('d-none');
  $(`#card_body_edit_${id}`).removeClass('d-none');

  //add "d-none" css class to other card
  $.each($("#div_todos").find(".card"),function(i,v){
      if($(v).attr("id")!==$(el).closest(".card").attr("id")){
        $(v).addClass("d-none");
      }
    });

  //add "d-none" css class to #btn_create_todo
  //#btn_create_todo is located in index.blade.php
  $("#btn_create_todo").addClass("d-none");
  
}
function revert_todo(el,id)
{
  
  $(`#card_body_show_${id}`).removeClass('d-none');
  $(`#card_body_edit_${id}`).addClass('d-none');

  //reset old hidden form fields value to original
  $(`#card_body_form_${id} #title`).val($(`#card_body_form_${id} #old_title`).html());
  $(`#card_body_form_${id} #content`).val($(`#card_body_form_${id} #old_content`).html());

  $("#div_todos").find(".card").removeClass('d-none');

  //remove "d-none" css class from #btn_create_todo
  //#btn_create_todo is located in index.blade.php
  $("#btn_create_todo").removeClass("d-none");

}
function update_todo(el,id)
{

  form_data=$(`#card_body_form_${id}`).serializeArray();
  
  axios({
    url: `todo/${id}`,
    method: 'PATCH',
    data: {
      form_data: form_data
    }
  })
  .then(function(data){

    revert_todo(el,id);

    //add animation to card
    //https://stackoverflow.com/questions/3789984/jquery-how-do-i-animate-a-div-rotation/26588627
    $(el).closest('.card')
    .animate({rotation: 360},
    {
      duration: 400,
      step: function(now, fx) {
        $(this).css({"transform": "rotate("+now+"deg)"});
        // $(this).css({"transform": "rotateY("+now+"deg)"});
        // $(this).css({"transform": "rotateZ("+now+"deg)"});
        // $(this).css({"transform": "skewY("+now+"deg)"});
      }
    });
    //end add animation to card


      //update todo list
      axios({
      method:'GET',
      dataType : "html",
      url: `{{ route("todo.index") }}`
    })
    .then(function(res){

      $("#div_todos").html(res.data);


      $("#modal_create_todo button").removeAttr("disabled");
      $("#modal_create_todo #btn_cancel").trigger("click");
      $("#div_alert .div_alert_content").html("已修改");
      $("#div_alert").fadeIn("800",function(){
        $(this).delay(1500).fadeOut("1000",function(){
          $("#div_alert .div_alert_content").html("");
        });
      })

    }).catch(function(err){
        console.log('err: failure while updating todo list');
        console.log(err);
        $("#modal_create_todo button").removeAttr("disabled");
        $("#modal_create_todo #span_msg").html("發生錯誤,請聯絡系統管理員");
        $("#modal_create_todo #span_msg").addClass("text-danger");
      });
    //end update todo list
    
  })
  .catch(function(err){
    console.log(err);
    
  })
  
}
function destroy_todo(el,id)
{
  if(confirm("即將刪除,是否確定?")){
    axios({
      url:`todo/${id}`,
      method:'DELETE',
      dataType: 'json'
    })
    .then(function(res){

      //add animation to card
      $(el).closest('.card')
      .animate({opacity: 0},{
        duration: 400
      });
      //end add animation to card


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

        $("#div_alert .div_alert_content").html("待辦事項已刪除");
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

    })
    .catch(function(err){
      console.log('fail while deleting todo');
      console.log(err);
    })
  }
}
//end function

//init
$(document).ready(function(){

//end document ready  
})
</script>