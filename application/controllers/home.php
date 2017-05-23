<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      $this->load->model('modelcrud');
      $this->load->library('upload');
      $this->load->library('pagination');
  }

  // fungsi untuk mengambil data
	public function index()
	{

      $cari = $this->input->get('cari');
      $page = $this->input->get('per_page');

      $search = array('name' => $cari );

      $batas =  9; // 9 data per page
      if(!$page):
          $offset = 0;
      else:
          $offset = $page;
      endif;

      $config['page_query_string'] = TRUE;
  		$config['base_url'] 				 = base_url().'index.php/home/?cari='.$cari;
  		$config['total_rows'] 			 = $this->modelcrud->jumlah_row($search);

  		$config['per_page'] 				 = $batas;
  		$config['uri_segment'] 			 = $page;

  		$config['full_tag_open'] 		= '<ul class="pagination">';
  		$config['full_tag_close'] 	= '<ul>';

  		$config['first_link'] 			= 'first';
  		$config['first_tag_open'] 	= '<li><a>';
  		$config['first_tag_close'] 	= '</a></li>';

  		$config['last_link'] 				= 'last';
  		$config['last_tag_open']	 	= '<li><a>';
  		$config['last_tag_close'] 	= '</a></li>';

  		$config['next_link'] 				= '&raquo;';
  		$config['next_tag_open'] 		= '<li><a>';
  		$config['next_tag_close'] 	= '</a></li>';

  		$config['prev_link'] 				= '&laquo;';
  		$config['prev_tag_open'] 		= '<li><a>';
  		$config['prev_tag_close'] 	= '</a></li>';

  		$config['cur_tag_open'] 		= '<li class="active"><a>';
  		$config['cur_tag_close'] 		= '</a></li>';

  		$config['num_tag_open'] 		= '<li><a>';
  		$config['num_tag_close'] 		= '</a></li>';

      $this->pagination->initialize($config);
      $data['pagination']	 = $this->pagination->create_links();
      $data['jumlah_page'] = $page;


      $data['data'] = $this->modelcrud->get($batas,$offset,$search);

  		$this->load->view('home',$data);
	}

  // untuk menampilkan halaman tambah data
  public function tambah()
  {
      return $this->load->view('tambah_data');
  }

  // untuk memasukan data ke database
  public function insertdata()
  {
      $name   = $this->input->post('name');
      $alamat = $this->input->post('alamat');

      // get foto
      $config['upload_path'] = './assets/picture';
      $config['allowed_types'] = 'jpg|png|jpeg|gif';
      $config['max_size'] = '2048';  //2MB max
      $config['max_width'] = '4480'; // pixel
      $config['max_height'] = '4480'; // pixel
      $config['file_name'] = $_FILES['fotopost']['name'];

      $this->upload->initialize($config);

	    if (!empty($_FILES['fotopost']['name'])) {
	        if ( $this->upload->do_upload('fotopost') ) {
	            $foto = $this->upload->data();
	            $data = array(
	                          'name'       => $name,
                            'foto'       => $foto['file_name'],
	                          'alamat'     => $alamat,
	                        );
							$this->modelcrud->insert($data);
              redirect('');
	        }else {
              die("gagal upload");
	        }
	    }else {
	      echo "tidak masuk";
	    }

  }

  // delete
  public function deletedata($id,$foto)
  {
      $path = './assets/picture/';
      @unlink($path.$foto);

      $where = array('id' => $id );
      $this->modelcrud->delete($where);
      return redirect('');
  }

  // edit
  public function edit($id)
  {
      $kondisi = array('id' => $id );

      $data['data'] = $this->modelcrud->get_by_id($kondisi);
      return $this->load->view('edit_data',$data);
  }

  // update
  public function updatedata()
  {
      $id   = $this->input->post('id');
      $name = $this->input->post('name');
      $alamat = $this->input->post('alamat');

      $path = './assets/picture/';

      $kondisi = array('id' => $id );

      // get foto
      $config['upload_path'] = './assets/picture';
      $config['allowed_types'] = 'jpg|png|jpeg|gif';
      $config['max_size'] = '2048';  //2MB max
      $config['max_width'] = '4480'; // pixel
      $config['max_height'] = '4480'; // pixel
      $config['file_name'] = $_FILES['fotopost']['name'];

      $this->upload->initialize($config);

	    if (!empty($_FILES['fotopost']['name'])) {
	        if ( $this->upload->do_upload('fotopost') ) {
	            $foto = $this->upload->data();
	            $data = array(
	                          'name'       => $name,
                            'foto'       => $foto['file_name'],
	                          'alamat'     => $alamat,
	                        );
              // hapus foto pada direktori
              @unlink($path.$this->input->post('filelama'));

							$this->modelcrud->update($data,$kondisi);
              redirect('');
	        }else {
              die("gagal update");
	        }
	    }else {
	      echo "tidak masuk";
	    }

  }


} // end class
