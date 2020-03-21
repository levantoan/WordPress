/*CSS*/
/*

.devvn_callmeback {
    border: 2px dashed #e03232;
    padding: 10px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    background: #ffe8e8 !important;
    color: #000;
    margin: 0 !important;
}
.devvn_callmeback_title {
    text-align: center;
    max-width: 290px;
    margin: 0 auto;
}
.devvn_callmeback_title strong {
    font-size: 20px;
}
.devvn_callmeback_form {
    -js-display: flex;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-flow: row nowrap;
    flex-flow: row nowrap;
    -ms-flex-pack: justify;
    justify-content: space-between;
    width: 100%;
}
.devvn_callmeback_form input.wpcf7-text {
    width: 100%;
    height: 32px;
    border: 1px solid #ddd;
    border-radius: 3px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    margin-bottom: 3px;
    padding: 0 10px ;
}
.devvn_col {
    max-height: 100%;
    padding: 0 3px;
}
.devvn_callmeback_form input.wpcf7-submit {
    padding: 0 10px;
    font-size: 12px;
    height: 32px;
    line-height: 1;
    min-height: auto;
    border-radius: 3px;
}
.devvn_callmeback_form span.wpcf7-not-valid-tip {
    font-size: 12px;
}
*/
/*CF7*/
/*
<div class="devvn_callmeback">
<div class="devvn_callmeback_title">
<strong style="color: #e03232;">YÊU CẦU GỌI LẠI</strong>
<p>Vui lòng nhập đầy đủ thông tin chúng tôi sẽ gọi lại cho bạn ngay.</p>
</div>
<div class="devvn_callmeback_form">
<div class="devvn_col">[tel* your-phone placeholder "Số điện thoại"]</div>
<div class="devvn_col">[text your-loinhan placeholder "Lời nhắn"]</div>
<div class="devvn_col">[submit "Gửi"]</div>
</div>
</div>
============================
CF7 - content
SĐT: [your-phone]
Lời nhắn: [your-loinhan]
Sản phẩm: [_post_title] - [_post_url]
*/
/*Functions*/
add_action('woocommerce_single_product_summary', 'devvn_callmeback', 36);
function devvn_callmeback(){
    echo do_shortcode('[contact-form-7 id="2424" title="Yêu cầu gọi lại"]');
}
