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
		$memberTr .= '<tr><td style="border-left:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4;border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
					<div style="color:#000">'.$v['name'].' </div>
				</td>
					<td style="border-bottom:1px solid #c4c4c4;border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
					<div style="color:#000">'.date("M d, Y", strtotime($v['dob'])).' </div>
				</td>
					<td style="border-bottom:1px solid #c4c4c4;
					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
					<div style="color:#000">'.$v['relation'].'</div>
				</td>
					<td style="border-bottom:1px solid #c4c4c4;
					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
					<div style="color:#000">'. date("M d, Y", strtotime($endDate)).'</div></td></tr>';
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





	$myTemplate  = '<table  cellpadding="0" cellspacing="0" width="800" style="margin:auto; border: 1px solid #d9d9d9; font-family:lato; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>


								              <table  cellpadding="0" cellspacing="0" width="100%" style="padding-bottom: 30px;">
								              		<tbody>
								              			<tr>
								              			  <td>
								              				<img src="https://insurmonthly.com/assets/images/care_pdf/insurmonthly-logo.png" style="width: 250px; float: left;">

								              			  </td>  
								              			
								              			</tr> 
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="padding-top: 20px;">
								              		<tbody>
								              			<tr style=""><br><br>
								              			  <td style="border-left: 6px solid #cbcbcb; padding-left: 20px; ">
								              			   <p style="font-size:30px; margin-left: 10px;">Health & Wellness Subscription Documents</p>

								              			  </td>  
								              			
								              			</tr> 
								              			<tr>
								              				<td style="padding-top:20px;padding-bottom: 20px;">
								              					<p style="margin-top:30px;margin-bottom: 0; font-size: 15px;"><b>Block L, Embassy Tech Village, Devarabisanahalli, Outer Ring Road, Bellandur,Bengaluru Ka - 560103, India </b></p>
								              					<p style="margin-top:0px; font-size: 15px;"><b>In case of any queries / assistance, please write to us at 
								              						<a href="mailto:help@insurmonthly.com">help@insurmonthly.com</a>

								              					</b></p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>

								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              			<tr>
								              				<td style="background:#ec8575; padding:10px;">
								              					<p style=" color:#fff; margin-top:20px;"> <b>Certificate </b> </p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>
								              	<table cellpadding="0" cellspacing="0" width="100%" style="">
													  <tbody>
													    <tr>
													      <td style="padding:10px;border:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;">
													        <p><b>Certificate Number </b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-top:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['certificate_no']) ? $_GET['certificate_no'] : 'N/A') . '</p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-top:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;">
													        <p><b>Plan Name </b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-top:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['plan_name']) ? urldecode($_GET['plan_name']) : 'N/A') . '</p>
													      </td>
													    </tr>
													    <tr>
													      <td style="padding:10px;padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;border-left:1px solid #c8c7c7;">
													        <p><b>Certificate Start Date </b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['start_date']) ? date("M d, Y", strtotime($_GET['start_date'])) : 'N/A') . '</p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;">
													        <p><b>Certificate End Date </b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['end_date']) ? date("M d, Y", strtotime($_GET['end_date'])) : 'N/A') . '</p>
													      </td>
													    </tr>
													    <tr>
													      <td style="padding:10px;padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;border-left:1px solid #c8c7c7;">
													        <p><b>First Name</b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['first_name']) ? $_GET['first_name'] : 'N/A') . '</p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;background: #e4e4e4;">
													        <p><b>Last Name </b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['last_name']) ? $_GET['last_name'] : 'N/A') . '</p>
													      </td>
													    </tr>
													    <tr>
													      <td style="padding:10px;padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;border-left:1px solid #c8c7c7;">
													        <p><b>Mobile Number</b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['mobile_no']) ? $_GET['mobile_no'] : 'N/A') . '</p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;background: #e4e4e4;">
													        <p><b>Date of Birth</b></p>
													      </td>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . '</p>
													      </td>
													    </tr>
													    <tr>
													      <td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #e4e4e4;font-size: 13px;border-left:1px solid #c8c7c7;">
													        <p><b>Address Line 1 </b></p>
													      </td>
													      <td colspan="3" style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
													        <p>'. (isset($fullAddress) ? $fullAddress : 'N/A') . '</p>
													      </td>
													    </tr>
													  </tbody>
													</table>
                                                       <br pagebreak="true" />

								              		<table  cellpadding="0" cellspacing="0" width="100%" style="page-break-before:always;">
								              		<tbody>
								              			<tr>
								              				<td style="background:#ec8575; padding:10px;">
								              					<p style="background:#ec8575; color:#fff;"> <b>Wellness Benefits </b> </p>
								              				</td>
								              			</tr>
								              		</tbody>
								              	</table>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="">
								              		<tbody>
								              										              			<tr>
								              				<td style="padding:10px;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;border-left:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Doctor Fees</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>Visit a doctor or consult them - we got you covered get 50% off all doctor consultations.</p>
								              				</td>
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;border-left:1px solid #c8c7c7;">
								              					<p><b>Lab Tests</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>Radiology, ECG or any prescribe diagnostic test - we`ll take care of it 20% instant reimbursement.</p>
								              				</td>
								              				
								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #c8c7c7;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Medicine Bills </b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>We`ll pay for all of your medical bills 20% instant reimbursement on prescribe medicine bills.</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #c8c7c7;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Mental Health </b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>Get one free wellness session in a year.</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #c8c7c7;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Dental Fees</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>20% instant reimbursement on dental consultation.</p>
								              				</td>

								              			</tr>
								              			<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #c8c7c7;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Virtual Doctor fees</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
								              					<p>Get free unlimited virtual doctor consultation</p>
								              				</td>

								              			</tr>
								              				<tr>
								              				<td style="padding:10px;padding:10px;border-left:1px solid #c8c7c7;
								              				   border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;background: #fff;font-size: 13px;">
								              					<p><b>Body Check-up</b></p>
								              				</td>

								              				<td style="padding:10px;border-right:1px solid #c8c7c7;border-bottom:1px solid #c8c7c7;font-size: 13px;">
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

				<br pagebreak="true"/>

			<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:lato; ">
				<tbody>
					<tr>
						<td>
						     <table  cellpadding="0" cellspacing="0" width="100%" style="background: #fff; padding:18px;">
								      <tbody>
								      	   <tr>
								              <td>
								              	<table  cellpadding="0" cellspacing="0" width="100%" style="page-break-before: always;">
								              		<tbody>
								              			<tr>
								              			  <td>
								              				<img src="https://insurmonthly.com/assets/images/care_pdf/care-logo.png" style="width: 1000px;">

								              			  </td>  
								              		
								              			</tr> 
								              		</tbody>
								              	</table>
								              	<table cellpadding="0" cellspacing="0" width="100%" style="padding-top: 20px;padding:18px;">
													  <tbody>
													    <tr valign="top">
													      <td style="width:250px;">
													        <div>
													          <p style="font-size:20px;">Certificate </p>
													          <p style="margin-top:0; margin-bottom: 0;font-size: 13px;">
													            '. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '
													          </p>
													          <p style="margin-top:0;font-size: 13px;">
													            '. (isset($fullAddress) ? $fullAddress : 'N/A') . '
													          </p>
													        </div>
													      </td>
													      <td>
													        <table cellpadding="0" cellspacing="0" width="100%" style="padding-left:18px; valign:right; float: right;">
													          <tbody style="align:right">
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px; width: 172px;">Certificate No</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000; padding: 6px; font-size: 13px;">
													                '. (isset($_GET['certificate_no']) ? $_GET['certificate_no'] : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;width: 172px;">Plan Name</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000;">
													                '. (isset($_GET['plan_name']) ? urldecode($_GET['plan_name']) : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;width: 172px;">Cover Type</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000;">
													                '. (isset($coverType) ? $coverType : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;width: 172px;">Policy Start Date</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000;">
													                '. (isset($_GET['start_date']) ? date("M d, Y", strtotime($_GET['start_date'])) : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;width: 172px;">Policy End Date</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000;">
													                '. (isset($_GET['end_date']) ? date("M d, Y", strtotime($_GET['end_date'])) : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;width: 172px;">Master Policy Holder</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000;">
													                '. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '
													              </td>
													            </tr>
													            <tr>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-bottom: 1px solid #000; width: 172px;">Master Policy Number</td>
													              <td style="border-top:1px solid #000;border-left: 1px solid #000; padding: 6px; font-size: 13px;border-right: 1px solid #000; border-bottom: 1px solid #000;">
													                '. (isset($_GET['master_policy_number']) ? $_GET['master_policy_number'] : 'N/A') . '
													              </td>
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
 								              				<td style="border:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;border-top:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4; 	">
 								              					<div style="color:#000"><b>Policy Holder</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #c4c4c4;border-right:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Date of Birth</b></div>
 								              				</td>
 								              					
 								              			</tr>
 								              			<tr>
 								              				<td style="border-left:1px solid #c4c4c4; border-right:1px solid #c4c4c4; 
 								              				border-bottom:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . '</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	border-right:1px solid #c4c4c4;">
 								              					<div style="color:#000">  '. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . '</div>
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
 								              				<td style="border:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Name</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4;border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Date of Birth</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4;
 								              					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;">
 								              					<div style="color:#000"><b>Relationship</b></div>
 								              				</td>
 								              					<td style="border-top:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4;
 								              					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Insured (since)</b></div>
 								              				</td>
 								              				
 								              			</tr>
 								              			<tr>
 								              				<td style="border-left:1px solid #c4c4c4;border-bottom:1px solid #c4c4c4;border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['first_name']) && isset($_GET['last_name']) ? $_GET['first_name'] . ' ' . $_GET['last_name'] : 'N/A') . ' </div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #c4c4c4;border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['dob']) ? date("M d, Y", strtotime($_GET['dob'])) : 'N/A') . ' </div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #c4c4c4;
 								              					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Self</div>
 								              				</td>
 								              					<td style="border-bottom:1px solid #c4c4c4;
 								              					border-right:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">'. (isset($_GET['end_date']) ? date("M d, Y", strtotime($_GET['end_date'])) : 'N/A') . '</div>
 								              				</td>
 								              				
 								              			</tr>
 								              			'. $memberTr.'
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
 								              				<td style="border:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Correspondence address</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #c4c4c4; 
 								              				border-right:1px solid #c4c4c4;
 								              				border-bottom:1px solid #c4c4c4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Care Health Insurance Limited, Vipul Tech Square, Tower C, 3rd Floor, Golf Course Road, Sector-43, Gurugram-122009 (Haryana)</div>
 								              				</td>
 								              			
 								              			</tr>
 								              				<tr>
 								              				<td style="border-left:1px solid #c4c4c4; 
 								              				border-right:1px solid #c4c4c4;
 								              				border-bottom:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>E-mail ID for Claims  
 								              				</b></div>
 								              				</td>
 								              				<td style="
 								              				border-right:1px solid #c4c4c4;
 								              				border-bottom:1px solid #c4c4c4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><a href="mailto:claims@careinsurance.com" style="color: #000; text-decoration: none;">claims@careinsurance.com </a></div>
 								              				</td>
 								              		

 								              			</tr>
 								              				<tr>
 								              				<td style="border-left:1px solid #c4c4c4; 
 								              				border-right:1px solid #c4c4c4;
 								              				border-bottom:1px solid #c4c4c4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><b>Website</b></div>
 								              				</td>
 								              				<td style="
 								              				border-right:1px solid #c4c4c4;
 								              				border-bottom:1px solid #c4c4c4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000"><a href="https://www.careinsurance.com/" style="color: #000; text-decoration: none;">www.careinsurance.com</a></div>
 								              				</td>
 								              		

 								              			</tr>
 								              		</tbody>
 								              	</table>

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
 								              				<td style="border:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #faed96;	">
 								              					<div style="color:#000"><b>S.NO</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #faed96;	">
 								              					<div style="color:#000"><b>Particulars</b></div>
 								              				</td>
 								              				<td style="border-top:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #faed96;	">
 								              					<div style="color:#000"><b>Basis of Offering</b>	</div>
 								              				</td>

 								              			</tr>

 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">1</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Sum Insured </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">'. (isset($uData['sumAssured']) ? $uData['sumAssured'] : 'N/A') . '</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">2</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">In - patient care </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">3</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Day Care Treatment</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Up to SI</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">4</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Pre-hospitalization Medical expenses</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">30 days</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">5</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Post-hospitalization Medical expenses</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">60 days</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">6</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Domestic Road Ambulance </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Up to Rs.1000 per hospitalization</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">7</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Initial Wait Period </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">15 Days, Yes (except for Injuries/Accident)</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">8</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Named Ailment (as defined in Group Care 360 Product) </div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">12 Months</div>
 								              				</td>

 								              			</tr>
 								              				<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">9</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Pre-existing diseases</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">12 Months</div>
 								              				</td>

 								              			</tr>
 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">10</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">ICU charges</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Up to 4% of Sl for up to 3 Lac SI, No Limits for 5 Lac and above Sl</div>
 								              				</td>

 								              			</tr>
 								              			<tr>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-left:1px solid #8ca3d4; padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">11</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4;
 								              				border-left:1px solid #8ca3d4;  
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;  padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">On Room Rent</div>
 								              				</td>
 								              				<td style="border-bottom:1px solid #8ca3d4; 
 								              				border-right:1px solid #8ca3d4;
 								              				border-bottom:1px solid #8ca3d4;padding:10px; font-size: 13px;background: #fff;	">
 								              					<div style="color:#000">Up to 2% of Sl for up to 3 Lac SI, Single Private Room for 5 Lac and above Si</div>
 								              				</td>

 								              			</tr>



										          </tbody>
										      </table>
										        <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              				<img src="https://insurmonthly.com/assets/images/care_pdf/bottom-bar.png" style="width: 900px; float: left;">
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
			<br pagebreak="true" />

				<table  cellpadding="0" cellspacing="0" width="900" style="margin:auto; border: 1px solid #d9d9d9; font-family:lato; ">
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
								              			  <td>
								              					<img src="https://insurmonthly.com/assets/images/care_pdf/care-logo.png" style="width: 1000px;">

								              			  </td>
								              			</tr> 
 								              		</tbody>
 								              	</table>
 								              		<br pagebreak="true"/>
 								              	   	<table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			   <p><b>Key Exclusions</b></p>
								              			   <p style="font-size:12px;">The company shall not be liable to make payment for any claim directly or indirectly caused by, based on, arising out of howsoever attributable to any of the following except covered by way of an extension:</p>
								              			   <ul>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Treatment taken from anyone who is not a Medical Practitioner or from a Medical Practitioner who is practicing outside the discipline for which he is licensed or any kind of self-medication.</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Charges incurred (or Treatment undergone) in connection with routine eye examinations and ear examinations, dentures, artificial teeth and all other similar external appliances and / or devices whether for diagnosis or treatment.</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Treatment of any external Congenital Anomaly or Illness or defects or anomalies or treatment relating to external birth defects.</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Medical treatment expenses traceable to childbirth (including complicated deliveries and caesarean sections incurred duringhospitalization)or expenses towards lawful medical termination of pregnancy during the policy period.</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Cosmetic surgery or plastic surgery or related treatment of any description, including any complication arising from these treatments, other than as may be necessitated due to an Injury, cancer or burn</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Any Illness or Injury directly or indirectly resulting or arising from or occurring during commission of any breach of any law by the Insured Member with any criminal intent</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Act of self-destruction or self-inflicted Injury, attempted suicide or suicide while sane or insane</li>
								              			   	<li style="font-size: 13px; margin-bottom: 6px;">Any Illness or Injury attributable to consumption, use, misuse or abuse of intoxicating drugs or alcohol or Tobacco (in any Form like cigarettes or Gutka etc..)or any other hallucinogens drugs.</li>
								              			   </ul>
								              			   <p style="font-size:12px;">Note: This is an illustrative list of exclusions. Please refer Group Policy Terms and Conditions at our website www.careinsurance.com or Group Policy T&C issued to the Group Master Policy Holder.</p>

								              			  </td>  
								              			 
								              			</tr> 
 								              		</tbody>
 								              	</table>


 								          
                                                                                      	

 								              	<table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			  	<p style="font-size: 13px;margin-bottom: 0px;">Consolidated Stamp Duty paide vide E-Challan GRN No. 0117751470 dated 13/06/2024. RCM Applicability - N/A</p>
								              			  	<p style="margin-bottom: 0px; margin-top:0px; font-size:13px;">SAC: 997133 and Description of Service: Accident and Health Insurance Services State </p>
								              			  	<p style="margin-bottom: 0px; margin-top:0px; font-size:13px;">GSTIN No.: 06AADCR6281N1ZW </p>
								              			  	<p style="margin-bottom: 0px; margin-top:0px; font-size:13px;">UIN :CHIHLGP25038V022425</p>
								              			  	<p style="margin-bottom: 0px; margin-top:0px; font-size:13px;">CIN: U66000DL2007PLC161503</p>
								              			  </td>
								              			 
								              			</tr> 
 								              		</tbody>
 								              	</table>
 								              	<table  cellpadding="0" cellspacing="0" width="100%" style=" margin-top:20px;">
 								              		<tbody>
 								              			<tr>
								              			  <td>
								              			  <p style="font-size: 13px;">Note:</p>
								              			  <p style="font-size:11px;">1- Validity of this certificate is subject to terms and conditions of Group Policy issued to the Group Policyholder</p>
								              			  <p style="font-size:11px;">2- In event of non-receipt of Premium, this certificate of insurance automatically stands cancelled from inception, irrespective of whether a
                                                          separate communication is sent or not. This policy is based on the information provided by the Insured to the Group Administrator. In case you find any discrepancy in the same, please contact us immediately.</p>
                                                          <p style="font-size:11px;">3- This Certificate of Insurance is governed by and is subject to the Terms and Conditions of the referred Group Policy.</p>
								              			  </td>
								              			 
								              			</tr> 
 								              		</tbody>
 								              	</table>

										        <table  cellpadding="0" cellspacing="0" width="100%" style="padding: 6px; background:#fff; margin-top:15px;">
 								              		<tbody>
 								              			<tr>
 								              				<td>
 								              				<img src="https://insurmonthly.com/assets/images/care_pdf/bottom-bar.png" style="width: 900px; float: left;">
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
 					</table>';



 					
 					// download pdf ===========================================
 					//echo $myTemplate; exit;
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
		                    'R' => 'Lato-Regular.ttf',
		                    'B' => 'Lato-Bold.ttf',
		                    'I' => 'Lato-Italic.ttf',
		                    'BI' => 'Lato-BoldItalic.ttf',
		                ],
		            ],
		            'default_font' => 'lato',
		            'tempDir' => __DIR__ . '/temp', // Ensure this directory exists
		        ]);

		        @$mpdf->WriteHTML('
		            <style>
		                body {
		                    font-family: lato;
		                }
		                h1 {
		                    font-family: lato;
		                    font-weight: bold;
		                }
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
