<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>amiya</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
<?php
$url = "https://job.rikunabi.com/2018/search/company/result/?isc=r8rcnc00939&moduleCd=2&b=86&ms=0&all_it=all&j=21&j=22&j=23&j=24&j=25&j=26&j=27&j=47&k=27&kk=0";

$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
@$dom->loadHTMLFile($url);
$xpath = new DOMXPath($dom);
//var_dump(gettype($dom));
//文字化けが発生しているが情報は取れている。
//$buf = mb_convert_encoding(file_get_contents($url), 'utf-8', 'windows-31j');
//print_r($buf);

//件数の取得
$tests = $xpath->query('//span[@class="search-resultCounter-count"]')->item(0)->nodeValue;

//ページ数の計算
$p = $tests/100;
//print $p . "\n";

$csv_header = ["会社名","資本金","売上高","代表者","ホームページ","メールアドレス","連絡先"];
$companies[] = $csv_header;
var_dump($csv_header);

for($i=1;$i<=$p+1;$i++){

    $url = "https://job.rikunabi.com/2018/search/company/result/?isc=r8rcnc00939&moduleCd=2&b=86&ms=0&all_it=all&j=21&j=22&j=23&j=24&j=25&j=26&j=27&j=47&k=27&kk=0&pn=" . $i;
//print $url . "\n";

    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    @$dom->loadHTMLFile($url);
    $xpath = new DOMXPath($dom);

    $tests = $xpath->query('//div[@class="search-cassette-title"]/a');
//var_dump($tests);
//class DOMNodeList#104 (1) {
//  public $length =>
//  int(101)
//}

    /*foreach($tests as $test){
        $link = $test->getAttribute('href');
//echo $link."\n";
//https://job.rikunabi.com/2018/company/r333400057/

        $link = "https://job.rikunabi.com". $link;
//print $link . "\n";

        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        @$dom->loadHTMLFile($link);
        $xpath = new DOMXPath($dom);

// 会社名の取得
        $companys = trim($xpath->query('//h1[@class="ts-h-company-mainTitle"]')->item(0)->nodeValue);
        $company1 = array("会社名"=> $companys);
//print_r($company1);

//会社データのラベルの取得
        $header = $xpath->query('//tr/th[@class="ts-h-mod-dataTable02-cell ts-h-mod-dataTable02-cell_th"]');

//会社データの値の取得
        $value = $xpath->query('//tr/td[@class="ts-h-mod-dataTable02-cell ts-h-mod-dataTable02-cell_td"]');

        $keys = [];
        $vals = [];

        foreach($header as $node){

            $keys[] = trim($node->nodeValue);
            //print_r($keys);

        }

        foreach($value as $node){

            $vals[] = trim($node->nodeValue);
            //print_r($vals);
        }

        $array = array_combine($keys, $vals);
        $array = str_replace(array("\r\n", "\r", "\n"), '', $array);
//var_dump($array);

//連絡先の取得
        $address = trim($xpath->query('//*[@id="company-data04"]/div[@class="ts-h-company-sentence"]')->item(0)->nodeValue);

//連絡先の改行削除
        $address = str_replace(array("\r\n", "\r", "\n"), '', $address);

        $address = array("連絡先" => $address);

//連絡先から企業URLを取得する。
        //preg_match('/(http|https)(：|:)(.*?)(.jp|.com)/', $address, $match);
//var_dump($match[0]);
        //$url = $match[0];
//var_dump($url);

        $company_url = (isset($array["ホームページ"])) ? $array["ホームページ"] : '';
        if($company_url == ""){
            $array["ホームページ"] = $url;
        }

//連絡先からメールアドレスを取得する。
        //preg_match('/(mail|E－mail|e-mail|Mail|MAIL|メール)(：|:)(.*?)(.jp|.com)/', $address, $match);
//var_dump($match);
        //$email = $match[3].$match[4];

        //$address_array = array("メールアドレス"=> $email);

        //$company = array_merge($array,$address_array);
        //$company = array_merge($company,$address);
        //$company = array_merge($company1,$company);
        //var_dump($company);

        //$_company = [];
        //foreach($csv_header as $column_name){

          //  $_company[$column_name] = (isset($company[$column_name])) ? $company[$column_name] : '';

        }

//var_dump(["会社名","資本金","売上高","代表者","ホームページ","メールアドレス"]);

        //$company = $_company;
        //var_dump($company);


        //$companies[] = array_merge($company);
        //var_dump($companies);

        //$result = [];


    }
    //$result = $result + $companies;
//$result[] = $companies;
//var_dump($result);
//}

//print_r($result);
//var_dump($result);

//CSV出力方法2⇒左詰め問題は解決したがヘッダーが文字化け+エラーも発生
/*$csv_header = array(
        '会社名',
        '事業内容',
        '資本金',
        '売上高',
        '代表者',
        'ホームページ',
        'メールアドレス',
   );

//date時間の関数年月日時分秒　会社名_年月日時分秒
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=rikunabi.csv');

$stream = fopen('rikunabi.csv', 'w');
//stream_filter_prepend($stream,'convert.iconv.utf-8/cp932');
fputcsv($stream, $csv_header);

foreach($result as $key => $arrays_row){
  $numeric_row = array();
      foreach ($csv_header as $header_name) {
          mb_convert_variables('SJIS-win', 'UTF-8', $arrays_row[$header_name]);
          $numeric_row[] = $arrays_row[$header_name];
      }
        fputcsv($stream, $numeric_row);
}*/

/*//CSV化の年月日時分秒
$today = date("YmdHis");

$f = fopen("rikunabi_${today}.csv", "w");
stream_filter_prepend($f,'convert.iconv.utf-8/cp932');
// 正常にファイルを開くことができていれば、書き込みます。
if ( $f ) {
    // $ary から順番に配列を呼び出して書き込みます。
    foreach($result as $line){
        //var_dump($line);
        // fputcsv関数でファイルに書き込みます。
        fputcsv($f, $line);
    }
}
// ファイルを閉じます。
fclose($f);*/
}

?>








    </body>
</html>
