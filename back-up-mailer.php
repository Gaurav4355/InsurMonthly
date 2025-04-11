<?php if(!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * This function is used to print the content of any data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * This function used to get the CI instance
 */
if(!function_exists('get_instance'))
{
    function get_instance()
    {
        $CI = &get_instance();
    }
}


/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if(!function_exists('getHashedPassword'))
{
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if(!function_exists('verifyHashedPassword'))
{
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}

/**
 * This method used to get current browser agent
 */
if(!function_exists('getBrowserAgent'))
{
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        $agent = '';

        if ($CI->agent->is_browser())
        {
            $agent = $CI->agent->browser().' '.$CI->agent->version();
        }
        else if ($CI->agent->is_robot())
        {
            $agent = $CI->agent->robot();
        }
        else if ($CI->agent->is_mobile())
        {
            $agent = $CI->agent->mobile();
        }
        else
        {
            $agent = 'Unidentified User Agent';
        }

        return $agent;
    }
}

if(!function_exists('setProtocol'))
{
    function setProtocol()
    {
        $CI = &get_instance();
                    
        $CI->load->library('email');
        
        $config['protocol'] = PROTOCOL;
        $config['mailpath'] = MAIL_PATH;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $CI->email->initialize($config);
        
        return $CI;
    }
}

if(!function_exists('emailConfig'))
{
    function emailConfig()
    {
        $CI = &get_instance();
        $CI->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailpath'] = MAIL_PATH;
        $config['charset'] = 'UTF-8';
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
    }
}

if(!function_exists('resetPasswordEmail'))
{
    function resetPasswordEmail($detail)
    {
        $data["data"] = $detail;
        // pre($detail);
        // die;
        
        $CI = setProtocol();        
        
        $CI->email->from(EMAIL_FROM, FROM_NAME);
        $CI->email->subject("Reset Password");
        $CI->email->message($CI->load->view('email/resetPassword', $data, TRUE));
        $CI->email->to($detail["email"]);
        $status = $CI->email->send();
        
        return $status;
    }
}

if(!function_exists('setFlashData'))
{
    function setFlashData($status, $flashMsg)
    {
        $CI = get_instance();
        $CI->session->set_flashdata($status, $flashMsg);
    }
}


if(!function_exists('text2url'))
{
    function text2url($text)
    {
        $url = str_replace('[', '-', $text);
        $url = str_replace(']', '-', $url);
        $url = str_replace('{', '-', $url);
        $url = str_replace('}', '-', $url);
        $url = str_replace('(', '-', $url);
        $url = str_replace(')', '-', $url);
        $url = str_replace('||', '-', $url);
        $url = str_replace('|', '-', $url);
        $url = str_replace('&', '-', $url);
        $url = str_replace('+', '-', $url);
        $url = str_replace(' ', '-', strtolower($url));
        $url = str_replace('–', '-', strtolower($url));
        $url = str_replace('--', '-', strtolower($url));
        $url = str_replace('--', '-', strtolower($url));
        $url = str_replace('--', '-', strtolower($url));
        $url = str_replace('--', '-', strtolower($url));
        $url = substr_replace($url ,"",-1);
        return $url;
    }
}


// CUrl

function callcurl($data) {
    $ch = curl_init();
    if(isset($data['apikey'])){
      $key = $data['apikey'];
      unset($data['apikey']);
    }  
    $url = $data['url'];
    unset($data['url']);

    //$headers = array('Authorization: Bearer '.$stripeData['secret_key']);
    
    curl_setopt($ch, CURLOPT_URL,$url);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    return $result;

   }

// curlUrl
    function curl_url($path = NULL){
       return 'https://movie.24chat.org/getapidata/'.$path;
    }   
// check is encoded
function is_base64_encoded($data)
{
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
       return TRUE;
    } else {
       return FALSE;
    }
};

