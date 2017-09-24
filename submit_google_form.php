<?php
$ch = curl_init();

// Xuất thông tin cấp key ra google form	
curl_setopt($ch, CURLOPT_URL,"https://docs.google.com/forms/d/1CZ9WthjKdh5WYF4EiRDmPpaZDdsFz1VSkQrCKrmFyss/formResponse");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "entry.1970949603=".urlencode($data[0]->name)."&entry.1539655264=".$data[0]->email."&entry.1577773444=".$data[0]->phoneno."&entry.512214156=".$key."&entry.1312405437=".urlencode($pack.' '.$numacc)."&entry.288611260=".$timeuse);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_exec ($ch);
curl_close ($ch);

/*
jQuery
jQuery.ajax({
    url: "https://docs.google.com/forms/d/1hnhKp8iJWG_yqQouY6-9a3wyVQ1e7S3b2RTrNtn-FuY/formResponse",
    data: {"entry.1970949603": name, "entry.1539655264": email, "entry.1577773444": phoneno, "entry.1097709750": city, "entry.53342246": esms},
    type: "POST",
    dataType: "xml",
    crossDomain: true
});
*/
