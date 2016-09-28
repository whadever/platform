<html>
    <head>
        <title>Welcome Task Management System </title>  
		<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
        <style>
    .profile-image{
        height: 350px;
        width:350px;
        display: block;
    }
.profile-image:hover .overlay {
  position:absolute;
  width: 350px;
  height: 350px;
  z-index: 100;
  background:transparent url('images/home.png') no-repeat scroll 0 0;
  cursor: pointer;
  display: block;
  top:100px;    
}
.btn-logout{
    background:#CD1619;
    border-radius: 4px;
    color: #fff;
    display: block;
    font-family: arial;
    font-weight: bold;
    height: 35px;
    line-height: 35px;
    text-align: center;
    text-decoration: none;
    width: 100px;
}
</style>
<style type="text/css"> 
    .imgBox { width: 350px; height: 350px; 
             
             background: url("images/srshome.png") no-repeat scroll 0 0 / 350px 350px rgba(0, 0, 0, 0);
             cursor: pointer; 
             margin: 0 auto;} 
    .imgBox:hover { width: 350px; height: 350px; 
           
            background: url("images/home.png") no-repeat scroll 0 0 / 350px 350px rgba(0, 0, 0, 0);
            margin: 0 auto;} 
</style>
    </head>
    
    <body>
    
 
<?php if(isset($message)) echo $message;  ?>
<div id="header-top" style="width:100%; margin-top: 10px; height: 100px;">
    <div class="logo" style="text-align:right; float:left;width:61%">
      <img src="<?php echo base_url();?>images/wbs_logo.png" />

    </div>
    <div class="logout" style="float:right; text-align: left;">
         <a class="btn-logout" title="Logout" href="<?php echo base_url();?>user/user_logout">
            Logout 
        </a>
    </div>
</div>
<div class="clear"></div>


<div class="all-title">

    <?php //echo $title; ?> <br/>

</div>







<div class="clear"></div>


<div class="front-page" style="text-align:center;">
<!--<div class="profile-image">
  <a href="#"><img src="<?php echo base_url();?>/images/srshome.png" /></a>
  <span class="overlay"></span>
</div> -->
<a href="<?php echo base_url()?>overview" style="text-decoration:none; color:#000;">
    <div style="text-align:center;" class=""> 
        <img width="400px" alt="Task Management System" src="uploads/request/document/1415778473_pen_write_file.svg"/> 
        <h2>Task Management System</h2>
    </div> 
</a>


</div>

    
    </body>
</html>




