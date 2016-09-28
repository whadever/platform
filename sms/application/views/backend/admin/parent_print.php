<table>
	<thead>
	    <tr>
	        <th><div><?php echo get_phrase('name');?></div></th>
	        <th><div><?php echo get_phrase('children');?></div></th>
	        <th><div><?php echo get_phrase('email');?></div></th>
	        <th><div><?php echo get_phrase('phone');?></div></th>
	        <th><div><?php echo get_phrase('profession');?></div></th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	        $this->db->order_by('parent_id','DESC');
	        $parents   =   $this->db->get('parent' )->result_array();
	        foreach($parents as $row):?>
	    <tr>
	        <td><?php echo $row['name'];?></td>
	        <td><?php echo $row['children'];?></td>
	        <td><?php echo $row['email'];?></td>
	        <td><?php echo $row['phone'];?></td>
	        <td><?php echo $row['profession'];?></td>
	    </tr>
	    <?php endforeach;?>
	</tbody>
</table>