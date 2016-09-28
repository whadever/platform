<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">

             <li class="<?php if(!isset($edit_profile))echo 'active';?>">
            	<a href="#system_settings" data-toggle="tab"><i class="entypo-lock"></i> 
					<?php echo get_phrase('system_settings');?>
                    	</a></li>       
                    	
             <li class="<?php if(isset($edit_profile))echo 'active';?>">
            	<a href="#manage_language" data-toggle="tab"><i class="entypo-lock"></i> 
					<?php echo get_phrase('manage_language');?>
                    	</a></li>        	
		</ul>
    	<!------CONTROL TABS END------>
        
	
		<div class="tab-content">
        	
            
            <!----EDITING FORM STARTS---->
			<div class="tab-pane box <?php if(!isset($edit_profile))echo 'active';?>" id="system_settings" style="padding: 5px">
                <div class="box-content padded">
					
					
					<div class="row">
					    <?php echo form_open(base_url() . 'admin/system_settings/do_update' , 
					      array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
					        <div class="col-md-6">
					            
					            <div class="panel panel-primary" >
					            
					                <div class="panel-heading">
					                    <div class="panel-title">
					                        <?php echo get_phrase('system_settings');?>
					                    </div>
					                </div>
					                
					                <div class="panel-body">
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('system_name');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="system_name" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'system_name'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('system_title');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="system_title" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'system_title'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('address');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="address" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'address'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="phone" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'phone'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('paypal_email');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="paypal_email" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'paypal_email'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('currency');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="currency" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'currency'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('system_email');?></label>
					                      <div class="col-sm-9">
					                          <input type="text" class="form-control" name="system_email" 
					                              value="<?php echo $this->db->get_where('sms_settings' , array('type' =>'system_email'))->row()->description;?>">
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('language');?></label>
					                      <div class="col-sm-9">
					                          <select name="language" class="form-control">
					                                <?php
														$fields = $this->db->list_fields('sms_language');
														foreach ($fields as $field)
														{
															if ($field == 'phrase_id' || $field == 'phrase')continue;
															
															$current_default_language	=	$this->db->get_where('sms_settings' , array('type'=>'language'))->row()->description;
															?>
					                                		<option value="<?php echo $field;?>"
					                                        	<?php if ($current_default_language == $field)echo 'selected';?>> <?php echo $field;?> </option>
					                                        <?php
														}
														?>
					                           </select>
					                      </div>
					                  </div>
					                    
					                  <div class="form-group">
					                      <label  class="col-sm-3 control-label"><?php echo get_phrase('text_align');?></label>
					                      <div class="col-sm-9">
					                          <select name="text_align" class="form-control">
					                          	  <?php $text_align	=	$this->db->get_where('sms_settings' , array('type'=>'text_align'))->row()->description;?>
					                              <option value="left-to-right" <?php if ($text_align == 'left-to-right')echo 'selected';?>> left-to-right</option>
					                              <option value="right-to-left" <?php if ($text_align == 'right-to-left')echo 'selected';?>> right-to-left</option>
					                          </select>
					                      </div>
					                  </div>
					                  
					                  <div class="form-group">
					                    <div class="col-sm-offset-3 col-sm-9">
					                        <button type="submit" class="btn btn-info"><?php echo get_phrase('save');?></button>
					                    </div>
					                  </div>
					                    
					                </div>
					            
					            </div>
					        
					        </div>
					    <?php echo form_close();?>

					      <?php 
					        $skin = $this->db->get_where('sms_settings' , array(
					          'type' => 'skin_colour'
					        ))->row()->description;
					      ?>
					    
					        <div class="col-md-6">
					            
					            <div class="panel panel-primary" >
					            
					                <div class="panel-heading">
					                    <div class="panel-title">
					                        <?php echo get_phrase('theme_settings');?>
					                    </div>
					                </div>
					                
					                <div class="panel-body">

					                <div class="gallery-env">

					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="default">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/default.png"
					                                    <?php if ($skin == 'default') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="default">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('default');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>

					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="black">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/black.png" 
					                                      <?php if ($skin == 'black') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="black">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="blue">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/blue.png"
					                                    <?php if ($skin == 'blue') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="blue">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="cafe">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/cafe.png"
					                                    <?php if ($skin == 'cafe') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="cafe">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="green">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/green.png"
					                                    <?php if ($skin == 'green') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="green">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="purple">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/purple.png"
					                                    <?php if ($skin == 'purple') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="purple">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="red">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/red.png"
					                                    <?php if ($skin == 'red') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="red">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="white">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/white.png"
					                                    <?php if ($skin == 'white') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="white">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>
					                    <div class="col-sm-4">
					                        <article class="album">
					                            <header>
					                                <a href="#" id="yellow">
					                                    <img src="<?php echo base_url(); ?>assets/images/skins/yellow.png"
					                                    <?php if ($skin == 'yellow') echo 'style="background-color: black; opacity: 0.3;"';?> />
					                                </a>
					                                <a href="#" class="album-options" id="yellow">
					                                    <i class="entypo-check"></i>
					                                    <?php echo get_phrase('select_theme');?>
					                                </a>
					                            </header>
					                        </article>
					                    </div>

					                </div>
					                <center>
					                  <div class="label label-primary" style="font-size: 12px;">
					                    <i class="entypo-check"></i> <?php echo get_phrase('select_a_theme_to_make_changes');?>
					                  </div>
					                </center>
					                </div>
					            
					            </div>

					            <?php echo form_open(base_url() . 'admin/system_settings/upload_logo' , array(
					            'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));?>

					              <div class="panel panel-primary" >
					              
					                  <div class="panel-heading">
					                      <div class="panel-title">
					                          <?php echo get_phrase('upload_logo');?>
					                      </div>
					                  </div>
					                  
					                  <div class="panel-body">
					                      
					                    
					                      <div class="form-group">
					                          <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
					                          
					                          <div class="col-sm-9">
					                              <div class="fileinput fileinput-new" data-provides="fileinput">
					                                  <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
					                                      <img src="<?php echo base_url();?>uploads/logo.png" alt="...">
					                                  </div>
					                                  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
					                                  <div>
					                                      <span class="btn btn-white btn-file">
					                                          <span class="fileinput-new">Select image</span>
					                                          <span class="fileinput-exists">Change</span>
					                                          <input type="file" name="userfile" accept="image/*">
					                                      </span>
					                                      <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
					                                  </div>
					                              </div>
					                          </div>
					                      </div>
					                    
					                    
					                    <div class="form-group">
					                      <div class="col-sm-offset-3 col-sm-9">
					                          <button type="submit" class="btn btn-info"><?php echo get_phrase('upload');?></button>
					                      </div>
					                    </div>
					                      
					                  </div>
					              
					              </div>

					            <?php echo form_close();?>
					            
					        
					        </div>

					    </div>

					<script type="text/javascript">
					    $(".gallery-env").on('click', 'a', function () {
					        skin = this.id;
					        $.ajax({
					            url: '<?php echo base_url();?>admin/system_settings/change_skin/'+ skin,
					            success: window.location = '<?php echo base_url();?>index.php?admin/manage_profile/'
					        });
					});
					</script>
                </div>
			</div>
            <!----EDITING FORM ENDS--->
            
            <!----EDITING FORM STARTS---->
			<div class="tab-pane box <?php if(isset($edit_profile))echo 'active';?>" id="manage_language" style="padding: 5px">
                <div class="box-content padded">
					
					
					<div class="row">
						<div class="col-md-12">
					    
					    	<!------CONTROL TABS START------>
							<ul class="nav nav-tabs bordered">
					        	<?php if(isset($edit_profile)):?>
								<li class="active">
					            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 
										<?php echo get_phrase('edit_phrase');?>
					                    	</a></li>
					            <?php endif;?>
								<li class="<?php if(!isset($edit_profile))echo 'active';?>">
					            	<a href="#list1" data-toggle="tab"><i class="entypo-menu"></i> 
										<?php echo get_phrase('language_list');?>
					                    	</a></li>
								<li>
					            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
										<?php echo get_phrase('add_phrase');?>
					                    	</a></li>
								<li class="">
					            	<a href="#add_lang" data-toggle="tab"><i class="entypo-plus-circled"></i> 
										<?php echo get_phrase('add_language');?>
					                    	</a></li>
							</ul>
					    	<!------CONTROL TABS END------>
					        
						
							<div class="tab-content">
					            <!----PHRASE EDITING TAB STARTS-->
					            <?php if (isset($edit_profile)):?>
								<div class="tab-pane active" id="edit" style="padding: 5px">
					                <div class="">


											<div class="row">
					                    	<?php 
											$current_editing_language	=	$edit_profile;
											echo form_open(base_url() . 'admin/manage_language/update_phrase/'.$current_editing_language  , array('id' => 'phrase_form'));
											$count = 1;
											$language_phrases	=	$this->db->query("SELECT `phrase_id` , `phrase` , `$current_editing_language` FROM `sms_language`")->result_array();
											foreach($language_phrases as $row)
											{
												$count++;
												$phrase_id			=	$row['phrase_id'];					//id number of phrase
												$phrase				=	$row['phrase'];						//basic phrase text
												$phrase_language	=	$row[$current_editing_language];	//phrase of current editing language
												?>
					                            <!----phrase box starts-->
					                            <div class="col-sm-3">
					                                <div class="tile-stats tile-gray">
					                                    <div class="icon"><i class="entypo-mail"></i></div>
					                                    
					                                    
					                                    <h3><?php echo $row['phrase'];?></h3>
					                                    <p>
					                                    	<input type="text" name="phrase<?php echo $row['phrase_id'];?>" 	
					                                    		value="<?php echo $phrase_language;?>" class="form-control"/>
					                                    </p>
					                                </div>
					                                
					                            </div>
					                            <!----phrase box ends-->
												<?php 
											}
											?>
											</div>
					                        <input type="hidden" name="total_phrase" value="<?php echo $count;?>" />
					                        <input type="submit" value="<?php echo get_phrase('update_phrase');?>" onClick="document.getElementById('phrase_form').submit();" class="btn btn-blue"/>	
					                        <?php
											echo form_close();
											?>
					                                     
					                </div>                
								</div>
					            <?php endif;?>
					            <!----PHRASE EDITING TAB ENDS-->
					            <!----TABLE LISTING STARTS-->
					            <div class="tab-pane <?php if(!isset($edit_profile))echo 'active';?>" id="list1">
					                
					                
					                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
					                	<thead>
					                    	<tr>
					                        	<th><?php echo get_phrase('language');?></th>
					                        	<th><?php echo get_phrase('option');?></th>
					                        </tr>
					                    </thead>
					                    <tbody>
					                    	<?php
													$fields = $this->db->list_fields('sms_language');
													foreach($fields as $field)
													{
														 if($field == 'phrase_id' || $field == 'phrase')continue;
														?>
					                    	<tr>
					                        	<td><?php echo ucwords($field);?></td>
					                        	<td>
					                            	<a href="<?php echo base_url();?>admin/manage_profile/edit_phrase/<?php echo $field;?>"
					                                	 class="btn btn-info">
					                                		<?php echo get_phrase('edit_phrase');?>
					                                </a>
					                            	<a href="<?php echo base_url();?>admin/manage_language/delete_language/<?php echo $field;?>" rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete_language');?>" class="btn btn-info" onclick="return confirm('Delete Language ?');">
					                                		<?php echo get_phrase('delete_language');?>
					                                </a>
					                            </td>
					                        </tr>
					                        <?php
					                        }
					                        ?>
					                    </tbody>
					                </table>
								</div>
					            <!----TABLE LISTING ENDS--->
					            
					            
								<!----PHRASE CREATION FORM STARTS---->
								<div class="tab-pane box" id="add" style="padding: 5px">
					                <div class="box-content">
					                    <?php echo form_open(base_url() . 'admin/manage_language/add_phrase/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
					                        <div class="padded">
					                            <div class="form-group">
					                                <label class="col-sm-3 control-label"><?php echo get_phrase('phrase');?></label>
					                                <div class="col-sm-5">
					                                    <input type="text" class="form-control" name="phrase" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
					                                </div>
					                            </div>
					                            
					                        </div>
					                        <div class="form-group">
					                              <div class="col-sm-offset-3 col-sm-5">
					                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_phrase');?></button>
					                              </div>
												</div>
					                    <?php echo form_close();?>                
					                </div>                
								</div>
								<!----PHRASE CREATION FORM ENDS--->
					            
					        	<!----ADD NEW LANGUAGE---->
								<div class="tab-pane box" id="add_lang" style="padding: 5px">
					                <div class="box-content">
					                    <?php echo form_open(base_url() . 'admin/manage_language/add_language/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
					                        <div class="padded">
					                            <div class="form-group">
					                                <label class="col-sm-3 control-label"><?php echo get_phrase('language');?></label>
					                                <div class="col-sm-5">
					                                    <input type="text" class="form-control" name="language" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
					                                </div>
					                            </div>
					                            
					                        </div>
					                        <div class="form-group">
					                              <div class="col-sm-offset-3 col-sm-5">
					                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_language');?></button>
					                              </div>
												</div>
					                    <?php echo form_close();?> 
					                </div>
								</div>
					            <!----LANGUAGE ADDING FORM ENDS-->
					            
							</div>
						</div>
					</div>
                </div>
			</div>
            <!----EDITING FORM ENDS--->
            
		</div>
	</div>
</div>

