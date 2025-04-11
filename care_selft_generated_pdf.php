	<?php
include_once "../../config.php"; 
// Get the query parameters from the URL
$certificateNo = isset($_GET['certificate_no']) ? $_GET['certificate_no'] : '';
$firstName = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$lastName = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$mobileNo = isset($_GET['mobile_no']) ? $_GET['mobile_no'] : '';
$dob = isset($_GET['dob']) ? $_GET['dob'] : '';
$planName = isset($_GET['plan_name']) ? $_GET['plan_name'] : '';

// Validation
$errors = [];

// print_r($_GET);exit;


if (empty($certificateNo)) {
    $errors[] = "Certificate number is required.";
}

if (empty($firstName)) {
    $errors[] = "First name is required.";
}

if (empty($lastName)) {
    $errors[] = "Last name is required.";
}

if (empty($startDate) || !validateDate($startDate)) {
    $errors[] = "Valid start date is required.";
}

if (empty($endDate) || !validateDate($endDate)) {
    $errors[] = "Valid end date is required.";
}

if (empty($mobileNo) || !preg_match('/^[0-9]{10}$/', $mobileNo)) {
    $errors[] = "Valid mobile number is required (10 digits).";
}

if (empty($dob) || !validateDate($dob)) {
    $errors[] = "Valid date of birth is required.";
}

if (empty($planName)) {
    $errors[] = "Plan name is required.";
}

if (count($errors) > 0) {
    echo implode('<br>', $errors); // Display validation errors
    exit;
}

// If all validations pass, proceed with further processing (e.g., generating the PDF)
// echo "All validations passed. Processing data...";

