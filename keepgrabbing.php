<?php
set_time_limit(0);
include('simplehtmldom/simple_html_dom.php');

function strip_nbsp($str) {
  return str_replace('&nbsp;', '', $str);
}

function strip_doublespace($str) {
  return str_replace('  ', ' ', $str);
}

$html = new simple_html_dom();
$csv = array_map('str_getcsv', file('leilani.csv'));
$hales = array();
$malformed_hales = array();

$debug_i = 0;
if (($handle = fopen("leilani.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
    if (strpos($data[1], 'http') === 0) {
      // Break these out into functions bumbai
      $qpublic_page = file_get_contents($data[1]);
      $html->load($qpublic_page);
      $els = $html->find('td.owner_value a');
      $map_url = '';
      foreach ($els as $el) {
        $href = $el->attr['href'];
        echo('HREF IS ' . $href . '<br/>');
        if (strpos($href, 'http://qpublic9') === 0) {
          $map_url = $href;
          break;
        }
      }

      // Owner name
      $owner_table = $html->find('table', 2);
      $owner_name = $html->find('table', 2)->find('tr', 1)->find('td', 1)->innertext;
      $mailing_address = $html->find('table', 2)->find('tr', 2)->find('td', 1)->innertext;
      $location_address = $html->find('table', 2)->find('tr', 3)->find('td', 1)->innertext;

      echo('OWNER ROW<br/>');
      foreach ($owner_table->find('tbody tr td') as $td) {
        echo($td->innertext . '<br/>');
      }
      echo('END OWNER ROW<hr/>');

      $tmk = substr($data[1], strpos($data[1], 'KEY=') + 4);
      $hales[]  = array(
        'address' => $data[0],
        'url' => $data[1],
        'tmk' => $tmk,
        'map_url' => $map_url,
        'owner_name' => strip_tags(str_replace('&nbsp;', '', $owner_name)),
        'mailing_address' => str_replace('<br>', ',', strip_nbsp($mailing_address)),
        'location_address' => strip_doublespace(strip_nbsp($location_address)),
      );
    } else {
      $malformed_hales[] = array(
        'address' => $data[0],
        'extra' => $data[1]
      );
    }
    $debug_i++;

//    if ($debug_i == 3) break;
//    break; // remove to go thru all of them
    sleep(2);
  }
  fclose($handle);
}

$output_csv = fopen('aloha-honua.csv', 'w');
fwrite($output_csv, "Address,TMK,Map_URL,Owner_Name,Mailing_Address, Location_Address\n");
for ($i = 0; $i < count($hales); $i++) {
  fwrite($output_csv, $hales[$i]['address'] . ',"' . $hales[$i]['tmk'] . '","' . $hales[$i]['map_url'] . '","' . $hales[$i]['owner_name'] . '","' . $hales[$i]['mailing_address'] . '","' . $hales[$i]['location_address'] . "\"\n");
}
fclose($output_csv);

echo '<h1>Malformed addresses/rows not included</h1>';
for ($i = 0; $i < count($malformed_hales); $i++) {
  echo($malformed_hales[$i]['address'] . ' - ' . $malformed_hales[$i]['extra'] . '<br/>');
}
