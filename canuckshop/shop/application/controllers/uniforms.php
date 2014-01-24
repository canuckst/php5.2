<?php
class Uniforms extends CI_Controller {

  private $title;
  private $trip;

  public function __construct()
  {
    parent::__construct();
    $this->load->library('template');
    $this->load->library('session');
    $this->load->library('pagination');
    $this->load->library('table');
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url'));
    $this->load->language('properties');

    $this->load->model('items');
    $this->load->model('codes');

    $this->title = "Uniform";
    $this->trip = '<img src="'.WEB_CONTEXT.'/images/icons/icon_arrow_right.gif" />&nbsp;Uniforms';
  }

  public function index($message = 'List') {
     $this->select(0,0,$message);
  }

  public function search($items_start = 0, $message = '')
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('items_what', 'What', 'trim|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      if ($this->codes->role_uniform_read($uf_uniforms) || $this->codes->role_uniform_update($uf_uniforms)) {
        if ($this->form_validation->run() == TRUE)
        {
          $items_what = $this->input->get_post('items_what',TRUE);
          $this->session->set_userdata('items_what', $items_what);
          $this->select(0,0,INFO_SUCCESS);
        } else {
          $this->select(0,0,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function select($items_start = 0, $items_edit = 0, $messsage = '')
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_uniform_read($uf_uniforms) || $this->codes->role_uniform_update($uf_uniforms)) {

        $items_what = $this->session->userdata('items_what');

        $data['items_what'] = $items_what;
        $data['items_start'] = $items_start;
        $data['items_edit'] = $items_edit;
        
        $this->template->write_view('header', 'common/admin_header');
        $this->template->write('message', $messsage);
        $this->template->write('title', $this->title);
        $this->template->write('trip', $this->trip);
        
        if ($data['rows'] = $this->items->search($items_what)) {

          $config['base_url'] = base_url().'/uniforms/select/';
          $config['total_rows'] = $data['rows']->num_rows();
          $config['per_page'] = '10';
          $config['full_tag_open'] = '<p>';
          $config['full_tag_close'] = '</p>';
          $this->pagination->initialize($config);

          $table_settings=array(
                'table_open' => '<table class="zebraTable" width="750">',
                'heading_row_start' => '<tr class="rowEven">',
                'row_start' => '<tr class="rowOdd">',
                'row_alt_start' => '<tr class="rowEven">'
          );
          $this->table->set_template($table_settings); //apply the settings

          $this->table->set_heading(' Item # ', ' Item Code ', ' Item Name ', 'Online', 'Status', 'Actions');

          $items_end = $items_start + 10;
          $r = 0;

          foreach($data['rows']->result() as $row)
          {
            if ($r >= $items_start && $r < $items_end) {
              if ($row->online) {
                $online_col = sprintf('<a href="%s/uniforms/online/%d/%d/0" title="Online Order - Enable"><img src="%s/images/icons/icon_green_on.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemid, WEB_CONTEXT);
              } else {
                $online_col = sprintf('<a href="%s/uniforms/online/%d/%d/1" title="Online Order - Disable"><img src="%s/images/icons/icon_red_on.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemid, WEB_CONTEXT);
              }
              if ($row->status) {
                $status_col = sprintf('<a href="%s/uniforms/status/%d/%d/0" title="Status - Enable"><img src="%s/images/icons/icon_green_on.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemid, WEB_CONTEXT);
              } else {
                $status_col = sprintf('<a href="%s/uniforms/status/%d/%d/1" title="Status - Disable"><img src="%s/images/icons/icon_red_on.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemid, WEB_CONTEXT);
              }
              $edit_col = sprintf('<a href="%s/uniforms/select/%d/%d" title="Item Code/Item Name - Edit"><img src="%s/images/icons/icon_edit.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemid, WEB_CONTEXT);
              $attibute_col = sprintf('<a href="%s/uniformstyles/select/%d/%s" title="Attibute/Style - List"><img src="%s/images/icons/icon_attributes_on.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemcode, WEB_CONTEXT);
              $stock_col = sprintf('<a href="%s/uniformstocks/select/%d/%s" title="Inventory/Stock - List"><img src="%s/images/icons/icon_stock_label.gif" /></a>', WEB_CONTEXT, $items_start, (string)$row->itemcode, WEB_CONTEXT);

              if ($items_edit == $row->itemid) {
                if ($this->codes->role_uniform_update($uf_uniforms)) {
                  $update_col = sprintf('<img src="%s/images/icons/icon_save.gif" /><input type="submit" value="Update" title="Update this item"/>', WEB_CONTEXT);
                  $itemid_col = sprintf('<input type="text" name="update_itemid" id="update_itemid" value="%s" maxlength="10" size="5" readonly="readonly"/>', (string)$row->itemid);
                  $itemcode_col = sprintf('<input type="text" name="update_itemcode" id="update_itemcode" value="%s" maxlength="12" size="8" />', (string)$row->itemcode);
                  $itemname_col = sprintf('<input type="text" name="update_itemname" id="update_itemname" value="%s" maxlength="50" size="40" />', (string)$row->itemname);
                  $this->table->add_row($itemid_col, $itemcode_col, $itemname_col.$update_col, $online_col, $status_col, $edit_col.'&nbsp;'.$attibute_col.'&nbsp;'.$stock_col);
                } else {
                  $this->select($items_start,0,'Unauthorised');
                }
              } else {
                $this->table->add_row($row->itemid, $row->itemcode, $row->itemname, $online_col, $status_col, $edit_col.'&nbsp;'.$attibute_col.'&nbsp;'.$stock_col);
              }
            }
            $r++;
          }

          $data['items_table'] = $this->table->generate();

        }
        $this->template->write_view('content', 'items', $data);
        
        // Write to $content
        $this->template->write_view('footer', 'common/footer');
        // Render the template
        $this->template->render();
      } else {
        redirect('/uniformorders/select/'.$items_start.'/0/Unauthorised', 'refresh');
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function update($items_start = 0)
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('update_itemcode', 'ItemCode', 'trim|required|min_length[3]|max_length[12]|xss_clean');
    $this->form_validation->set_rules('update_itemname', 'ItemName', 'trim|required|min_length[8]|max_length[50]|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');
      $items_what = $this->session->userdata('items_what');

      $itemid = $this->input->get_post('update_itemid',TRUE);
      $itemcode = $this->input->get_post('update_itemcode',TRUE);
      $itemname = $this->input->get_post('update_itemname',TRUE);
      if ($this->codes->role_uniform_update($uf_uniforms)) {
        if ($this->form_validation->run() == TRUE)
        {
          if ($this->items->update($itemid, $itemcode, $itemname)) {
            $this->select($items_start,0,INFO_SUCCESS);
          } else {
            $this->select($items_start,0,INFO_UNCHANGED);
          }
        } else {
          $this->select($items_start,$itemid,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function insert($items_start = 0)
  {
    $this->form_validation->set_error_delimiters('<div class="messageStackError">', '</div>');
    $this->form_validation->set_rules('itemcode', 'ItemCode', 'trim|required|min_length[3]|max_length[12]|xss_clean');
    $this->form_validation->set_rules('itemname', 'ItemName', 'trim|required|min_length[8]|max_length[50]|xss_clean');
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      $items_start = $this->input->get_post('items_start',TRUE);
      $itemid = $this->input->get_post('itemid',TRUE);
      $itemcode = $this->input->get_post('itemcode',TRUE);
      $itemname = $this->input->get_post('itemname',TRUE);
      if ($this->codes->role_uniform_update($uf_uniforms)) {
        if ($this->form_validation->run() == TRUE)
        {
          if ($this->items->insert($itemcode, $itemname)) {
            $this->select($items_start,0,INFO_SUCCESS);
          } else {
            $this->select($items_start,0,INFO_UNCHANGED);
          }
        } else {
          $this->select($items_start,$itemid,ERROR_FORM_VALIDATION);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }

  }

  public function delete($items_start = 0, $items_delete = 0)
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_uniform_update($uf_uniforms)) {
        if ($items_delete !== 0)
        {
          if ($this->items->delete($items_delete)) {
            $this->select($items_start,0,INFO_SUCCESS);
          } else {
            $this->select($items_start,0,INFO_UNCHANGED);
          }
        } else {
          $this->select($items_start,$items_delete,INFO_UNCHANGED);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function online($items_start = 0, $items_update = 0, $items_online = 0)
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_uniform_update($uf_uniforms)) {
        if ($items_update !== 0)
        {
          if ($this->items->online($items_update, $items_online)) {
            $this->select($items_start,0,INFO_SUCCESS);
          } else {
            $this->select($items_start,0,INFO_UNCHANGED);
          }
        } else {
          $this->select($items_start,$items_update,INFO_UNCHANGED);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function status($items_start = 0, $items_update = 0, $items_status = 0)
  {
    if ($this->session->userdata('uf_logged_in')) {
      $uf_orders = $this->session->userdata('uf_orders');
      $uf_stocks = $this->session->userdata('uf_stocks');
      $uf_uniforms = $this->session->userdata('uf_uniforms');

      if ($this->codes->role_uniform_update($uf_uniforms)) {
        if ($items_update !== 0)
        {
          if ($this->items->status($items_update, $items_status)) {
            $this->select($items_start,0,INFO_SUCCESS);
          } else {
            $this->select($items_start,0,INFO_UNCHANGED);
          }
        } else {
          $this->select($items_start,$items_update,INFO_UNCHANGED);
        }
      } else {
        $this->select($items_start,0,INFO_UNAUTHORISED);
      }
    } else {
      redirect('/accounts/index/', 'refresh');
    }
  }

  public function comments()
  {
    echo 'Uniform - maintenance.';
  }

}
?>