// check is encoded
function trimm($data)
{
    $data = str_replace('href="', "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace('https://veryfastdownload.pw/watch.php?link=', "", $data);
    $data = str_replace('https://veryfasturl.club/watch.php?link=', "", $data);
    
    $data = str_replace('https://gpshares.com/r/download.php?id=', "", $data);
    $data = str_replace('https://gpshare.xyz/download.php?id=', "", $data);
    $data = str_replace('href="https://gpshares.com/r/download.php?id=', "", $data);
    
    

    return trim($data);
};

function get_whishlist($user_id, $product_id){
   $op = & get_instance();
   
   $op->db->select('*');
   $op->db->where(array('user_id' =>$user_id, 'product_id' =>$product_id ));
   $op->db->from('tbl_wishlist');
   $res = $op->db->get();
   $result =  $res->row();
   if (!empty($result)) {
       return true;
   }else{
    return false;
   }
   // echo $result->$para;
}
function get_cart_by_user_id($user_id, $product_id){
   $op = & get_instance();
   
   $op->db->select('*');
   $op->db->where(array('user_id' =>$user_id, 'product_id' =>$product_id ));
   $op->db->from('tbl_cart');
   $res = $op->db->get();
   $result =  $res->row();
   if (!empty($result)) {
       return true;
   }else{
    return false;
   }
   // echo $result->$para;
}
function get_cart_by_temp_id($temp_id, $product_id){
   $op = & get_instance();
   
   $op->db->select('*');
   $op->db->where(array('temp_id' =>$temp_id, 'product_id' =>$product_id ));
   $op->db->from('tbl_cart');
   $res = $op->db->get();
   $result =  $res->row();
   if (!empty($result)) {
       return true;
   }else{
    return false;
   }
   // echo $result->$para;
}

function get_size($id){
   $op = & get_instance();
   
   $op->db->select('*');
   $op->db->where(array('id' =>$id ));
   $op->db->from('size');
   $res = $op->db->get();
   $result =  $res->row();
   if (!empty($result)) {
       return $result->name;
   }else{
    return NULL;
   }
   // echo $result->$para;
}

function country_list(){
   $op = & get_instance();
   
   $op->db->select('*');
   // $op->db->where(array('id' =>$id ));
   $op->db->from('z_countries');
   $res = $op->db->get();
   $result =  $res->result();
   return $result;
}
function state_list($country_id){
   $op = & get_instance();
   $op->db->select('*');
   $op->db->where(array('country_id' =>$country_id ));
   $op->db->from('z_states');
   $res = $op->db->get();
   $result =  $res->result();
   return $result;
}


function get_invoicpdf($paymnetdata){
    
         // pre($paym/netdata);
      
    return  $invoiceTemplate = '
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px;  margin:auto;">
    <tbody>
  <tr>
  <td style="padding: 23px 0px;background: white;box-shadow: 0px 0px 3px 0px #bcc3bf;">
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px;     padding: 0px 40px 10px 40px; margin:auto;">
 <tbody>
  <tr>
    <td style="width: 300px;">
     <div class="logo"> 
      <img src="https://trufedu.com/wp-content/uploads/2022/05/TRU_LOGO345163413.png" style="width: 170px;">
     </div>
    </td>

      <td style="font-family: system-ui;text-align: right;">
    <table>
    <tr>
      <td style="margin-bottom: 15px; font-size: 20px; color: #e07a5f;text-align: right;">Trufedu Budgetary Education Private Limited
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;line-height: 1.4;font-size: 14px;text-align: right;">Block L, Embassy Tech Village, Devarabisanahalli, Outer Ring Road, Bellandur, Bengaluru Ka - 560103, India
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;"><a href="https://trufedu.com/" >www.trufedu.com</a>&nbsp;&nbsp;
            <a href="mailto:help@trufedu.com" >help@trufedu.com</a>
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;"><strong>GSTIN: 07AAHCT5709R1ZL</strong>
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;">PAN: AAHCT5709R, CIN: U74999DL2019PTC356633
      </td>
    </tr>
    </table>
        
    </td>
   </tr>
  </tbody>
 </table>
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px; padding: 0px 40px; margin:auto;">
 <tbody>
   <tr>
    <td style="width: 33%;">
       <p style="height: 1px; background-color:#b3b3b3; color: #b3b3b3; width: 250px;"></p>
    </td>
        <td style="text-align: center; font-family: system-ui;  font-size: 20px;">
        <p>Tax Invoice</p>
    </td>
        <td style="width: 33%;">
        <p style="height: 1px; background-color:#b3b3b3; color: #000; width: 250px;"></p>
    </td>
   </tr>

    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px; padding: 0px 40px; margin:auto;">
     <tbody>
      <tr>
            <td style="width:360px;">
           <table style="width: 99%; font-family: system-ui; font-size: 15px; height: 120px;     border: 2px solid #e07a5f;">
            <tr>
                 <td colspan="2" style="text-align: left;">Bill To</td>
 
            </tr> 
            <tr>
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$paymnetdata['duepaydata']->name.'</td>
            </tr> 
            <tr>     
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$paymnetdata['userData']->address.'.</td>
            </tr>   
             <tr>
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$paymnetdata['userData']->phone.'</td>
            </tr> 

            <tr>
                 <td colspan="2" style="text-align: left;"><a href="'.$paymnetdata['userData']->email.'">'.$paymnetdata['userData']->email.'</a></td>
            </tr> 
            '.$paymnetdata['userData']->gstNumber.' 
           </table>
            </td>

            <!-- end -->
    
        <!-- new table start -->
           <td style="width:360px; border:2px solid #e07a5f;padding: 8px;">
           <table style="width: 99%; font-family: system-ui; font-size: 15px;">
            <tr>
                 <td colspan="2" style="text-align: left;">Invoice No:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$paymnetdata['paymentlist']->invoice_id.'</td>
            </tr> 
            <tr>
                 <td colspan="2" style="text-align: left;">Invoice Date:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$paymnetdata['paymentlist']->dateAt.'</td>
            </tr> 

            <tr>
                 <td colspan="2" style="text-align: left;">Client ID:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$paymnetdata['paymentlist']->userId.'</td>
            </tr> 
           </table>
            </td>

            <!-- end -->
      </tr>

    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 100%; padding: 0px 40px; margin:auto; padding-top:20px;">
     <tbody style="margin-top:20px;">
       <tr>
        <th style="background-color: #e07a5f; font-family: system-ui; font-weight:300; text-align: left; padding:10px; color:#fff;">Particulars </th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:right; padding:10px; color:#fff;">Amount (INR)</th>
       </tr>

       <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;">'.$paymnetdata['paymentlist']->planName.'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f;">'.$paymnetdata['duepaydata']->amount.'</td>
      </tr>
      <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353; border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;">IGST (18%)</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui;border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f">'.$paymnetdata['duepaydata']->gst.'</td>
      </tr>
      <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f; color: #e07a5f; font-weight:500;">Invoice Amount </td>
       <td style="padding: 10px; text-align: right;font-family: system-ui;border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; color: #e07a5f; font-weight:500;">'.$paymnetdata['duepaydata']->total.'</td>
      </tr>
      <tr style="padding-top: 10px;">
        <td style="padding-top: 20px; "><span style=" font-family: system-ui;color: #535353;">Amount in Words:</span>&nbsp;
            <span style=" font-family: system-ui;color: #000; font-weight: 600; font-size:17px"></span>
        </td>
      </tr>
    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 100%; padding: 0px 40px; margin:auto; padding-top:20px;">
     <tbody style="padding-top:20px;">
       <tr>
        <th style="background-color: #e07a5f; font-family: system-ui; font-weight:300; text-align: center; padding:10px; color:#fff;">Payment Date </th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Mode</th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Reference ID</th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Amount (INR)</th>
       </tr>
       <tr>
       <td style="padding: 10px;font-family: system-ui; color:#000;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;border-right: 2px solid #e07a5f; text-align: center;">'.$paymnetdata['duepaydata']->dateAt.'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$paymnetdata['duepaydata']->payment_mode.'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$paymnetdata['duepaydata']->payment_from.'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$paymnetdata['duepaydata']->total.'</td>
      </tr>
    </tbody>
   </table>

   <!-- footer Con -->
   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="padding: 0px 40px; margin-top:30px;">
     <tbody>
      <tr>
          <td colspan="1" style="text-align: left;font-size:18px;font-weight:800;width:49%;">Important Note</td>
          <td colspan="1" style="text-align: right;font-size:17px;font-weight:800;width:49%; padding-left:40px">Trufedu Budgetary Education Private Limited</td>
      </tr>
      <tr>
          <tr>   
              <td colspan="1" style="text-align: left;font-family: system-ui;">
              <p style="font-size:14px;text-align: justify font-family: system-ui;">Please note that cancellation of your subscription is not available at Trufedu Budgetary Education Private Limited. We do not offer refunds of any value at any stage according to our refund policy, our policy equally to each member.</p><br/>
              <a href="mailto:help@trufedu.com">help@trufedu.com</a>
              </td>
              <td colspan="1" style="text-align: right;width:49%;"><img src="https://trufedu.com/wp-content/uploads/2022/07/signtre.png" width="110px" /></td>
          </tr>  
          
      </tr>

    </tbody>
   </table>
   <!--// footer Con -->
</td>
</tr>
</tbody>
</table>
</body>
</html>

';

  }


  function invoiceTemplate($data){
    $invoiceTotalAmountInWord = ucwords(convert_number_to_words($data['total_amount'])).' Only';
         // pre($paym/netdata);
      
    return  $invoiceTemplate = '
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px;  margin:auto;">
    <tbody>
  <tr>
  <td style="padding: 23px 0px;background: white;box-shadow: 0px 0px 3px 0px #bcc3bf;">
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px;     padding: 0px 40px 10px 40px; margin:auto;">
 <tbody>
  <tr>
    <td style="width: 300px;">
     <div class="logo"> 
      <img src="http://insurmonthly.com/assets/images/logo-Insur-MOnthly.png" style="width: 170px;">
     </div>
    </td>

      <td style="font-family: system-ui;text-align: right;">
    <table>
    <tr>
      <td style="margin-bottom: 15px; font-size: 20px; color: #e07a5f;text-align: right;">Trufedu Budgetary Education Private Limited
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;line-height: 1.4;font-size: 14px;text-align: right;">Block L, Embassy Tech Village, Devarabisanahalli, Outer Ring Road, Bellandur, Bengaluru Ka - 560103, India
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;"><a href="https://trufedu.com/" >www.trufedu.com</a>&nbsp;&nbsp;
            <a href="mailto:help@trufedu.com" >help@trufedu.com</a>
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;"><strong>GSTIN: 07AAHCT5709R1ZL</strong>
      </td>
    </tr>
    <tr>
      <td style="margin-bottom: 10px;text-align: right;">PAN: AAHCT5709R, CIN: U74999DL2019PTC356633
      </td>
    </tr>
    </table>
        
    </td>
   </tr>
  </tbody>
 </table>
<table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px; padding: 0px 40px; margin:auto;">
 <tbody>
   <tr>
    <td style="width: 33%;">
       <p style="height: 1px; background-color:#b3b3b3; color: #b3b3b3; width: 250px;"></p>
    </td>
        <td style="text-align: center; font-family: system-ui;  font-size: 20px;">
        <p>Tax Invoice</p>
    </td>
        <td style="width: 33%;">
        <p style="height: 1px; background-color:#b3b3b3; color: #000; width: 250px;"></p>
    </td>
   </tr>

    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 800px; padding: 0px 40px; margin:auto;">
     <tbody>
      <tr>
            <td style="width:360px;">
           <table style="width: 99%; font-family: system-ui; font-size: 15px; height: 120px;     border: 2px solid #e07a5f;">
            <tr>
                 <td colspan="2" style="text-align: left;">Bill To</td>
 
            </tr> 
            <tr>
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$data['company'].'</td>
            </tr> 
            <tr>     
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$data['address'].'.</td>
            </tr>   
             <tr>
                 <td colspan="2" style="text-align: left; color: #e07a5f;">'.$data['phone'].'</td>
            </tr> 

            <tr>
                 <td colspan="2" style="text-align: left;"><a href="to:'.$data['email'].'">'.$data['email'].'</a></td>
            </tr> 
            '.$data['gst_number'].' 
           </table>
            </td>

            <!-- end -->
    
        <!-- new table start -->
           <td style="width:360px; border:2px solid #e07a5f;padding: 8px;">
           <table style="width: 99%; font-family: system-ui; font-size: 15px;">
            <tr>
                 <td colspan="2" style="text-align: left;">Invoice No:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$data['invoice_id'].'</td>
            </tr> 
            <tr>
                 <td colspan="2" style="text-align: left;">Invoice Date:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$data['invoice_date'].'</td>
            </tr> 

            <tr>
                 <td colspan="2" style="text-align: left;">Client ID:</td>
                 <td colspan="2" style="text-align: right; float: right;">'.$data['user_id'].'</td>
            </tr> 
           </table>
            </td>

            <!-- end -->
      </tr>

    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 100%; padding: 0px 40px; margin:auto; padding-top:20px;">
     <tbody style="margin-top:20px;">
       <tr>
        <th style="background-color: #e07a5f; font-family: system-ui; font-weight:300; text-align: left; padding:10px; color:#fff;">Particulars </th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:right; padding:10px; color:#fff;">Amount (INR)</th>
       </tr>

       <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;">'.$data['plan_name'].'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f;">'.$data['amount'].'</td>
      </tr>
      <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353; border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;">IGST (18%)</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui;border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f">'.$data['gst_price'].'</td>
      </tr>
      <tr>
       <td style="padding: 10px;font-family: system-ui; color:#535353;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f; color: #e07a5f; font-weight:500;">Invoice Amount </td>
       <td style="padding: 10px; text-align: right;font-family: system-ui;border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; color: #e07a5f; font-weight:500;">'.$data['total_amount'].'</td>
      </tr>
      <tr style="padding-top: 10px;">
        <td style="padding-top: 20px; "><span style=" font-family: system-ui;color: #535353;">Amount in Words:</span>&nbsp;
            <span style=" font-family: system-ui;color: #000; font-weight: 600; font-size:17px">'.$invoiceTotalAmountInWord.'</span>
        </td>
      </tr>
    </tbody>
   </table>

   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="width: 100%; padding: 0px 40px; margin:auto; padding-top:20px;">
     <tbody style="padding-top:20px;">
       <tr>
        <th style="background-color: #e07a5f; font-family: system-ui; font-weight:300; text-align: center; padding:10px; color:#fff;">Payment Date </th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Mode</th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Reference ID</th>
        <th style="background-color: #e07a5f;  font-family: system-ui; font-weight:300; text-align:center; padding:10px; color:#fff;">Payment Amount (INR)</th>
       </tr>
       <tr>
       <td style="padding: 10px;font-family: system-ui; color:#000;border-bottom: 2px solid #e07a5f; border-left: 2px solid #e07a5f;border-right: 2px solid #e07a5f; text-align: center;">'.$data['payment_date'].'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$data['payment_mode'].'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$data['payment_form'].'</td>
       <td style="padding: 10px; text-align: right;font-family: system-ui; border-bottom: 2px solid #e07a5f;
       border-right: 2px solid #e07a5f; text-align: center;">'.$data['total_amount'].'</td>
      </tr>
    </tbody>
   </table>

   <!-- footer Con -->
   <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0"  style="padding: 0px 40px; margin-top:30px;">
     <tbody>
      <tr>
          <td colspan="1" style="text-align: left;font-size:18px;font-weight:800;width:49%;">Important Note</td>
          <td colspan="1" style="text-align: right;font-size:17px;font-weight:800;width:49%; padding-left:40px">Trufedu Budgetary Education Private Limited</td>
      </tr>
      <tr>
          <tr>   
              <td colspan="1" style="text-align: left;font-family: system-ui;">
              <p style="font-size:14px;text-align: justify font-family: system-ui;">Please note that cancellation of your subscription is not available at Trufedu Budgetary Education Private Limited. We do not offer refunds of any value at any stage according to our refund policy, our policy equally to each member.</p><br/>
              <a href="mailto:help@trufedu.com">help@trufedu.com</a>
              </td>
              <td colspan="1" style="text-align: right;width:49%;"><img src="https://trufedu.com/wp-content/uploads/2022/07/signtre.png" width="110px" /></td>
          </tr>  
          
      </tr>

    </tbody>
   </table>
   <!--// footer Con -->
</td>
</tr>
</tbody>
</table>
</body>
</html>

';

  }

/*------------- Insure Template ---------------*/
function insureInvoiceTemplate($data){
    $invoiceTotalAmountInWord = ucwords(convert_number_to_words($data['total_amount'])).' Only';
         // pre($paym/netdata);
    return  $invoiceTemplate = '
<table width="100%">
   <tbody>
      <tr>
         <td align="center" width="800">
            <table width="800"   style="border:1px solid rgb(192 192 192);padding-left:30px; padding-right:30px;margin:0px; padding-top:30px; padding-bottom: 30px;">
               <tbody>
                  <tr>
                     <td>
                        <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px; " width="100%">
                           <tbody>
                              <tr>
                                 <td align="left">
                                    <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px;  " width="100%">
                                       <tbody>
                                          <tr align="left">
                                              <td style="justify-content: start; display: grid;">
                                                <p style=" font-family: Montserrat, sans-serif; font-size: 20px; font-weight: 700; margin-bottom:0;">TAX INVOICE</p>
                                                 <p style=" font-family: Montserrat, sans-serif; font-size:11px; color: #666666; font-weight: 600; margin-top:0">Invoice #: '.$data['invoice_id'].'</p>
                                               </td>
                                          </tr>
                                       </tbody>  
                                       </table>                             
                                   </td>
                                  <td>
                                    <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px;" width="100%">
                                       <tbody>
                                          <tr align="right" style="justify-content: end;">
                                              <td  align="right">
                                                <img src="http://insurmonthly.com/assets/images/logo-Insur-MOnthly.png" style="width:100px; margin-bottom: 20px;"> </td>
                                           </tr>
                                          </tbody>    
                                        </table>                           
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>

                              <!-- Secod Row -->


                        <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px; ; " width="100%">
                           <tbody>
                              <tr valign="top">
                                 <td align="top">
                                    <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px;  " width="300">
                                       <tbody>
                                          <tr align="left">
                                              <td style="justify-content: start;">
                                                <td  style="">
                                                <p style="  margin-top: 0px;margin-bottom:0px; text-align:left; font-family: Montserrat, sans-serif; font-size:15px; color: #666666;">Balance Due</p>
                                                <p style=" margin-bottom:0px; font-family: Montserrat, sans-serif; font-weight: 700; font-size: 25px; margin-top: 8px; "> ₹'.$data['total_amount'].'</p>
                                             </td>
                                              </td>
                                            </tr>
                                         </tbody>  
                                       </table>                             
                                   </td>

                                  <td>
                                    <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px;" width="500">
                                       <tbody>
                                          <tr align="right" style="justify-content: end;">
                                               
                                          <td  align="right" style="margin-top: 50px;">
                                        
                                                <p style="font-family: Montserrat, sans-serif; color:#e07a5f; font-weight: 700; font-size: 18px; margin-bottom: 0;">Trufedu Budgetary Education Private Limited</p>
                                                <p style="font-family: Montserrat, sans-serif; color: #666666; font-size: 15px; margin-top: 0;">The Executive Centre, Level 3B,DLF Centre,
                                                Sansad Marg, Connaught Place
                                                New Delhi Central Delhi 110001, India
                                                </p>
                                                <p style="font-family: Montserrat, sans-serif; color: #666666; font-size: 15px;">GSTIN: 07AAHCT5709R1ZL</p>
                                                <p style="font-family: Montserrat, sans-serif; color: #666666; font-size: 15px;">CIN: U74999DL2019PTC356633</p>
                         
                                          </td>
                                           </tr>
                                          </tbody>    
                                        </table>                           
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>


                              <!-- third-row -->

               <table cellpadding="0" cellspacing="0" style="padding:0px;margin-top:40px;" width="100%">
                           <tbody>
                              <tr valign="top">
                                 <td align="left">
                                    <table cellpadding="0" cellspacing="0" style="padding:0px;margin:0px;  " width="200">
                                       <tbody>
                                          <tr>
                                             <td  align="left" valign="top">Invoice Date:
                                                
                                          
                                              </td>
                                            </tr>
                                         </tbody>  
                                       </table>                             
                                   </td>

                                  <td>
                                  <table cellpadding="0" cellspacing="0" width="200">
                                       <tbody>
                                       <tr >
                                          <td  align="left" Valign="top"style="">
                                        '.$data['invoice_date'].'</td>
                                             
                                     
                                           </tr>
                                          </tbody>    
                                        </table>                        
                                       </td>

                                        <td>
                                  <table cellpadding="0" cellspacing="0" width="400">
                                       <tbody>
                                       <tr >
                                          <td  align="left" valign="top">
                                                  <p style="color:margin-bottom:0px; #666666;">Bill To</p>
                                                   <p style="color:margin-bottom:0px; #000;">'.$data['company'].'</p>
                                                   <p style="color:margin-bottom:0px; #666666;">'.$data['address'].'</p>
                                                   <p style="color:margin-bottom:0px; #666666;">'.$data['phone'].'</p>
                                                   <p style="color:margin-bottom:0px; #666666;">'.$data['email'].'</p>
                                               </td>
                                           </tr>
                                          </tbody>    
                                        </table>                        
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>



                        <table cellpadding="0"  cellspacing="0" style="padding:0px; margin-top:40px;" width= "100%">
                           <tbody>
                              <tr align="left">
                                 <td style="font-family: Montserrat, sans-serif; font-size:14px;   ">Place Of Supply: Delhi(07)</td>
                              </tr>
                           </tbody>
                        </table>


                        <table cellpadding="0"  cellspacing="0" style="padding:0px;margin:0px;  margin-top:30px;" width= "100%"  >
                           <tbody>
                              <tr align="left" bgcolor="#e07a5f" style=" height: 50px; text-align: left; color: #fff; font-family: Montserrat, sans-serif;">
                                 <td style=" text-align:left; height:40px; padding-left: 15px; color: #ffffff;">#</td>
                                 <td style="text-align:left;  height:40px; color: #ffffff;">Item & Description</td>
                                 <td style="text-align:left;  height:40px; width: 150px;color: #ffffff;">HSN/SAC</td>
                                 <td style=" text-align:left; height:40px; color: #ffffff;">Rate</td>
                                 <td style=" text-align:left; height:40px; color: #ffffff;">IGST</td>
                                 <td style=" text-align:left; height:40px; color: #ffffff;">Amount</td>
                              </tr>
                              <tr align="left" style="background: #fff;     height: 50px; text-align: left; color: #000;  font-family: Montserrat, sans-serif;">
                                 <td style="border-bottom:1px solid rgb(186 186 186);font-family: Montserrat, sans-serif; padding-left: 15px; padding-top: 10px;    font-size: 14px;">1</td>
                                 <td style="border-bottom:1px solid rgb(186 186 186); padding-top: 10px;    font-size: 14px;"> '.$data['plan_name'].' </td>
                                 <td style=" border-bottom:1px solid rgb(186 186 186);padding-top: 10px;    font-size: 14px;">'.$data['planId'].'</td>
                                 <td style=" border-bottom:1px solid rgb(186 186 186);padding-top: 10px;    font-size: 14px;">'.$data['amount'].'</td>
                                 <td style=" border-bottom:1px solid rgb(186 186 186);padding-top: 10px;">
                                    <span style="font-size: 14px; ">'.$data['gst_price'].'</span>
                                    <span style="margin-bottom: 10px; font-size: 14px; color:#666666">18%</span>
                                 </td>
                                 <td style="  border-bottom:1px solid rgb(186 186 186);  font-size: 14px; padding-top: 10px;">'.$data['total_amount'].'</td>
                              </tr>
                              <tr  style=" color: #000; font-family: Montserrat, sans-serif; margin-top: 30px; ">
                                 <td colspan="3"></td>
                                 <td colspan="2" align="left">
                                    <p style="margin-bottom: 0px; padding-top: 10px;font-size:14px;">Sub Total</p>
                                    <p style="margin-bottom: 20px; font-size:14px;">IGST18 (18%)</p>
                                 </td>
                                 <td align="right">
                                    <p style="padding-top: 10px;margin-bottom: 0px;font-size:14px;">'.$data['amount'].'</p>
                                    <p style="margin-bottom: 20px; font-size:14px;">'.$data['gst_price'].'</p>
                                 </td>
                              </tr>
                              <tr  valign="" style=" color: #000; font-family: Montserrat, sans-serif; margin-top: 30px; ">
                                 <td colspan="3"></td>
                                 <td bgcolor="#ffece9"  colspan="2" align="left" style="background: rgb(255 236 233); font-size: 14px; padding: 14px;">Total</td>
                                 <td bgcolor="#ffece9"  colspan=""  align="right" style="background: rgb(255 236 233); font-size:14px;">'.$data['total_amount'].'</td>
                              </tr>
                              <tr valign="top" style=" color: #000; font-family: Montserrat, sans-serif; margin-top: 30px; ">
                                 <td colspan="3"></td>
                                 <td colspan="1"  style="font-size: 14px; padding: 14px; color: #666666;">Total In Words:</td>
                                 <td colspan="" style="position: absolute;font-size: 11px;overflow: hidden;width: 190px;margin-top: 13px;font-weight: 600;margin-left: -39px;">Indian Rupee - '.$invoiceTotalAmountInWord.' Only</td>
                              </tr>
                           </tbody>
                        </table>
                        <table cellpadding="0"  cellspacing="0" style="padding:0px;margin:0px;  margin-top:100px;" width= "100%">
                           <tbody>
                              <tr valign="left">
                                 <td style="">
                                    <p style="color: #666666; font-family: Montserrat, sans-serif; ">Notes</p>  
                                    <p style=" margin-top:8px;font-family: Montserrat, sans-serif;">Thank you!</p>
                                 </td>
                              </tr>
                              <tr >
                                 <td >
                                    <p style="margin-top:40; margin-bottom:0px;color: #666666; font-family: Montserrat, sans-serif;">Terms & Conditions</p>
                                    <p style="margin-top:8px; margin-bottom:0px; font-family: Montserrat, sans-serif; font-size:10px;">1. The monthly subscription payment is on a recurring basis.</p>
                                    <p style="margin-top:0; margin-bottom:0px; font-family: Montserrat, sans-serif; font-size:10px;">2. The due date for the payment each month will be intimated to you via SMS, email, or WhatsApp. If the payment fails on the due date, your subscription will be
                                       deactivated and benefits will be stopped.
                                    </p>
                                    <p style="margin-top:0; margin-bottom:0px; font-family: Montserrat, sans-serif; font-size:10px;">3. Benefits can be availed by the customer only if KYC is complete, and the Subscription docket is signed. Any bills/orders received before docket signatures are not
                                       eligible under the subscription.
                                    </p>
                                    <p style="margin-top:0; margin-bottom:0px; font-family: Montserrat, sans-serif; font-size:10px;">4. Please note that cancellation of your subscription is not available at InsurMonthly. We do not offer any refunds of any value at any stage according to our refund
                                       policy, our policy equally to each member.
                                    </p>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <table cellpadding="0"  cellspacing="0" style="padding:0px;margin-top:20px; margin-bottom:20px;  " width= "100%">
                           <tbody>
                              <tr>
                                 <td>
                                    <span style="color: #666666; font-family: Montserrat, sans-serif; ">
                                    <img src="https://trufedu.com/wp-content/uploads/2022/07/signtre.png" style="    width: 100px;">
                                    </span> 
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <table cellpadding="0"  cellspacing="0" style="padding:0px;" width= "100%">
                           <tbody>
                              <tr>
                                 <td style="border-top: 1px solid rgb(196 196 196);text-align: center; font-family: Montserrat, sans-serif;font-size: 10px;">
                                    <p style="margin-top: 15px;">Please note that Insur Monthly is not a broker or an insurance intermediary. We are a comprehensive Health & Wellness Membership platform 2023 Trufedu Budgetary Education Private Limited. All Rights Reserved</p>
                                    <p style="font-size: 10px;">Insur Monthly Is A Trademark Of Trufedu Budgetary Education Private Limited</p>
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

  }

?>