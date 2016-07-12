<h1><? if( isset($route->name) ) echo $route->name ?> on <?=$on_date?></h1>
<? // $this->db->last_query() ?>
<? $this->load->view('pickups/pickup_table') ?>
