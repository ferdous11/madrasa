<?php
// ------------------------------------------------------------------------

/**
 * CSV Helpers
 * Inspiration from PHP Cookbook by David Sklar and Adam Trachtenberg
 * 
 * @author		ferdous
 * @link		fzamancse11@gmail.com
 */

// ------------------------------------------------------------------------
	function fu_b2eNo ($number){

        $search_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $replace_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $en_number = str_replace($search_array, $replace_array, $number);

        return $en_number;
    }

    function fu_e2bNo ($number){

        $replace_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $en_number = str_replace($search_array, $replace_array, $number);

        return $en_number;
    }


    function fu_no2bangWord($numbers2){

        $z2h = array("শূন্য","এক","দুই","তিন","চার","পাঁচ","ছয়","সাত","আট","নয়","দশ","এগার","বার","তের","চৌদ্দ","পনের","ষোল","সতের","আঠার","ঊনিশ","বিশ","একুশ","বাইশ","তেইশ","চব্বিশ","পঁচিশ","ছাব্বিশ","সাতাশ","আটাশ","ঊনত্রিশ","ত্রিশ","একত্রিশ","বত্রিশ","তেত্রিশ","চৌত্রিশ","পঁয়ত্রিশ","ছত্রিশ","সাঁইত্রিশ","আটত্রিশ ","ঊনচল্লিশ","চল্লিশ","একচল্লিশ","বিয়াল্লিশ","তেতাল্লিশ","চুয়াল্লিশ","পঁয়তাল্লিশ","ছেচল্লিশ","সাতচল্লিশ","আটচল্লিশ","ঊনপঞ্চাশ","পঞ্চাশ","একান্ন","বায়ান্ন","তিপ্পান্ন","চুয়ান্ন","পঞ্চান্ন","ছাপ্পান্ন","সাতান্ন","আটান্ন","ঊনষাট","ষাট","একষট্টি","বাষট্টি","তেষট্টি","চৌষট্টি","পঁয়ষট্টি","ছেষট্টি","সাতষট্টি","৬৮ আটষট্টি","ঊনসত্তর","সত্তর","একাত্তর","বাহাত্তর","তিয়াত্তর","চুয়াত্তর","পঁচাত্তর","ছিয়াত্তর","সাতাত্তর","আটাত্তর","ঊনআশি","আশি","একাশি","বিরাশি","তিরাশি","চুরাশি","পঁচাশি","ছিয়াশি","সাতাশি","আটাশি","ঊননব্বই","নব্বই","একানব্বই","বিরানব্বই","তিরানব্বই","চুরানব্বই","পঁচানব্বই","ছিয়ানব্বই","সাতানব্বই","আটানব্বই","নিরানব্বই");

        $in_word[0] = "";
        $in_word[1] = "";
        $numbers2 = fu_b2eNo($numbers2);

        if($numbers2<0)
            $numbers2*=-1;
        $numbers3=$numbers=(int)$numbers2;

        if($numbers>999999999)
            return "Out of range";

        if($numbers==0){
            $in_word[0] = 'শূন্য';
            return $in_word;
        }

        if($numbers>=10000000)
        {
            $tem = (int) ($numbers/10000000);
            $numbers%=10000000;
            $in_word[0] .= $z2h[$tem]." কোটি ";
        }

        if($numbers>=100000)
        {
            $tem = (int) ($numbers/100000);
            $numbers%=100000;
            $in_word[0] .= $z2h[$tem]." লাখ ";
        }

        if($numbers>=1000)
        {
            $tem = (int) ($numbers/1000);
            $numbers%=1000;
            $in_word[0] .= $z2h[$tem]." হাজার ";
        }

        if($numbers>=100)
        {
            $tem = (int) ($numbers/100);
            $numbers%=100;
            $in_word[0] .= $z2h[$tem]." শত ";
        }

        if($numbers!=0)
        $in_word[0] .= $z2h[$numbers];
        
        if($numbers3<$numbers2){
            $numbers2=$numbers2-$numbers3;
            $numbers2=($numbers2*100);
            $in_word[1]=$z2h[$numbers2];
        }

        return $in_word;
    }

    function smsOO($number,$text){
        $message = trim($text);
        if(strlen($number)<11)
            return 0;

        $url = "http://bulksmsbd.net/api/smsapi";
        $api_key = "wi58qy4C21RK4jen6w3l";// local thke sms jeno na jay tai deya hoy nai
        $senderid = "8809617611022";
        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function smsWhatsappAdmin($message){
        $phone="8801744896062";  // Enter your phone number here
        $apikey="4140424";       // Enter your personal apikey received in step 3 above
        $url='https://api.callmebot.com/whatsapp.php?source=php&phone='.$phone.'&text='.urlencode($message).'&apikey='.$apikey;

        if($ch = curl_init($url)):
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $html = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // echo "Output:".$html;  // you can print the output for troubleshooting
            curl_close($ch);
            return (int) $status;
        else:
        return 0;
        endif;
    }

    function convert_number($number) { 
        $my_number = $number;

        if (($number < 0) || ($number > 999999999)) 
        { 
        throw new Exception("Number is out of range");
        } 
        $Kt = floor($number / 10000000); /* Koti */
        $number -= $Kt * 10000000;
        $Gn = floor($number / 100000);  /* lakh  */ 
        $number -= $Gn * 100000; 
        $kn = floor($number / 1000);     /* Thousands (kilo) */ 
        $number -= $kn * 1000; 
        $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
        $number -= $Hn * 100; 
        $Dn = floor($number / 10);       /* Tens (deca) */ 
        $n = $number % 10;               /* Ones */ 
        $res = ""; 
        if ($Kt) 
        { 
            $res .= convert_number($Kt) . " Koti "; 
        } 
        if ($Gn) 
        { 
            $res .= convert_number($Gn) . " Lakh"; 
        } 
        if ($kn) 
        { 
            $res .= (empty($res) ? "" : " ") . 
                convert_number($kn) . " Thousand"; 
        } 
        if ($Hn) 
        { 
            $res .= (empty($res) ? "" : " ") . 
               convert_number($Hn) . " Hundred"; 
        } 
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
            "Nineteen"); 
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
            "Seventy", "Eigthy", "Ninety"); 
        if ($Dn || $n) 
        { 
            if (!empty($res)) 
            { 
                $res .= " and "; 
            } 
            if ($Dn < 2) 
            { 
                $res .= $ones[$Dn * 10 + $n]; 
            } 
            else 
            { 
                $res .= $tens[$Dn]; 
                if ($n) 
                { 
                    $res .= "-" . $ones[$n]; 
                } 
            } 
        } 
        if (empty($res)) 
        { 
            $res = "zero"; 
        } 
        return $res; 
    } 


?>