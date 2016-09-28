<?php
    $domain = $_SERVER['SERVER_NAME'];
    $current_job = (isset($current_job)) ? $current_job : $_SESSION[$domain]['current_job'];

    switch($_GET['cp']){
        case 'pre_construction':
            $_SESSION[$domain]['pre_construction_page'] = current_url(); break;
        case 'construction':
            $_SESSION[$domain]['construction_page'] = current_url(); break;
        case 'post_construction':
            $_SESSION[$domain]['post_construction_page'] = current_url(); break;
    }

    /*removing the session page functionality. task #3962*/
    //$pre_construction_page = ($_SESSION[$domain]['pre_construction_page']) ? $_SESSION[$domain]['pre_construction_page']."?cp=pre_construction" : "#";
    //$construction_page = ($_SESSION[$domain]['construction_page']) ? $_SESSION[$domain]['construction_page']."?cp=construction"  : "#";
    //$post_construction_page = ($_SESSION[$domain]['post_construction_page']) ? $_SESSION[$domain]['post_construction_page']."?cp=post_construction"  : "#";
    $pre_construction_page = $construction_page = $post_construction_page = '#';

   /*current job info*/
    if(isset($current_job)){
        $this->db->where('id',$current_job);
        $job_info = $this->db->get('construction_development')->row();
    }

    /*getting all forms*/
    $wp_company_id = $this->session->userdata('user')->company_id;
    $forms = $this->db->query('select * from construction_checklist_form where wp_company_id = '.$wp_company_id)->result();

	$user = $this->session->userdata('user');
    $user_id = $user->uid;

	$this->db->select('application_role_id');
	$this->db->where('user_id',$user_id);
	$this->db->where('application_id',5);
	$app_role_id = $this->db->get('users_application')->row()->application_role_id;



?>
<style>
    /*menu css*/
    #job_menu {
        clear: both;
        position: relative;

    }
    #job_menu ul {
        display: none;
    }
    #job_menu li {
        display: block;
        float: none;
        height: auto;
        width: 100%;
    }
    #job_menu a {
        background-color: #fdd07c;
        border: 0 none;
        border-radius: 5px;
        color: #333333;
        font-size: 88%;
        margin: 2px 0;
        text-align: left;
        width: 100%;
    }
    li > ul {
        margin-left: 15px;
    }
    ul#job_menu li.active, ul#job_menu li:hover, ul#job_menu li a:focus {
        border: none;
        outline: none;
    }
    #job_menu li a.btn{
    	white-space: normal;
    	padding: 6px 6px 6px 12px;
	}
	#job_menu li a.btn.dcjq-parent {
	    padding-left: 23px;
	}
    /**********/
