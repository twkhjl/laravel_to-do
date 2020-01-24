
@if (count($todos)>0)
{{-- HTML part --}}
  @foreach ($todos as $item)
    <div class="col-md-4 my-2">
      {{-- hidden card --}}
      {{-- this part is hidden by default,only show when user click "edit" --}}
      <div class="card" style="display:none;" id="card_hidden_{{ $item['id'] }}">
        <div class="card-body">
          <form>
            <div class="d-none">
              <div id="old_title">{{ $item["title"] }}</div>
              <div id="old_content">{{ $item["content"] }}</div>
            </div>
            <div class="form-group">
              <label for="title">標題</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ $item["title"] }}">
            </div>
            <div class="form-group">
              <label for="title">內容</label>
              <textarea class="form-control" id="content" name="content" rows="3">{{ $item["content"] }}</textarea>
            </div>
          </form>
          <div class="d-flex justify-content-end align-items-start">
            <button class="btn btn-success mx-1" onclick="update_todo(this,{{ $item['id'] }})">
              <i class="fas fa-check"></i>更改
            </button>
            <button onclick="revert_todo(this,{{ $item['id'] }})" class="btn btn-secondary mx-1">
              <i class="fas fa-undo-alt"></i>返回
            </button>
          </div>
        </div>
      </div>
{{--end hidden card --}}

      <div class="card" id="card_{{ $item['id'] }}">
        <h3 class="card-header card-title border-bottom pb-3">
          {{ $item['title'] }}
        </h3>
        <div class="card-body">
           <p class="card-text">{{ $item['content'] }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-start" id="div_card_btn_container">
          <button class="btn btn-success" onclick="show_todo(this,{{ $item['id'] }})">detail...</button>
          {{-- <a class="btn btn-success" href="{{ route("todo.show", $item['id']) }}">detail...</a> --}}
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
    {{-- /.col-md-4 my-2 --}}

      @endforeach
    <div id="div_show_todo"></div>

{{-- end HTML part--}}

<style>
.card{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    /* overflow-y: initial !important */
  }
.card-body{
    /* height: 180px; */
    /* overflow-y: auto; */
}
</style>

<script>
//ajax function
function ajax_refresh_todo_list(params={}){

  axios({
    method:'GET',
    dataType : "html",
    url: `{{ route("todo.index") }}`
  })
  .then(function(res){
    if(params.is_animate){
      $("#div_todos").fadeOut("300",function(){
        $("#div_todos").html(res.data).fadeIn("300")
      })
    }
    else{
      $("#div_todos").html(res.data);
    }
    if (params.fn_success instanceof Function){
      params.fn_success.call();
    }

  })
  .catch(function(err){
    if (params.fn_fail instanceof Function){
      params.fn_fail.call();
    }
  })

}
//end ajax function
//---------------------------------------
//common function
function show_msg(msg,params={}){
  $("#div_alert .div_alert_content").html(msg);
      $("#div_alert").fadeIn(params.fadeIn || 800,function(){
        $(this).delay(params.delay || 1500).fadeOut(params.fadeOut || 1000,function(){
          $("#div_alert .div_alert_content").html("");
        });
      })
}
function get_other_cards(id){
  cards=$('#div_todos').find('.card').filter(function (i,v) {
      return /card_\d+/.test($(v).attr('id')) && 
      $(v).attr('id') !=`card_${id}` ;
    });
  return cards;
}
function rotate_card($jq_obj,$degree=360,$duration=400){

  //add animation to card
    //https://stackoverflow.com/questions/3789984/jquery-how-do-i-animate-a-div-rotation/26588627
    $jq_obj
    .animate({rotation: $degree},
    {
      duration: $duration,
      step: function(now, fx) {
        $(this).css({"transform": "rotate("+now+"deg)"});
        // $(this).css({"transform": "rotateY("+now+"deg)"});
        // $(this).css({"transform": "rotateZ("+now+"deg)"});
        // $(this).css({"transform": "skewY("+now+"deg)"});
      }
    });
    //end add animation to card

}
//reset hidden form input to old value
function reset_hidden_card_value(id){
  $(`#card_hidden_${id} #title`).val($(`#card_hidden_${id} #old_title`).html());
  $(`#card_hidden_${id} #content`).val($(`#card_hidden_${id} #old_content`).html());
}
//end common function
//---------------------------------------
//event function
function show_todo(el,id)
{
  axios({
    url:`todo/${id}`,
    method:'GET',
    dataType:'html'
  })
  .then(function(res){
    
    $("#div_show_todo").html(res.data);
    $("#btn_show_todo").trigger("click");
  })
  .catch(function(err){
    console.log('fail when trying to show todo detail');
    console.log(err);
  })
}
function edit_todo(el,id)
{
  //reset hidden form inputs
  reset_hidden_card_value(id);
  
  $(`#card_${id}`).hide();
  $(`#card_hidden_${id}`).show();

  //hide another cards
  // https://stackoverflow.com/questions/21727456/jquery-value-match-regex
  cards=get_other_cards(id);
  $(cards).hide();

  //hide #btn_create_todo
  //#btn_create_todo is located in index.blade.php
  $("#btn_create_todo").hide();
  
}
function revert_todo(el,id)
{

  //reset hidden form inputs
  reset_hidden_card_value(id);

  $(`#card_hidden_${id}`).hide();
  $(`#card_${id}`).show();

  //show all other cards
  cards=get_other_cards(id);
  $(cards).show();

  //show #btn_create_todo
  //#btn_create_todo is located in index.blade.php
  $("#btn_create_todo").show();

}
function update_todo(el,id)
{

  form_data=$(`#card_hidden_${id} form`).serializeArray();
  
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
    rotate_card($(`#card_${id}`));
    //end add animation to card

    //update todo list
    ajax_refresh_todo_list({
      fn_success: function(){
        show_msg("已修改");
      },
      fn_fail: function(){
        console.log('err: failure while updating todo list');
        console.log(err);
      },
      is_animate: false
    });
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
      ajax_refresh_todo_list();
      show_msg("已刪除");
      //end update todo list

    })
    .catch(function(err){
      console.log('fail while deleting todo');
      console.log(err);
    })
  }
}
//end event function



//init
$(document).ready(function(){

//end document ready  
})
</script>

    
@else
<div class="col-sm-12">
  <div>沒有任何待辦事項...</div>
</div>
@endif