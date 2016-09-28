<table>
    <thead>
        <tr>
            <th width="80"><div><?php echo get_phrase('roll');?></div></th>
            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
            <th><div><?php echo get_phrase('name');?></div></th>
            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
            <th><div><?php echo get_phrase('email');?></div></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $this->db->order_by('student_id','DESC');
        $students   =   $this->db->get_where('sms_student',array('company_id'=>$this->session->userdata('user')->company_id))->result_array();
        foreach($students as $row):?>
        <tr>
            <td><?php echo $row['roll'];?></td>
            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['address'];?></td>
            <td><?php echo $row['email'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>