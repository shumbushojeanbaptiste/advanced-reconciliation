<?php
require '../include/config.php';
$host = $host;
$db = $db_names;
$user = $db_username;
$pass = $db_password;

// Backup file
$backupFile = 'tbl_payment_and_tbl_pay_bill' . date('Y-m-d_H-i-s') . '.sql';

// Tables to dump
$tables = "tbl_payment tbl_pay_bill";

// Run mysqldump ONLY for these two tables
$command = "mysqldump --user=$user --password=$pass --host=$host $db $tables > $backupFile";

system($command);

// Optional: Delete old backups
$files = glob('backup_selected_*.sql');
if(count($files) > 7){
    array_map('unlink', array_slice($files, 0, count($files) - 7));
}

?>
