<?php

/*
Cách sử dụng:
[btc_price_small limit="0"] - Shortcode này để hiển thị dạng thu gọn ở sidebar. limit là số tiền hiển thị mặc định là 10, để 0 là hiển thị toàn bộ

[btc_price limit="0"] - Shortcode này để hiển thị dạng thu gọn ở sidebar. limit là số tiền hiển thị mặc định là 0, để 0 là hiển thị toàn bộ
*/

class btc_price{

    public $usd_vnd_mua = '';
    public $usd_vnd_ban = '';

    function __construct()
    {
        add_shortcode( 'btc_price', array($this, 'devvn_btc_price') );
        add_shortcode( 'btc_price_small', array($this, 'devvn_btc_price_widget') );
    }
    function json_curl($url, $data = ''){
        if(!$url) return false;
        if($data && is_array($data)){
            $url = $url.'?'.http_build_query($data);
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if(!is_ssl()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        $price_result = curl_exec($curl);
        curl_close($curl);
        $price_result = json_decode($price_result);
        if($price_result && !empty($price_result) && is_array($price_result)){
            return $price_result;
        }else{
            return false;
        }
    }
    function xml_curl($url, $data = ''){
        if(!$url) return false;
        if ( false === ( $usd_price = get_transient( 'usd_price_cache' ) ) ) {
            if($data && is_array($data)){
                $url = $url.'?'.http_build_query($data);
            }
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if(!is_ssl()) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            }
            $price_result = curl_exec($curl);
            curl_close($curl);
            $price_result = json_decode(json_encode((array) simplexml_load_string($price_result)), true);
            if($price_result && is_array($price_result) && !empty($price_result)){
                $Exrate = isset($price_result['Exrate']) ? $price_result['Exrate'] : '';
                if($Exrate && !empty($Exrate) && is_array($Exrate)){
                    foreach($Exrate as $vcb){
                        if(isset($vcb['@attributes']['CurrencyCode']) && $vcb['@attributes']['CurrencyCode'] == 'USD'){
                            $usd_price = $vcb['@attributes'];
                            set_transient( 'usd_price_cache', $usd_price, 5 * MINUTE_IN_SECONDS);
                            return $usd_price;
                        }
                    }
                }
            }else{
                return false;
            }
        }else{
            return $usd_price;
        }
    }
    function devvn_btc_price( $atts ) {
        $atts = shortcode_atts( array(
            'limit'     =>  0
        ), $atts, 'btc_price' );
        $limit = $atts['limit'];
        $data = array(
            'limit' => $limit
        );
        $json_curl = $this->json_curl('https://api.coinmarketcap.com/v1/ticker/', $data);
        $usd_price = $this->xml_curl('https://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx');
        if($usd_price && is_array($usd_price) && !empty($usd_price)) {
            $this->usd_vnd_mua = isset($usd_price['Buy']) ? $usd_price['Buy'] : 0;
            $this->usd_vnd_ban = isset($usd_price['Sell']) ? $usd_price['Sell'] : 0;
        }
        ob_start();
        if($json_curl):?>
            <style>
                table.devvn_btc_price {
                    width: 100%;
                    margin: 20px 0;
                    border-spacing: 0;
                    border-collapse: collapse;
                    border: 1px solid #ededed;
                    font-size: 15px;
                    line-height: 26px;
                    color: #222;
                    letter-spacing: 0.8px;
                }
                table.devvn_btc_price td, table.devvn_btc_price th {
                    padding: 2px 8px;
                    border: 1px solid #ededed;
                    font-size: 15px;
                    line-height: 26px;
                    color: #222;
                }
            </style>
            <table class="devvn_btc_price">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên</th>
                        <th>Mã</th>
                        <th>USD</th>
                        <th>Bán ra (VNĐ)</th>
                        <th>Mua vào (VNĐ)</th>
                        <th>% 24h</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($json_curl as $price):
                    $percent_change_24h = $price->percent_change_24h;
                    $buy = number_format($this->usd_vnd_mua * $price->price_usd, 0, ',', '.');
                    $sell = number_format($this->usd_vnd_ban * $price->price_usd, 0, ',', '.');
                    ?>
                    <tr>
                        <td><?php echo $price->rank;?></td>
                        <td><?php echo $price->name;?></td>
                        <td><?php echo $price->symbol;?></td>
                        <td><span style="color: #4db2ec"><?php echo $price->price_usd;?></span></td>
                        <td><span style="color: green"><?php echo $buy;?></span></td>
                        <td><span style="color: red"><?php echo $sell;?></span></td>
                        <td>
                            <?php if($percent_change_24h):?>
                                <?php if($percent_change_24h > 0):?>
                                    <span style="color: green"><?php echo $percent_change_24h;?>%</span>
                                <?php else:?>
                                    <span style="color: red"><?php echo $percent_change_24h;?>%</span>
                                <?php endif;?>
                            <?php endif;?>
                    </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php
        endif;
        return ob_get_clean();
    }
    function devvn_btc_price_widget( $atts ) {
        $atts = shortcode_atts( array(
            'limit'     =>  10
        ), $atts, 'btc_price_small' );
        $limit = $atts['limit'];
        $data = array(
            'limit' => $limit
        );
        $json_curl = $this->json_curl('https://api.coinmarketcap.com/v1/ticker/', $data);
        $usd_price = $this->xml_curl('https://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx');
        if($usd_price && is_array($usd_price) && !empty($usd_price)) {
            $this->usd_vnd_mua = isset($usd_price['Buy']) ? $usd_price['Buy'] : 0;
            $this->usd_vnd_ban = isset($usd_price['Sell']) ? $usd_price['Sell'] : 0;
        }
        ob_start();
        if($json_curl):?>
            <style>
                table.devvn_btc_price {
                    width: 100%;
                    margin: 20px 0;
                    border-spacing: 0;
                    border-collapse: collapse;
                    border: 1px solid #ededed;
                    font-size: 15px;
                    line-height: 26px;
                    color: #222;
                    letter-spacing: 0.8px;
                }
                table.devvn_btc_price td, table.devvn_btc_price th {
                    padding: 2px 8px;
                    border: 1px solid #ededed;
                    font-size: 15px;
                    line-height: 26px;
                    color: #222;
                }
            </style>
            <table class="devvn_btc_price">
                <thead>
                    <tr>
                        <th>STT</th>
						<th>Tên</th>
                        <th>USD</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($json_curl as $price):
                    $percent_change_24h = $price->percent_change_24h;
                    ?>
                    <tr>
                        <td><?php echo $price->rank;?></td>
						<td><?php echo $price->name;?></td>
                        <td>
                            <span style="color: #4db2ec">$<?php echo $price->price_usd;?></span>
                            <?php if($percent_change_24h):?>
                                <?php if($percent_change_24h > 0):?>
                                    <span style="color: green">(<?php echo $percent_change_24h;?>%) <i class="fa fa-sort-asc" aria-hidden="true"></i></span>
                                <?php else:?>
                                    <span style="color: red">(<?php echo $percent_change_24h;?>%)<i class="fa fa-sort-desc" aria-hidden="true"></i></span>
                                <?php endif;?>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
            <?php
        endif;
        return ob_get_clean();
    }
}
new btc_price;
