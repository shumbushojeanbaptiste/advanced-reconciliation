
<?php

require '../include/config.php';

$host = $host;
$db = $db_names;
$user = $db_username;
$pass = $db_password;

$backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = "mysqldump --user=$user --password=$pass --host=$host $db > $backupFile";

system($command);

// Optional: Delete old backups
$files = glob('backup_*.sql');
if(count($files) > 7){
    array_map('unlink', array_slice($files, 0, count($files) - 7));
}
?>

