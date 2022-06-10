<?php
// Derniere modification le : 30/12/2020
// Par : Laurent Asselin

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
    $Username   = $argv[1];
    $Company    = $argv[2];
    $DateTime   = $argv[3].' '.$argv[4];
    $Firewall   = $argv[5];

    // Get all companies
    $GetCorporations = $db->prepare('SELECT * FROM otp_companies WHERE folder = ?');
    $GetCorporations->execute(array($Company));

    if ($GetCorporations->rowCount() == 1) {
        $CorpInfos = $GetCorporations->fetch();
        
        $updateHoure = $db->prepare('UPDATE otp_tokens SET otp_firewall = ?, otp_last_connected = ? WHERE corpid = ? AND login = ?');
        $updateHoure->execute(array($Firewall, $DateTime, $CorpInfos['corpid'], $Username));
    }


?>