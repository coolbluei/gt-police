<?PHP
header('Content-Disposition: attachment; filename="crimelog.csv"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
readfile("http://reports.police.gatech.edu/public/crimelogcsv.asp");
flush();
?>