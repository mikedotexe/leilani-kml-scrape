<?php
set_time_limit(0);

$debug_i = 0;
if (($handle = fopen("aloha-honua.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
    $debug_i++;
    // Skip header row of csv
    if ($debug_i == 1) continue;

    $tmk = $data[1];
    $url = $data[2];

    // since it hung up, see if we already have the tmk file. if so, continue
    if (file_exists('parcels-tmks/' . $tmk . '.kml')) {
      echo('already have that one, soldier on');
      continue;
    }

    // open and load url for 3 seconds using Selenium
    $command = escapeshellcmd('python keepgrabbing.py ' . $url);
    $kml_link = shell_exec($command);
    echo('getting contents of: ' . $kml_link);

    // can't do this actually. maybe because https://github.com/mapserver/mapserver/issues/324
    // altho i swear this worked a couple days ago in a hardcoded test
//    $kml_data = file_get_contents($kml_link);

    $wget_command = 'wget -O parcels-tmks/' . $tmk . '.kml ' . $kml_link;
    $command = escapeshellcmd($wget_command);
    exec($command);
  }
  fclose($handle);
}
