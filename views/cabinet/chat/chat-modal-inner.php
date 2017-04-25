<div class="section-cabinet">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Переписка c <?=$message->_to->name?></h4>
      </div>
      <div class="modal-body">
 			<? echo $this->render('_messages', ['messages'=>$messages, 'message'=>$message]); ?>
      </div>
	</div>
</div>
<script>
	if(!document.getElementById('body'))
		location.href = '/';
</script>