// Example function to validate date format (YYYY-MM-DD)
function validateDate($date) {
	return true; // for only some time 
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// get db data 
if(isset($_GET['o'])){
	$orderId = base64_decode($_GET['o']);
	$userId = base64_decode($_GET['uid']);
}else{
	echo "Something went wrong!!";exit;
}
$sql = "SELECT u.*, p.sumAssured FROM user as u LEFT JOIN tru_order as o ON o.userId = u.id LEFT JOIN plan as p ON p.id = o.planId WHERE u.id = '".$userId."' ";
$result  = mysqli_query($conn, $sql);
$uData = mysqli_fetch_assoc($result); 
// ======================================================
$sql = "SELECT * FROM user WHERE parent_id = '".$userId."' AND status = '1'";
$result  = mysqli_query($conn, $sql);
$members = array();
 while($row = mysqli_fetch_assoc($result))
  {
     $members[] = $row;
  }

$coverType = empty($members)?"Individual":"Family";
//pre($members);exit;
$memberTr = '';
if(!empty($members)){
	foreach ($members as $v) {
		$memberTr .= '<tr>
          				<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">'.$v['name'].'</div>
          				</td>
          				<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">TRFD'.$v['id'].'</div>
          				</td>
          					<td style="border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">'.date("M d, Y", strtotime($v['dob'])).'</div>
          				</td>
          					<td style="border-bottom:1px solid #8ca3d4;
          					border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">'.ucfirst($v['relation']).'</div>
          				</td>
          					<td style="border-bottom:1px solid #8ca3d4;
          					border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">'. date("M d, Y", strtotime($endDate)).'</div>
          				</td>
          				<td style="border-bottom:1px solid #8ca3d4;
          					border-right:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
          					<div style="color:#000">NONE</div>
          				</td>
          				
          			</tr>';
	}
}

// Initialize the full address variable
$fullAddress = "";

// Check if each field exists and is not empty, then concatenate them
if (!empty($uData['address'])) {
    $fullAddress .= $uData['address'];
}
if (!empty($uData['area'])) {
    $fullAddress .= ", " . $uData['area'];
}
if (!empty($uData['city'])) {
    $fullAddress .= ", " . $uData['city'];
}
if (!empty($uData['state'])) {
    $fullAddress .= ", " . $uData['state'];
}
if (!empty($uData['zip'])) {
    $fullAddress .= " - " . $uData['zip'];
}

// Remove any leading comma or extra spaces
$fullAddress = trim($fullAddress, ", ");

// Validate if full address is created
if (empty($fullAddress)) {
    $fullAddress = "Address not available"; // or handle accordingly
}

// Output the full address





	$myTemplate  = '<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:roboto; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>


								              <table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr>
								              			  <td>
								              				<img src="img/insurmonthly-logo.png" style="width: 250px; float: left;">

								              			  </td>  
								              			
								              			</tr> 
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr style="">
								              			  <td style="padding-top:25px; padding-bottom:25px;">
								              			   <p style=" border-left: 6px solid #cbcbcb;  padding-right:10px; font-size:30px; padding-left: 10px; font-family
								              			   :Roboto; "><b>&nbsp;Health & Wellness Subscription Documents</b></p>

								              			  </td>  
								              			
								              			</tr> 
								              			<tr>
								              				<td style="font-weight: 400;">
								              					<p style="margin-top:30px;margin-bottom: 0; font-size: 15px;font-weight: 400;">Block L, Embassy Tech Village, Devarabisanahalli, Outer Ring Road, Bellandur,Bengaluru Ka - 560103, India </p>
								              					<p style="margin-top:0px; font-size: 15px;">In case of any queries / assistance, please write to us at 
								              						<a href="mailto:help@insurmonthly.com">help@insurmonthly.com</a>

								              					</p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>

								              	<table  cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px;">
								              		<tbody>
								              			<tr>
								              				<td style="background:#fc7a69; padding:10px;">
								              					<p style="  color:#fff; margin-top:20px;">Certificate </p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr>
								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-left:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;">
								              					<p>Certificate Number </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['certificate_no']) ? $_GET['certificate_no'] : 'N/A') . '</p>
								              				</td>
								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;">
								              					<p>Plan Name </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['plan_name']) ? urldecode($_GET['plan_name']) : 'N/A') . ' </p>
								              				</td>



								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;border-left:1px solid #7f7f7f;">
								              					<p>Certificate Start Date </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['start_date']) ? date("M d, Y", strtotime($_GET['start_date'])) : 'N/A') . '</p>
								              				</td>
								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;">
								              					<p>Certificate End Date</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['end_date']) ? date("M d, Y", strtotime($_GET['end_date'])) : 'N/A') . '</p>
								              				</td>



								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;border-left:1px solid #7f7f7f;">
								              					<p>First Name</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['first_name']) ? $_GET['first_name'] : 'N/A') . '</p>
								              				</td>
								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;">
								              					<p>Last Name </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['last_name']) ? $_GET['last_name'] : 'N/A') . '</p>
								              				</td>



								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;border-left:1px solid #7f7f7f;">
								              					<p>Mobile Number</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['mobile_no']) ? $_GET['mobile_no'] : 'N/A') . '</p>
								              				</td>
								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;">
								              					<p>Date of Birth</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>'. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . '</p>
								              				</td>



								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #e4e4e4;border-left:1px solid #7f7f7f;">
								              					<p>Address Line 1 </p>
								              				</td>

								              				<td colspan="3" style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>	'. (isset($fullAddress) ? $fullAddress : 'N/A') . '</p>
								              				</td>

								              			</tr>

								              		
								              		</tbody>
								              	</table>

								              		<table  cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px; ;">
								              		<tbody>
								              			<tr>
								              				<td style="background:#ec8575; padding:10px;">
								              					<p style=" color:#fff; margin-top:20px;"> Wellness Benefits </p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              										              			<tr>
								              				<td style="padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;border-left:1px solid #7f7f7f;background: #fff;">
								              					<p>Doctor Fees</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>Visit a doctor or consult them - we got you covered get 50% off all doctor consultations.</p>
								              				</td>
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;border-left:1px solid #7f7f7f;">
								              					<p>Lab Tests</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>Radiology, ECG or any prescribe diagnostic test - we`ll take care of it 20% instant reimbursement.</p>
								              				</td>
								              				
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #7f7f7f;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;">
								              					<p>Medicine Bills </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>We`ll pay for all of your medical bills 20% instant reimbursement on prescribe medicine bills.</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #7f7f7f;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;">
								              					<p>Mental Health </p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>Get one free wellness session in a year.</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #7f7f7f;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;">
								              					<p>Dental Fees</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>20% instant reimbursement on dental consultation.</p>
								              				</td>

								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #7f7f7f;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;">
								              					<p>Virtual Doctor fees</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>Get free unlimited virtual doctor consultation</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #7f7f7f;
								              				   border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;background: #fff;">
								              					<p>Body Check-up</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #7f7f7f;border-bottom:1px solid #7f7f7f;">
								              					<p>Get one free full body check-up in a year.</p>
								              				</td>

								              			</tr>

								              		</tbody>
								              	</table>
								                </td>
								             </tr>
								          </tbody>
								       </table>
								    </td>
								 </tr>
								</tbody>
							</table>

