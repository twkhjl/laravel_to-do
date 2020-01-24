<!-- Button trigger modal -->
<button type="button" id="btn_show_todo" class="btn btn-primary d-none" data-toggle="modal" data-target="#modal_show_todo">
  this button is hidden,triggered by "details" button in each todo card
</button>

<!-- Modal -->
<div class="modal" id="modal_show_todo" tabindex="-1" role="dialog" aria-labelledby="modal_show_todoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_show_todoLabel">{{ $todo['title'] }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div>{{ $todo['content'] }}</div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
/* https://stackoverflow.com/questions/25874001/how-to-put-scroll-bar-only-for-modal-body */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 250px;
    overflow-y: auto;
}
</style>