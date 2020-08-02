<?php
$combined_schemed = [];
$files = scandir("C:/Users/acadg/Desktop/JSON");

$row = 0;

function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

function issset($string=null) {
    if (isset($string)) {
        return $string;
    }else{
        return null;
    }
}

function jsonToCSV($json, $cfilename)
{
    $data = json_decode($json, true);
    $fp = fopen($cfilename, 'w');
    $header = false;
    foreach ($data as $row)
    {
        if (empty($header))
        {
            $header = array_keys($row);
            fputcsv($fp, $header);
            $header = array_flip($header);
        }
        fputcsv($fp, array_merge($header, $row));
    }
    fclose($fp);
    return;
}

foreach (array_slice($files,2,count($files)-1) as $key => $value){
  $schemed = [];
  ini_set('memory_limit', '-1');


	$arr = json_decode(file_get_contents("C:/Users/acadg/Desktop/JSON/".$value),true);
    if ($arr!=NULL) {
        foreach ($arr as $key => $value) {
            $schemed['uniqueId']=issset($arr['user']['uniqueId']);
            $schemed['email']=issset($arr['user']['email']);
            $schemed['phone']=issset($arr['user']['phone']);
            $schemed['name']=issset($arr['user']['name']);
            $schemed['image']=issset($arr['user']['image']);
            $schemed['companyLogo']=issset($arr['user']['companyLogo']);
            $schemed['startup_name']=issset($arr['user']['startup']['name']);
            $schemed['startup_LegalName']=issset($arr['user']['startup']['legalName']);
            $schemed['startup_cin']=issset($arr['user']['startup']['cin']);
            $schemed['startup_pan']=issset($arr['user']['startup']['pan']);
            $schemed['startup_logo']=issset($arr['user']['startup']['logo']);
            $schemed['startup_stage']=issset($arr['user']['startup']['stage']);
            $schemed['startup_udyogAadhar']=issset($arr['user']['startup']['udyogAadhar']);
            $schemed['startup_ideaBrief']=issset(strip_tags($arr['user']['startup']['ideaBrief']));
            $schemed['country']=issset($arr['user']['startup']['location']['country']['text']);
            $schemed['state']=issset($arr['user']['startup']['location']['state']['text']);
            $schemed['city']=issset($arr['user']['startup']['location']['city']['text']);
            $schemed['startup_website']=issset($arr['user']['startup']['website']);
            $schemed['startup_app']=issset($arr['user']['startup']['mobileAppLink']['Android']);
            $schemed['startup_focusArea_industry_name']=issset($arr['user']['startup']['focusArea']['industry']['text']);
            if (issset($arr['user']['startup']['socialInfos'])!=null) {
                foreach (issset($arr['user']['startup']['socialInfos']) as $key => $item) {
                    $schemed['startup_'.$item['social']]=$item['url'];
                }
            }
            $index = 1;
            if (issset($arr['user']['startup']['members'])!=null) {
                if($index<=4){
                foreach (issset($arr['user']['startup']['members']) as $key => $item) {
                    
                    foreach ($item as $key1 => $item1) {
                        if ($key1 == 'socialInfos') {
                            if (issset($item1)!=null) {
                                foreach ($item1 as $key2 => $item2) {
                                    $schemed['startup_member_'.$index.'_'.$item2['social']]=$item2['url'];
                                }
                            }
                        } else {
                            if (in_array($key1, ['name','pic','role'])) {
                                $schemed['startup_member_'.$index.'_'.$key1]=$item1;
                            }
                        }
                    }
                    $index++;
                }
                }
            }
        }
    }
    if($schemed!=[]){
        $combined_schemed[$row] = $schemed;
    }
  $row++;
}
echo json_encode($combined_schemed);
exit;
jsonToCSV(json_encode($combined_schemed), 'output.csv');

?>