<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:roboto; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>


								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr>
								              			  <td align="left">
								              			
								              				<img src="img/care-logo.png" style="; float: left;">
								              				

								              			  </td>  
								              			  <td align="right">
								              			 
								              				<img src="img/best-claim.png" style="float: right;">
								              			
								              			  </td>
								              			</tr> 
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="padding-top: 20px;padding:18px;">
								              		<tbody>
								              			<tr valign="top">
								              				<td style="width:250px;">
								              					<div>
								              						<p style="font-size:16px;">Certificate </p>
								              						<p style="margin-top:0; margin-bottom: 0;">'. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '</p>
								              						<p style="margin-top:0;">'. (isset($fullAddress) ? $fullAddress : 'N/A') . '</p>
								              						
								              					</div>
								              				</td>
								              				<td>
								              						<table  cellpadding="0" cellspacing="0" width="100%" style="padding-left:18px;  valign:right; float: right;">
								              							<tbody style="align:right">
								              								<tr >
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; font-size: 14px;  width: 172px; background: #faed96;">Group Policyholder Name </td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4;border-right: 1px solid #8ca3d4;  padding: 6px; ">Trufedu Budgetary Education Private Limited</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; width: 172px;background: #faed96;">Group Policy No.</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; ">	87800876</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; width: 172px;background: #faed96;">Certificate of Insurance No</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; ">'. (isset($_GET['certificate_no']) ? $_GET['certificate_no'] : 'N/A') . '</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; width: 172px;background: #faed96;">Plan Name</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; ">'. (isset($_GET['plan_name']) ? urldecode($_GET['plan_name']) : 'N/A') . '</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; width: 172px;background: #faed96;">Covered Type</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; ">'. (isset($coverType) ? $coverType : 'N/A') . '</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; width: 172px;background: #faed96;">Policy Period - Start Date</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; ">00:00 hrs '. (isset($_GET['start_date']) ? date("M d, Y", strtotime($_GET['start_date'])) : 'N/A') . '</td>
								              								</tr>
								              									<tr>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-bottom: 1px solid #8ca3d4; width: 172px;background: #faed96;">Policy Period - End Date</td>
								              									<td style="border-top:1px solid #8ca3d4;border-left: 1px solid #8ca3d4; padding: 6px; border-right: 1px solid #8ca3d4; border-bottom: 1px solid #8ca3d4; ">Midnight '. (isset($_GET['end_date']) ? date("M d, Y", strtotime($_GET['end_date'])) : 'N/A') . '</td>
								              								</tr>
								              							</tbody>

								              						</table>
								              						
										              				</td>
										              			</tr>
										              		</tbody>
										              	</table>


                                                    <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#faed96;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              					<div style="color:#000">Details of Applicant</div>
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>

                                                <table  cellpadding="0" cellspacing="0" width="100%" style=" ">
 								              		<tbody>
 								              			<tr>
 								              				<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4; 	">
 								              					<div style="color:#000"><b>Policy Holder</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #8ca3d4;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Date of Birth</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Client ID</b></div>
 								              				</td>
 								              					
 								              			</tr>
 								              			<tr>
 								              				<td style="border-left:1px solid #8ca3d4; border-right:1px solid #8ca3d4; 
 								              				border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	border-right:1px solid #8ca3d4;">
 								              					<div style="color:#000">  '. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . '</div>	

 								              					<td style="border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	border-right:1px solid #8ca3d4;">
 								              					<div style="color:#000">TRFD'.$userId.'</div>
 								              				</td>
 								              					
 								              			</tr>	
 								              			
 								              				
 								              		</tbody>
 								              	</table>
 								              	   <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#faed96; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              					<div style="color:#000">Details of Insured Person</div>
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
 								              	    <table  cellpadding="0" cellspacing="0" width="100%" style=" ">
 								              		<tbody>
 								              			<tr>
 								              				<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Name</b></div>
 								              				</td>
 								              					<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Client ID</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Date of Birth</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;">
 								              					<div style="color:#000"><b>Relationship</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Insured with the Company (since)</b></div>
 								              				</td>	
 								              		
 								              					<td style="border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Pre-existing diseases since</b></div>
 								              				</td>
 								              				
 								              			</tr>

 								              			<tr>
 								              				<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '</div>
 								              				</td>
 								              				<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">TRFD'.$userId.'</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . '</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">Self</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['start_date']) ? date("M d, Y", strtotime($_GET['start_date'])) : 'N/A') . '</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              					border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">NONE</div>
 								              				</td>
 								              				
 								              			</tr>
 								              			'.$memberTr.'

 								              			
 								              		</tbody>
 								              	</table>

 								              	    <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#faed96; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              					<div style="color:#000">Contact details for Claims & Policy Servicing</div>
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
								              <table  cellpadding="0" cellspacing="0" width="100%" style="">
 								              		<tbody>
 								              			<tr>
 								              				<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Correspondence address</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Care Health Insurance Limited, Vipul Tech Square, Tower C, 3rd Floor, Golf Course Road, Sector-43, Gurugram-122009 (Haryana)</div>
 								              				</td>
 								              			
 								              			</tr>
 								              				<tr>
 								              				<td style="border-left:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>E-mail ID for Claims  
 								              				</b></div>
 								              				</td>
 								              				<td style="
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000"><a href="mailto:claims@careinsurance.com" style="color: #000; text-decoration: none;">claims@careinsurance.com </a></div>
 								              				</td>
 								              		

 								              			</tr>
 								              				<tr>
 								              				<td style="border-left:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Website</b></div>
 								              				</td>
 								              				<td style="
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000"><a href="https://www.careinsurance.com/" style="color: #000; text-decoration: none;">www.careinsurance.com</a></div>
 								              				</td>
 								              		

 								              			</tr>
 								              		</tbody>
 								              	</table>
 								              	  <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#faed96; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              					<div style="color:#000">Intermediary Details</div>
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
 								              	     <table  cellpadding="0" cellspacing="0" width="100%" style="">
 								              		<tbody>
 								              			<tr>
 								              				<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Name</b></div>
 								              				</td>
 								              					<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Code</b></div>
 								              				</td>
 								              					<td style="border:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><b>Contact Details</b></div>
 								              				</td>
 								              				
 								              			
 								              			</tr>
 								              			<tr>
 								              					<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">Care Health Insurance Ltd.</div>
 								              				</td>
 								              				<td style="border-left:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">Direct</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #8ca3d4;border-right:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000"><a herf="https://www.careinsurance.com/contact-us.html">https://www.careinsurance.com/contact-us.html</a></div>
 								              				</td>
 								              			</tr>
 								              			
 								              		</tbody>
 								              	</table>

										        <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr style="padding-top:100px">
 								              				<td align="left">
 								              				<img src="img/bottom-bar-left.png">
 								              				</td>
 								              		
 								              				<td align="right">
 								              				<img src="img/bottom-bar-right.png">
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
 								              </td>
 								          </tr>
 								      </tbody>
 								  </table>
 								</td>
 							</tr>
 						</tbody>
 					</table>
 

