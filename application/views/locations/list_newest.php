<h1>Newest <?=$limit?> Locations</h1>
<p>Show 
   <select name="num_customers">
      <option value="">...</option>
      <? $chunk_size = 100;
         $max = 500;
         $num_chunks = ceil($total_locations / ($chunk_size*2)); 
         $scale = array($chunk_size);
         for($i = 2; $i < $num_chunks; $i++) :
           if($i * $chunk_size > 500) break; //don't let it get bigger than 1,000
           $scale[] = $i * $chunk_size;
         endfor;
      ?>

      <? foreach($scale as $v) : ?>
        <option value="<?=$v?>"><?=$v?></option>
      <? endforeach ?>
    </select>
</p>
<script type="text/javascript">
  $(function(){
      $("select[name=num_customers]").change(function(){
          limit = $("select[name=num_customers]").val()
          window.location = "<?=site_url('locations/newest')?>" + "/" + limit
      })
    })  
</script>

<? $this->load->view("locations/locations_table") ?>
