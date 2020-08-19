
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nama Device <?php echo form_error('nama_device') ?></label>
            <input type="text" class="form-control" name="nama_device" id="nama_device" placeholder="Nama Device" value="<?php echo $nama_device; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ip <?php echo form_error('ip') ?></label>
            <input type="text" class="form-control" name="ip" id="ip" placeholder="Ip" value="<?php echo $ip; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Port <?php echo form_error('port') ?></label>
            <input type="text" class="form-control" name="port" id="port" placeholder="Port" value="<?php echo $port; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Username <?php echo form_error('username') ?></label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Password <?php echo form_error('password') ?></label>
            <input type="text" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>" />
        </div>
	    <input type="hidden" name="id_device" value="<?php echo $id_device; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('device') ?>" class="btn btn-default">Cancel</a>
	</form>
   