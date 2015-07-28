<?php
/*
Liệt kê toàn bộ quốc gia trong woo
*/
?>
<select>
<?php  			
$countries = WC()->countries->get_allowed_countries();
foreach ($countries as $k=>$v){
echo '<option value="'.$k.'">'.$v.'</option>';
}
?>
</select>