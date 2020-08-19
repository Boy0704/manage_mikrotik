<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Pilih Device</label>
			<div class="col-sm-4">
				<select name="pilih_device" class="form-control select2" id="pilih_device">
					<option value="">--Pilih Device--</option>
					<?php foreach ($this->db->get('device')->result() as $row) { ?>
						<option value="<?php echo $row->id_device ?>"><?php echo $row->nama_device ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row" id="list">
	
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("#pilih_device").change(function() {
			var id_device = $(this).val();
			$.ajax({
				url: 'app/list_poe_out',
				type: 'POST',
				data: {id_device: id_device},
				beforeSend: function () {
					console.log('sedang loading');	
					$("#list").html("<center>Sedang loading..</center>")
				},
				success: function(data) {
			        $("#list").html(data);
			    },
			    error: function(xhr) { // if error occured
			        console.log(xhr);
			    },
			    complete: function() {
			       	console.log('complete');
			    },
			    dataType: 'html'
			})
			
			
		});
	});
</script>