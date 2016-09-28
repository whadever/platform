<script>
    window.BaseUrl = "<?php print base_url(); ?>";
</script>

<?php 
$this->load->helper('url');
 
$redirect_login_page = 'https://www.horncastledevelopments.co.nz/user';
if(!$this->session->user){redirect($redirect_login_page); }

?>

<div class="container-fluid main-body">
         
<div id="maincontent">
  <?php echo $maincontent; ?>   
</div>

</div>
<div class="clear"></div>