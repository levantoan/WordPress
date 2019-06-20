<?php
/*
 * Add quickbuy addter add to cart button. Redirect to cart after click
 * Author: levantoan.com
 */
add_action('woocommerce_after_add_to_cart_button','devvn_quickbuy_after_addtocart_button');
function devvn_quickbuy_after_addtocart_button(){
    global $product;
    ?>
    <style></style>
    <a href="javascript:void(0)" rel="nofollow" class="button alt" id="devvn_quickbuy_button_<?php echo $product->get_id();?>" data-id="<?php echo $product->get_id();?>" data-base="<?php echo wc_get_cart_url();?>">Mua ngay</a>
    <script type="text/javascript">
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('k u(t,e){m(4 r=0,i=t.w;r<i;r++)b(t[r]==e)8!0;8!1}k j(t,e){m(4 r=7.v(e),i=t.A;i&&!u(r,i);)i=i.A;8 i}7.G("H").I=k(t){t.J();4 e=s(9.D.F),r=9.D.K,i=7.f(\'l.2 g[6="q"]\')?s(7.f(\'l.2 g[6="q"]\').h):e,n=j(9,".2").f("l.E.2 g.3");3=M!=n?n.h:"x";4 a=j(9,".2").v(\'.Q [6^="R"]\'),o=[];m(5=0;5<a.w;++5)o.L(a[5].O("6")+"="+a[5].h);4 d=o.P("&");b(i==e&&"x"==3){4 c=r+"?z-B-2="+i;C.p.y=c}N{b(i!=e||0==3)8!1;c=r+"?z-B-2="+i+"&3="+3+"&"+d;C.p.y=c}};',54,54,'||cart|variation_id|var|index|name|document|return|this||if||||querySelector|input|value||findParentBySelector|function|form|for|||location|product_id||parseInt||collectionHas|querySelectorAll|length|simple|href|add|parentNode|to|window|dataset|variations_form|id|getElementById|devvn_quickbuy_button_<?php echo $product->get_id();?>|onclick|preventDefault|base|push|null|else|getAttribute|join|variations|attribute_'.split('|'),0,{}))
    </script>
    <?php
}
