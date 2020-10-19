<?php
global $wpdb;


// Table name
$tablename = $wpdb->prefix."mock_data";

// Import CSV
if(isset($_POST['importCsv'])){

  // File extension
  $extension = pathinfo($_FILES['import_csv_file']['name'], PATHINFO_EXTENSION);

  // If file extension is 'csv'
  if(!empty($_FILES['import_csv_file']['name']) && $extension == 'csv'){

    $totalInserted = 0;

    // Open file in read mode
    $csvFile = fopen($_FILES['import_csv_file']['tmp_name'], 'r');

    fgetcsv($csvFile); // Skipping header row

    // Read file
    while(($csvData = fgetcsv($csvFile)) !== FALSE){
      $csvData = array_map("utf8_encode", $csvData);

      // Row column length
      $dataLen = count($csvData);

      // Skip row if length != 9
      if( !($dataLen == 9) ) continue;

      // Assign value to variables
      $id = trim($csvData[0]);
      $title = trim($csvData[1]);
      $first_name = trim($csvData[2]);
      $last_name = trim($csvData[3]);
      $email = trim($csvData[4]);
      $tz = trim($csvData[5]);
      $date = trim($csvData[6]);
      $time = trim($csvData[7]);
      $note = trim($csvData[8]);

      // Check record already exists or not
      $cntSQL = "SELECT count(*) as count FROM {$tablename} where id='".$id."'";
      $record = $wpdb->get_results($cntSQL, OBJECT);

      if($record[0]->count==0){

        // Check if variable is empty or not
        if(!empty($id) && !empty($title) && !empty($first_name) && !empty($last_name) && !empty($email) && !empty($tz) && !empty($date)&& !empty($time) && !empty($note)) {
            //Validate emil and get address
       if(validEmail($email)){
        $domain_check = substr($email, strrpos($email, '@')+1);
        $ip_address = gethostbyname($domain_check); 
           $email_valid= $ip_address;
             
       }else{
            $email_valid= "Not Valid";
        }
            
          // Insert Record
          $wpdb->insert($tablename, array(
            'id' =>$id,
            'title' =>$title,
            'first_name' =>$first_name,
            'last_name' =>$last_name,
            'email' =>$email,
            'email_valid' =>$email_valid,
            'tz' =>$tz,
            'date' =>$date,
            'time' =>$time,
            'note' =>$note
          ));

          if($wpdb->insert_id > 0){
            $totalInserted++;
          }
        }
        
      }

    }
    echo "<h3 style='color: green;'>Transaction Succesfully Imported</h3>";


  }else{
    echo "<h3 style='color: red;'>Error: Data was not Imported(check file must be .csv)</h3>";
  }
  
}
?>

<h1>Import CSV Files</h1>
<!-- Form -->
<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
  <input type="file" name="import_csv_file" >
  <input type="submit" name="importCsv" value="Import">
</form>

<!-- Contact Card -->
<table width='100%' border='1' style='border-collapse: collapse;'>
   <thead>
   <tr>
     <th>First Name</th>
     <th>Last Name</th>
     <th>Email</th>
     <th>Email(Valid)</th>
   </tr>
   </thead>
   <tbody>
 <?php
   // Fetch records
   $transactionList = $wpdb->get_results("SELECT * FROM ".$tablename." order by id asc limit 500");
   if(count($transactionList) > 0){
     $count = 0;
     foreach($transactionList as $transaction){
        $first_name = $transaction->first_name;
        $last_name = $transaction->last_name;
        $email = $transaction->email;
        $email_valid = $transaction->email_valid;

         echo "<tr>
           <td>".$first_name."</td>
        <td>".$last_name."</td>
        <td>".$email."</td>
        <td>".$email_valid."</td>
         </tr>
         ";
      }
    }else{
     echo "<tr><td colspan='9'>No data found</td></tr>";
  }
//validate email
function validEmail($email){
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex)
    {
       $isValid = false;
    }
    else
    {
       $domain = substr($email, $atIndex+1);
       $local = substr($email, 0, $atIndex);
       $localLen = strlen($local);
       $domainLen = strlen($domain);
       if ($localLen < 1 || $localLen > 64)
       {
          // local part length exceeded
          $isValid = false;
       }
       else if ($domainLen < 1 || $domainLen > 255)
       {
          // domain part length exceeded
          $isValid = false;
       }
       else if ($local[0] == '.' || $local[$localLen-1] == '.')
       {
          // local part starts or ends with '.'
          $isValid = false;
       }
       else if (preg_match('/\\.\\./', $local))
       {
          // local part has two consecutive dots
          $isValid = false;
       }
       else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
       {
          // character not valid in domain part
          $isValid = false;
       }
       else if (preg_match('/\\.\\./', $domain))
       {
          // domain part has two consecutive dots
          $isValid = false;
       }
       else if
     (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
         {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                 $isValid = false;
          }
                 }
                 if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
                 {
                     // domain not found in DNS

                     $isValid = false;
                 }
             }
             return $isValid;
             }
           
  ?>
  </tbody>
</table>