<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:roboto; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>
								              								            <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              					<div style="color:#000"><b>Benefits</b></div>
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
	                                             <table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:5px;">
 								              		<tbody>
 								              			<tr>
 								              				<td style="border:1px solid #8ca3d4; padding:10px; background: #faed96;	">
 								              					<div style="color:#000"><b>S.NO</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #faed96;	">
 								              					<div style="color:#000"><b>Particulars</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #faed96;	">
 								              					<div style="color:#000"><b>Basis of Offering</b>	</div>
 								              				</td>

 								              			</tr>

 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">1</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Hospitalization Exp-Sum Insured (SI) in Rs.</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">'. (isset($uData['sumAssured']) ? str_replace("00000", "L", $uData['sumAssured']) : 'N/A') . '</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">2</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">In - patient care </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">3</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Day Care Treatment</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">4</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Pre-hospitalization Medical expenses</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">30 days</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">5</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Post-hospitalization Medical expenses</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">60 days</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">6</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Domestic Road Ambulance </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to Rs.1000 per hospitalization</div>
 								              				</td>

 								              			</tr>
 								              			
 								              				
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">7</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Alternative Treatments (IPD basis)</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>		

 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">8</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Donor Expenses</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>
 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">9</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Domiciliary Hospitalization</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to Sl; if it continues for a period exceeding 3 consecutive days</div>
 								              				</td>

 								              			    </tr>
 								              			    <tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">10</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Lasik Surgery </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Up to Rs.25,000</div>
 								              				</td>

 								              			</tr>	
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">11</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Wait Period-15 Days</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Yes (except for Injuries/Accident)</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">12</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Named Ailment (as defined in Group Care 360 Product)</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">12 Months</div>
 								              				</td>

 								              			</tr>	
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">13</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Pre-existing diseases</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">12 Months</div>
 								              				</td>

 								              			</tr>	
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">14</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">On Room rent</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Single Private Room </div>
 								              				</td>

 								              			</tr>	
 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">15</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">ICU charges</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">No Limits</div>
 								              				</td>

 								              			</tr>	
 								              					<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">16</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Lasik Surgery</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">Covered if power of the eye is +/- 7.5 max</div>
 								              				</td>

 								              			</tr>
 								              			 <tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">17</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Personal Accident Cover-Sum Insured</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">1 Lac (Self only)</div>
 								              				</td>

 								              			</tr>
 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">18</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Accidental Death</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">100% of SI</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; background: #fff;	">
 								              					<div style="color:#000">19</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; background: #fff;	">
 								              					<div style="color:#000">Claims payout</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; background: #fff;	">
 								              					<div style="color:#000">HE: Cashless (within network) / Re-imbursement, PA: Re-imbursement</div>
 								              				</td>

 								              			</tr>
 								              		

 								              			
 								              		</tbody>
 								              	</table>

										        <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td align="left">
 								              				<img src="img/bottom-bar-left.png">
 								              				</td>
 								              		
 								              				<td align="right">
 								              				<img src="img/bottom-bar-right.png">
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>


								              </td>
								          </tr>
								      </tbody>
								  </table>
								</td>
							</tr>
						</tbody>
					</table>








	<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:roboto; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>

										     
								      	<table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td align="left">
								              			
								              				<img src="img/care-logo.png" style="; float: left;">
								              				

								              			  </td>  
								              			  <td align="right">
								              			 
								              				<img src="img/best-claim.png" style="float: right;">
								              			
								              			  </td>
								              			</tr> 
 								              		</tbody>
 								              	</table>

 								              	   	<table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			   <p><b>Key Exclusions</b></p>
								              			   <p style="font-size:13px;">The company shall not be liable to make payment for any claim directly or indirectly caused by, based on, arising out of howsoever attributable to any of the following except covered by way of an extension:</p>
								              			   <ul>
								              			   	<li style=" margin-bottom: 6px;">Treatment taken from anyone who is not a Medical Practitioner or from a Medical Practitioner who is practicing outside the discipline for which he is licensed or any kind of self-medication.</li>
								              			   	<li style=" margin-bottom: 6px;">Charges incurred (or Treatment undergone) in connection with routine eye examinations and ear examinations, dentures, artificial teeth and all other similar external appliances and / or devices whether for diagnosis or treatment.</li>
								              			   	<li style=" margin-bottom: 6px;">Treatment of any external Congenital Anomaly or Illness or defects or anomalies or treatment relating to external birth defects.</li>
								              			   	<li style=" margin-bottom: 6px;">Medical treatment expenses traceable to childbirth (including complicated deliveries and caesarean sections incurred duringhospitalization)or expenses towards lawful medical termination of pregnancy during the policy period.</li>
								              			   	<li style=" margin-bottom: 6px;">Cosmetic surgery or plastic surgery or related treatment of any description, including any complication arising from these treatments, other than as may be necessitated due to an Injury, cancer or burn</li>
								              			   	<li style=" margin-bottom: 6px;">Any Illness or Injury directly or indirectly resulting or arising from or occurring during commission of any breach of any law by the Insured Member with any criminal intent</li>
								              			   	<li style=" margin-bottom: 6px;">Act of self-destruction or self-inflicted Injury, attempted suicide or suicide while sane or insane</li>
								              			   	<li style=" margin-bottom: 6px;">Any Illness or Injury attributable to consumption, use, misuse or abuse of intoxicating drugs or alcohol or Tobacco (in any Form like cigarettes or Gutka etc..)or any other hallucinogens drugs.</li>
								              			   </ul>
								              			   <p style="font-size:13px;">Note: This is an illustrative list of exclusions. Please refer Group Policy Terms and Conditions at our website www.careinsurance.com or Group Policy T&C issued to the Group Master Policy Holder.</p>

								              			  </td>  
								              			 
								              			</tr> 
 								              		</tbody>
 								              	</table>

 								              	  <table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			   <p><b>Portability/Renewability</b></p>
								              			</td>
								              		</tr>
								              	</tbody>
								              </table>
								                 	<table  cellpadding="0" cellspacing="0" width="100%" style="padding-top: 20px;">
								              		<tbody>
								              										              			<tr>
								              				<td style="padding:10px;border-top:1px solid #8ca3d4;
								              				   border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;border-left:1px solid #8ca3d4;background: #fff; background: #faed96;">
								              					<p><b>S.NO</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #8ca3d4;border-top:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4; background: #faed96;">
								              					<p><b>Particulars </b></p>
								              				</td>
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;background: #fff;border-left:1px solid #8ca3d4;">
								              					<p>1.</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;">
								              					<p>You can renew the policy only if Master Policy is renewed by Group Master Policyholder.</p>
								              				</td>
								              				
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;background: #fff;border-left:1px solid #8ca3d4;">
								              					<p>2.</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;">
								              					<p>Care Health Insurance reserves the right to change premium/benefits of the Group Policy at the time of Renewal in consultation with Master Policy Holder.</p>
								              				</td>
								              				
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;background: #fff;border-left:1px solid #8ca3d4;">
								              					<p>3.</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;">
								              					<p>You can migrate your existing Policy from this scheme to any Health Insurance retail product of Care Health Insurance Policy Issuance subject to Underwriting guidelines of company on the date of migration.</p>
								              				</td>
								              				
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;background: #fff;border-left:1px solid #8ca3d4;">
								              					<p>4.</p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #8ca3d4;border-bottom:1px solid #8ca3d4;">
								              					<p>Once the Group Care Health Insurance policy is migrated to retail product of Care Health Insurance, customer will have to pay premium as per the New product underwriting guidelines.</p>
								              				</td>
								              				
								              			</tr>
								              		</tbody>
								              	</table>
								              	 <table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			   <p><b>Grievance Redressal/Complaints</b></p>
								              			</td>
								              		</tr>
								              	</tbody>
								              </table>

								                	 <table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			  <div style="font-size:13px;">In case of any grievance the Insured Person may contact the Company through</div>
								              			  <div style="font-size:13px;">Website/linkhttps://www.careinsurance.com/contact-us.html</div>
								              			  <div style="font-size:13px;">Mobile App: Care Health- Customer App</div>
								              			  <div style="font-size:13px;">Toll free (WhatsApp Number): 8860402452</div>
								              			  <div style="font-size:13px;">Courier. Any of Company`s Branch Office or Corporate Office</div>
								              			  <div style="font-size:13px;">Insured Person may also approach the grievance cell at any of the Company`s branches with the details of grievance.</div>
								              			  <div style="font-size:13px;">If Insured Person is not satisfied with the redressal of grievance through one of the above methods, Insured Person may contact the grievance officer at Branch Office or corporate office.</div>
								              			  <div style="font-size:13px;">For updated details of grievance officer, kindly refer the link- <a href="https://www.careinsurance.com/customer-grievance-redressal.html">https://www.careinsurance.com/customer-grievance-redressal.html</a></div>
								              			  <div style="font-size:13px;">If Insured Person is not satisfied with the redressal of grievance through above methods, the Insured Person may also approach the office of Insurance Ombudsman of the respective area/region for redressal of grievance as per Insurance Ombudsman Rules 2017 The details of insurance ombudsman offices may be referred in the annexure shared along with the Master policy document</div>
								              			  <div style="font-size:13px;">Grievance may also be lodged at IRDAI integrated Grievance Management</div>
								              		
								              			</td>
								              		</tr>
 								              		</tbody>
 								              	</table>
 								              		<table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td align="left">
 								              				<img src="img/bottom-bar-left.png">
 								              				</td>
 								              		
 								              				<td align="right">
 								              				<img src="img/bottom-bar-right.png">
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
 								              </td>
 								           </tr>
 								        </tbody>
 								     </table>
 								  </td>
 								</tr>
 							</tbody>
 						</table>

 						   	<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:roboto; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>



								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr>
								              			  <td align="left">
								              			
								              				<img src="img/care-logo.png" style="; float: left;">
								              				

								              			  </td>  
								              			  <td align="right">
								              			 
								              				<img src="img/best-claim.png" style="float: right;">
								              			
								              			  </td>
								              			</tr> 
								              		</tbody>
								              	</table>
								              	 <table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			   <p><b>For Care Health Insurance Limited</b></p>
								              			</td>
								              		</tr>
								              		<tr>
								              			  <td>
								              			   <img src="img/sign.png">
								              			    <div style="font-size:13px; margin-bottom: 6px;">Authorized Signatory</div>
								              			    <div style="font-size:13px;margin-top:20px; margin-bottom: 6px;"><b>Date of Issue:</b> '. (isset($_GET['start_date']) ? date("d/M/Y", strtotime($_GET['start_date'])) : 'N/A') . '</div>
								              			    <div style="font-size:13px; margin-bottom: 6px;"><b>Place of Issue:</b> Gurgaon, Haryana</div>
								              			    <div style="font-size:13px; margin-bottom: 6px;"><b>Service Branch: </b> Vipul Tech Square TowerC3rd Floor Sector43Golf Course Road Branch Contact No. 0124-6141810</div>
								              			    <div style="font-size:13px; margin-bottom: 6px;">Gurgaon Haryana 122009 Gurgaon, Haryana, 122009</div>
								              			    <div style="font-size:13px; margin-top: 50px;">Consolidated Stamp Duty paide vide E-Challan GRN No. 0117751470 dated 13/06/2024. RCM Applicability - N/A</div>

																	SAC: 997133 and Description of Service: Accident and Health Insurance Services State

																	 <div style="font-size:13px; ">GSTIN No.: 06AADCR6281N1ZW</div>

																	 <div style="font-size:13px; ">UIN:CHIHLGP25038V022425</div>

																	 <div style="font-size:13px; ">CIN: U66000DL2007PLC161503</div>
																	<div style="margin-top:15px;margin-bottom: 10px;">Note:</div>

																	<div style="font-size:10px;">1- Validity of this certificate is subject to terms and conditions of Group Policy issued to the Group Policyholder</div>

																	<div style="font-size:10px;">2- In event of non-receipt of Premium, this certificate of insurance automatically stands cancelled from inception, imespective of whether a separate communication is sent or not. This policy is based on the information provided by the Insured to the Group Administrator, in caме you find any discrepancy in the same, please contact us immediately.</div>

																	<div style="font-size:10px;">3- This Certificate of insurance is governed by and is subject to the Terms and Conditions of the referred Group Policy</div>
								              			</td>
								              		</tr>
								              	</tbody>
								              </table>
								               <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              				<tr>
 								              				<td align="left">
 								              				<img src="img/bottom-bar-left.png">
 								              				</td>
 								              		
 								              				<td align="right">
 								              				<img src="img/bottom-bar-right.png">
 								              				</td>
 								              			</tr>
 								              		</tbody>
 								              	</table>
								              </td>
								           </tr>
								        </tbody>
								     </table>
								  </td>
								</tr>
							</tbody>
						</table>
