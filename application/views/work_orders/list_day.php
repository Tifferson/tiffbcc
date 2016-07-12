<?$this->load->view('simple_header', array('title' => $title))?>

<script type="text/javascript">
	$(function(){$('.work_items').sortable()})
</script>

<h1> <?= $title ?></h1>

<ol class="work_items">
<? foreach($today_work_orders->result() as $wo) : ?>
	<li style="cursor:move" >
		<h3>
		
		<?=$wo->task?>
		</h3>
		<span style="padding:5px"><?=$wo->comments?></span>
		
	  <?if(!empty($today_work_order->location_name)) :?>
	 	   <br/><br/>Location Info:
		      <br/><?=$work_order->location_name ?>
		      <br/><?=$work_order->address ?>
		      <br/><?=$work_order->city . ', ' . $work_order->state . " " . $work_order->zip?>
		      <br/><?=$work_order->pickup_specifics ?>
		      <br/><?=$work_order->container_type ?>
	  <? endif?>
	</li>
<? endforeach ?>
</ol>

<br/>

<h1>Due by the next month</h1>
<ol class="work_items">
<? foreach($due_on_work_orders->result() as $wo) : ?>
	<li style="cursor:move" >
		<h3>
		
		<?=$wo->task?>
		</h3>
		<span style="padding:5px"><?=$wo->comments?></span>
	</li>
<? endforeach ?>
</ol>


<a href="javascript:window.print()">print</a>
<?$this->load->view('simple_footer')?>
