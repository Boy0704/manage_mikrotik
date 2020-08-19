<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {


    public function log_user()
    {
        if ($this->session->userdata('level') == '') {
            redirect('login');
        }
        $data = array(
            'konten' => 'a_user/log_user',
            'judul_page' => 'Log User',
        );
        $this->load->view('v_index', $data);
    }
	
	public function index()
	{
        if ($this->session->userdata('level') == '') {
            redirect('login');
        }
		$data = array(
			'konten' => 'home_admin',
            'judul_page' => 'Dashboard',
		);
		$this->load->view('v_index', $data);
    }

    public function poe_out()
    {
        if ($this->session->userdata('level') == '') {
            redirect('login');
        }
        $data = array(
            'konten' => 'poe_out/view',
            'judul_page' => 'Manage PoE Out',
        );
        $this->load->view('v_index', $data);
    }

    public function list_poe_out()
    {
        require APPPATH.'third_party/api_mikrotik.php';

        $API = new RouterosAPI();
        $API->debug = false;

        $id = $this->input->post('id_device');
        $device = $this->db->get_where('device', array('id_device'=>$id))->row();

        if ($API->connect($device->ip,$device->port,$device->username,$device->password)) {
            // echo "Koneksi sukses";

            $API->write('/interface/ethernet/poe/print');

            $READ = $API->read(false);
            $hasil = $API->parseResponse($READ);
            $img = 'https://cdn.pixabay.com/photo/2014/03/25/15/22/power-296626_1280.png';
            foreach ($hasil as $row) {
                // log_r($row);
                if ($row['poe-out'] == 'forced-on') {
                    $img = 'https://cdn.pixabay.com/photo/2012/04/11/18/29/button-29286_1280.png';
                }
                ?>
                    <div class="col-md-4">
                        <table class="table table-striped">
                            <tr>
                                <td colspan="2">
                                    <center><img src="<?php echo $img ?>" id="<?php echo $row['.id'] ?>_img" style="width: 50%"></center>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <select id="<?php echo substr($row['.id'], 1) ?>_status">
                                        <option value="<?php echo $row['poe-out'] ?>"><?php echo $row['poe-out'] ?></option>
                                        <option value="off">off</option>
                                        <option value="forced-on">forced-on</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td><b><?php echo $row['name'] ?></b></td>
                            </tr>
                            <tr>
                                <td>power-cycle-interval</td>
                                <td><input type="text" id="<?php echo substr($row['.id'], 1) ?>_pc" value="<?php echo $row['power-cycle-interval'] ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><button onclick="simpan('<?php echo $id ?>','<?php echo substr($row['.id'], 1) ?>')" class="btn btn-xs btn-primary btn-block">SIMPAN</button></td>
                            </tr>
                        </table>
                    </div>
                    <script type="text/javascript">
                        function simpan(id_device,id) {
                            // alert(id);
                            var status = $("#"+id+"_status").val();
                            var pc = $("#"+id+"_pc").val();
                            $.ajax({
                                url: 'app/simpan_poe_out',
                                type: 'POST',
                                dataType: 'html',
                                data: {id: id, status: status, pc: pc, id_device: id_device},
                            })
                            .done(function() {
                                
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
                                alert('berhasil disimpan');
                                console.log("success");
                            })
                            .fail(function() {
                                console.log("error");
                            })
                            .always(function() {
                                console.log("complete");
                            });
                            
                        }
                    </script>
                <?php
            }
            
            $API->disconnect();
        } else {
            ?>
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Warning!</strong> Device tidak bisa di akses.
                </div>
            </div>
            <?php
        }

        
    }

    public function simpan_poe_out()
    {
        require APPPATH.'third_party/api_mikrotik.php';

        $API = new RouterosAPI();
        $API->debug = false;

        $id_device = $this->input->post('id_device');
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $pc = $this->input->post('pc');
        $device = $this->db->get_where('device', array('id_device'=>$id_device))->row();

        if ($API->connect($device->ip,$device->port,$device->username,$device->password)) {
            echo "Koneksi sukses";

            $run = $API->comm("/interface/ethernet/poe/set", array(
                ".id"  => "*".$id,
                "poe-out"  => $status,
                "power-cycle-interval"  => $pc,
            ));
            
            $API->disconnect();
        } else {
            echo "tidak ada Koneksi";
        }
    }
   
	
}