';



 					
 					// download pdf ===========================================
 					// echo $myTemplate; exit;
 					// $rData =   = insureInvoiceTemplate($data);
		        include_once "../assets/plugins/php_pdf/vendor/autoload.php";
		        // $mpdf = new \Mpdf\Mpdf();
		        // @$mpdf->WriteHTML($myTemplate);

		        $mpdf = new \Mpdf\Mpdf([
		            'mode' => 'utf-8',
		            'format' => 'A4',
		            'fontDir' => [
		                __DIR__ . '/pdf_font', // Your custom font directory
		            ],
		            'fontdata' => [
		                'lato' => [
		                    'R' => 'Roboto-Regular.ttf',
		                    'B' => 'Roboto-Bold.ttf',
		                    'I' => 'Roboto-Italic.ttf',
		                    'BI' => 'Roboto-BoldItalic.ttf',
		                ],
		            ],
		            'default_font' => 'Roboto',
		            'tempDir' => __DIR__ . '/temp', // Ensure this directory exists
		        ]);

		        @$mpdf->WriteHTML('
		            <style>
		                body {
		                    font-family: Roboto;
		                }
		                h1 {
		                    font-family: Roboto;
		                    font-weight: bold;
		                }
		                font-size:30px;
		            </style>
		        '.$myTemplate);

		        $fileName1  = "temp_invoice_" . date("y-m-d-h-i-s") . ".pdf";
		        $fileTempName = "../temp/care/".$fileName1;
		        $fileName = $fileTempName;

		        $mpdf->Output($fileName);
		        //echo "https://trufedu.com/api/admin/temp/". $fileName1;
		        $url = $base_url. "admin/temp/care/". $fileName1;
		        

 					?>





<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Download PDF</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<script>
        // JavaScript code to redirect on page load
        window.onload = function() {
            // Get the PHP variable into JavaScript
            var url = "<?php echo $url; ?>";
            // Redirect to the URL
            window.location.href = url;
        };
    </script>
</head>
<body>
		<div class="container">
			<div class="row mt-5">
				<div class="col-sm-12 text-center" style="padding-top: 30vh;">
					<p><b>Policy PDF Generated.</b></p><br/>
					<?php echo '<a href="'.$url.'" class="btn btn-success" id="btnDownload" >Download Now</a>'; ?>
				</div>
			</div>
		</div>
</body>
</html>
