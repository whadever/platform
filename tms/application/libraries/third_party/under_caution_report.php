<?php
$hostname = 'freyr.hosts.net.nz';
$username = 'wclp_platform';
$password = '=[.uPVK%rW;2';
$database = 'wclp_williams_platform';

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
} 

/*----
$now = date('Y-m-d'); 
$c_s_date = date('Y-m-d', strtotime($now. ' + 20 days'));

$sql_user = "SELECT users.* 
		FROM users 
		LEFT JOIN users_application ON users.uid=users_application.user_id
		WHERE users_application.application_id = '1' && users.company_id='28'";
$result_user = mysqli_query($conn, $sql_user);

while($row = mysqli_fetch_assoc($result_user)) {
	$user_id = $row["uid"];
	$user_name = $row["username"];
	$user_email = $row["email"];

	$sql_stage = "SELECT stage_task.*,development.development_name,stage_phase.phase_name,users.username,users.email 
			FROM stage_task 
			LEFT JOIN development ON stage_task.development_id=development.id
			LEFT JOIN stage_phase ON stage_task.phase_id=stage_phase.id
			LEFT JOIN users ON stage_task.task_person_responsible=users.uid
			WHERE users.uid = '$user_id' && stage_task.planned_completion_date < '$c_s_date' && stage_task.stage_task_status = '0' && stage_task.task_start_date > '0000-00-00' && stage_task.planned_completion_date > '0000-00-00' && stage_task.task_person_responsible > '0'
			ORDER BY stage_task.planned_completion_date DESC";
	$result_stage = mysqli_query($conn, $sql_stage);

	if(mysqli_num_rows($result_stage) > 0) {
		$alert = '';
		while($row_s = mysqli_fetch_assoc($result_stage)) {
			$alert .= '<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tbody>
					
					<tr>
						<td width="30%" bgcolor="#d8d8da">Development</td><td bgcolor="#ebecec">'.$row_s["development_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Stage</td><td bgcolor="#ebecec">'.$row_s["stage_no"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Phase</td><td bgcolor="#ebecec">'.$row_s["phase_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Task</td><td bgcolor="#ebecec">'.$row_s["task_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Completion Date</td><td bgcolor="#ebecec">'.$row_s["planned_completion_date"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Person Responsible</td><td bgcolor="#ebecec">'.$row_s["username"].'</td>
					</tr>
				</tbody>
			</table><br>';
		}

		$html = '<html><body>';
		$html .= 'Hi '.$user_name.',<br><br>';
		$html .= $alert;
		$html .= "</body></html>";
		
		$subject ='Under Caution Report';
					
		$headers = "From: ".$user_email . "\r\n";
		$headers .= "Reply-To: ". $user_email . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		//mail($user_email, $subject, $html, $headers);
	}
}

*/


$sql = "SELECT stage_task.*,development.development_name,stage_phase.phase_name,users.username,users.email 
		FROM stage_task 
		LEFT JOIN development ON stage_task.development_id=development.id
		LEFT JOIN stage_phase ON stage_task.phase_id=stage_phase.id
		LEFT JOIN users ON stage_task.task_person_responsible=users.uid
		WHERE development.wp_company_id = '34' && stage_task.stage_task_status = '0' && stage_task.task_start_date > '0000-00-00' && stage_task.planned_completion_date > '0000-00-00' && stage_task.task_person_responsible > '0'
		ORDER BY stage_task.planned_completion_date DESC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) 
{
	$message= '';
    $message .= '<html><body>';	
	$message .= '<h2>Under Caution Report</h2>';
    while($row = mysqli_fetch_assoc($result)) {

		$completion_date = strtotime($row["planned_completion_date"]);
		$start_date = strtotime($row["task_start_date"]);

		$now = time();
	    $datediff = $completion_date - $now;
	    $day = floor($datediff/(60*60*24));
		
		if(20 >= $day){

			$message .= '<table border="1" width="100%" cellpadding="4" cellspacing="0">
				<tbody>
					
					<tr>
						<td width="30%" bgcolor="#d8d8da">Development</td><td bgcolor="#ebecec">'.$row["development_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Stage</td><td bgcolor="#ebecec">'.$row["stage_no"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Phase</td><td bgcolor="#ebecec">'.$row["phase_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Task</td><td bgcolor="#ebecec">'.$row["task_name"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Completion Date</td><td bgcolor="#ebecec">'.$row["planned_completion_date"].'</td>
					</tr>
					<tr>
						<td width="30%" bgcolor="#d8d8da">Person Responsible</td><td bgcolor="#ebecec">'.$row["username"].'</td>
					</tr>
				</tbody>
			</table><br>';

		}
		
    }

	$message .= "</body></html>";


	$to  = 'andrewc@horncastle.co.nz, bill@horncastle.co.nz, dayle@horncastle.co.nz';
	$email = 'hds@wclp.co.nz';
	$subject ='Under Caution Report';
				
	$headers = "From: ".$email . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
	$headers .= 'BCc: ziaur@williamsbusiness.co.nz' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($to, $subject, $message, $headers);
} 


mysqli_close($conn);

?>