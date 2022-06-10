 <?php
 try {
    $dbexer_dbname 		= "exer_db";
    $dbexer_host 		= "127.0.0.1";
    $dbexer_username 	= "admin_exer";
    $dbexer_password 	= trim(shell_exec('cat /etc/mysql/otp.conf'));

    $db = new PDO("mysql:host=$dbexer_host;dbname=$dbexer_dbname;charset=utf8", $dbexer_username, $dbexer_password);
} catch (Exception $exception) {
    die('Erreur(s) rencontrée(s) : ' . $exception ->getCode() . ' ' . $exception->getMessage() . ').');
}

    // Prepose output
  $Timestamp  = time();
    $Date_time   = $argv[1].' '.$argv[2];
    $Username   = $argv[3];
    $Company    = $argv[4];
    $Firewall   = $argv[5];
        
        $updateHoure = $db->prepare('INSERT INTO otp_stats (`Date_hour`, `Vm_name`, `corpid`, `company`, `Firewall`, `Login`, `Result`) VALUES (?,?,?,?,?,?,?)');
        $updateHoure->execute(array($Date_time,$Username ,"123456", "company", $Firewall, $Company, "Result"));

    
?>