</style>
<ul id="job_menu" class="con_menu">
    <?php  $pre_construction_label = ($domain != 'horncastle.wclp.co.nz') ? "Pre Construction" : "Design and Consenting"; ?>
    <li id="" class="files">
        <a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_detail/<?php echo $current_job; ?>" data-match-url-segments="2">Job Information</a>

    </li>
    <!--pre construction pages-->
    <!--will show this menu only for jobs not under any unit-->
    <?php if(empty($job_info->parent_unit)): ?>
    
    <?php if($app_role_id ==5): ?>
    <li id="" class="files">
        <a href="<?php echo base_url(); ?>constructions/construction_overview/<?php echo $current_job; ?>?cp=pre_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="pre_construction"><?php echo $pre_construction_label; ?></a>
    </li> 
    <?php elseif($app_role_id !=3): ?>
    <li id="" class="files">
        <a class="btn btn-default btn-cons" href="<?php echo $pre_construction_page; ?>">
            <?php echo $pre_construction_label; ?>
            
        </a>
        <ul>
            <?php if($domain != 'horncastle.wclp.co.nz'): ?>
            <li id=""><a href="<?php echo base_url(); ?>constructions/tendering/<?php echo $current_job; ?>?cp=pre_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="pre_construction">Tendering</a></li>
            <?php endif; ?>
            <li id=""><a href="<?php echo base_url(); ?>constructions/construction_overview/<?php echo $current_job; ?>?cp=pre_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="pre_construction">Dashboard</a></li>
            
            <li id=""><a href="<?php echo base_url(); ?>constructions/phases_underway/<?php echo $current_job; ?>?cp=pre_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="pre_construction">Program</a></li>
            
            <!--<li id=""><a class="btn btn-default btn-cons" href="<?php /*echo base_url(); */?>constructions/construction_detail/<?php /*echo $current_job; */?>">Job Information</a></li>-->
            <?php if($domain != 'homes.wclp.co.nz'): ?>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>job/consultants_contact_list/<?php echo $current_job; ?>?cp=pre_construction" data-match-url-segments="2" data-cp="pre_construction">Consultants Contact List</a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php endif; ?>
    
    <!--construction pages-->
    <?php if($app_role_id !=3): ?>
    <li class="">
        <a class="btn btn-default btn-cons <?php if($this->uri->segment(2)=='photo_notes'){ echo 'selected active'; } ?>" href="<?php echo $construction_page; ?>">
            Construction
            
        </a>
        <ul>
            <li id=""><a href="<?php echo base_url(); ?>constructions/construction_overview/<?php echo $current_job; ?>?cp=construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="construction">Dashboard</a></li>
            
            <?php if($app_role_id !=5): ?>
            <li id=""><a href="<?php echo base_url(); ?>constructions/phases_underway/<?php echo $current_job; ?>?cp=construction" class="btn btn-default btn-cons"  data-match-url-segments="2" data-cp="construction">Program</a></li>
            <?php endif; ?>
            
            <li id=""><a class="btn btn-default btn-cons <?php if($this->uri->segment(2)=='photo_notes'){ echo 'selected active'; } ?>" href="<?php echo base_url(); ?>constructions/construction_photos/<?php echo $current_job; ?>?cp=construction" data-match-url-segments="2" data-cp="construction">Photos</a></li>
            <?php if($app_role_id !=5){ ?><li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/notes/<?php echo $current_job; ?>?cp=construction" data-match-url-segments="2" data-cp="construction">Notes</a></li><?php } ?>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_documents/<?php echo $current_job; ?>/documents?cp=construction" data-match-url-segments="4" data-cp="construction">Documents</a></li>
            <?php if($domain == 'horncastle.wclp.co.nz' && $app_role_id !=5): ?>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_documents/<?php echo $current_job; ?>/health_and_safety?cp=construction" data-match-url-segments="4" data-cp="construction">Health and Safety</a></li>
            <?php endif; ?>
            <?php if($app_role_id !=5){ ?>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>job/trade_contact_list/<?php echo $current_job; ?>?cp=construction" data-match-url-segments="2" data-cp="construction">Trade Contact List</a></li>
            <?php } ?>
            <?php /*if($domain == 'horncastle.wclp.co.nz' || $domain == 'xprobuilders.wclp.co.nz'): */?>
                <!--<li id=""><a class="btn btn-default btn-cons" href="<?php /*echo base_url(); */?>job/checklist/<?php /*echo $current_job; */?>">Check List</a></li>-->
            <?php /*endif; */?>
			<?php if($app_role_id !=5){ ?>
            <li id="">
                <a class="btn btn-default btn-cons parent" href="#">
                    Quality Check List
                </a>
                <ul>
                    <?php if($forms): ?>
                    <?php foreach($forms as $form): ?>
                    <li><a class="btn btn-default btn-cons" href="<?php echo site_url('job/checklist/'.$current_job.'/'.$form->id); ?>?cp=construction" data-match-url-segments="4" data-cp="construction"><?php echo $form->name; ?></a></li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color: white">No form created yet</span>
                    <?php endif; ?>
                </ul>
            </li>
			<?php } ?>
        </ul>
    </li>
    <?php endif; ?>
    
    <?php if(in_array($domain, array('localhost', 'business.wclp.co.nz', 'homes.wclp.co.nz', 'xprobuilders.wclp.co.nz', 'property.wclp.co.nz'))): ?>
    <!--post construction pages-->
    <?php if($app_role_id !=5): ?>
    <li id="" class="">
        <a class="btn btn-default btn-cons" href="<?php echo $post_construction_page; ?>">
            Post Settlement
            
        </a>
        <ul>
            <li id=""><a href="<?php echo base_url(); ?>constructions/construction_overview/<?php echo $current_job; ?>?cp=post_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="post_construction">Dashboard</a></li>
            <li id=""><a href="<?php echo base_url(); ?>constructions/phases_underway/<?php echo $current_job; ?>?cp=post_construction" class="btn btn-default btn-cons" data-match-url-segments="2" data-cp="post_construction">Program</a></li>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_photos/<?php echo $current_job; ?>?cp=post_construction" data-match-url-segments="2" data-cp="post_construction">Photos</a></li>
            <?php if($app_role_id !=5){ ?><li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/notes/<?php echo $current_job; ?>?cp=post_construction" data-match-url-segments="2" data-cp="post_construction">Notes</a></li><?php } ?>
            <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_documents/<?php echo $current_job; ?>/documents?cp=post_construction" data-match-url-segments="4" data-cp="post_construction">Documents</a></li>
            <?php if($domain == 'horncastle.wclp.co.nz' && $app_role_id !=5 ): ?>
                <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_documents/<?php echo $current_job; ?>/health_and_safety?cp=post_construction" data-match-url-segments="4" data-cp="post_construction">Health and Safety</a></li>
            <?php endif; ?>
            <?php if($app_role_id !=5){ ?> <li id=""><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>job/trade_contact_list/<?php echo $current_job; ?>?cp=post_construction" data-match-url-segments="2" data-cp="post_construction">Trade Contact List</a></li><?php } ?>
            <?php /*if($domain == 'horncastle.wclp.co.nz' || $domain == 'xprobuilders.wclp.co.nz'): */?>
            <!--<li id=""><a class="btn btn-default btn-cons" href="<?php /*echo base_url(); */?>job/checklist/<?php /*echo $current_job; */?>">Check List</a></li>-->
            <?php /*endif; */?>
			<?php if($app_role_id !=5 && $app_role_id !=3){ ?>
            <li id="">
                <a class="btn btn-default btn-cons parent" href="#">
                    Quality Check List
                </a>
                <ul>
                    <?php if($forms): ?>
                        <?php foreach($forms as $form): ?>
                            <li><a class="btn btn-default btn-cons" href="<?php echo site_url('job/checklist/'.$current_job.'/'.$form->id); ?>?cp=post_construction" data-match-url-segments="4" data-cp="post_construction"><?php echo $form->name; ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span style="color: white">No form created yet</span>
                    <?php endif; ?>
                </ul>
            </li>
			<?php } ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php endif; ?>
    
    <?php if($app_role_id !=3): ?>
    <li id="" class="files">
        <a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_overview_all/<?php echo $current_job; ?>" data-match-url-segments="2">View Combined Overview</a>
    </li>
    <?php endif; ?>
    
    <?php if( ($app_role_id == 1 or $app_role_id == 5) && $domain != 'horncastle.wclp.co.nz'): ?>
    <li id="investor_report" class="files">
        <a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>report/construction_investor_report/<?php echo $current_job; ?>" data-match-url-segments="2">Investor Report</a>
    </li>
	<?php endif; ?>
</ul>
<div style="clear: both"></div>
<script>

    var current_url = "<?php echo current_url(); ?>";
    var base_url = "<?php echo base_url(); ?>";

    function hide_submenu(){
        $("#job_menu ul").hide();
    }
    $(document).ready(function(){
        /*$("#job_menu > li > a").click(function(){
            hide_submenu();
            $(this).siblings("ul").show(400);
            $(this).addClass('selected');
            $(this).parent().siblings().children('a').removeClass('selected');

        });
        $("#job_menu .parent").click(function(){
            $(this).parent().siblings().children("ul").hide();
            $(this).siblings("ul").show(400);
            $(this).addClass('selected');
            $(this).parent().siblings().children('a').removeClass('selected');
            return false;
        });*/
        $("#job_menu").dcAccordion({
            eventType: 'click',
            autoClose: true,
            saveState: false,
            disableLink: true,
            showCount: false,
            speed: 'slow'
        });
        $("#job_menu a").each(function(){
            //if(current_url.indexOf($(this).prop('href')) == 0){
            if(url_match($(this))){
                /*if(current_url.length > $(this).prop('href').length && (current_url.replace($(this).prop('href'),'').charAt(0) != '/' || $(this).attr('data-cp') != '<?php echo $_GET['cp']; ?>')){
                    return;
                }*/
                $(this).addClass(' selected');
                $(this).addClass(' active');
                //$(this).parent().parent().siblings('a').addClass(' selected');
                $(this).parents().siblings('a').addClass(' selected');
                $(this).parents().siblings('a').addClass(' active');
                //$(this).parent().parent().show();
                $(this).parents().show();
            }
        });

        var title = [];
        $("a.selected").each(function(){
            title.push($(this).text())
        });
        $("#top_title").html(title.join(": "));

    });
    function url_match(el){

        var current = current_url.replace(base_url,'');
        if('<?php echo $_GET['cp']; ?>' != '' ){
            current = current+'?cp='+'<?php echo $_GET['cp']; ?>';
        }
        var match = el.prop('href').replace(base_url,'');

        if('<?php echo $_GET['cp']; ?>' == '' ){
            return current == match;
        }
        current_url_arr = current.split('/');
        match_url_arr = match.split('/');

        if(el.attr('data-cp') != '<?php echo $_GET['cp']; ?>') return false;

        for(var i = 0; i < el.attr('data-match-url-segments'); i++){
            if(!current_url_arr[i] || current_url_arr[i] != match_url_arr[i]){
                return false;
            }
        }
        return true;
    }

</script>