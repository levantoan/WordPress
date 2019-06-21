<?php
/*
 * Add quickbuy button go to cart after click
 * Author: levantoan.com
 */
add_action('woocommerce_after_add_to_cart_button','devvn_quickbuy_after_addtocart_button');
function devvn_quickbuy_after_addtocart_button(){
    global $product;
    $rand = time()+rand(0,9);
    ?>
    <style></style>
    <a href="javascript:void(0)" rel="nofollow" class="button alt" id="devvn_quickbuy_button_<?php echo $rand;?>" data-id="<?php echo $product->get_id();?>" data-base="<?php echo wc_get_cart_url();?>">Mua ngay</a>
    <script type="text/javascript">
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('j y(t,e){f(2 r=0,i=t.u;r<i;r++)b(t[r]==e)8!0;8!1}j 9(t,e){f(2 r=D.l(e),i=t.C;i&&!y(r,i);)i=i.C;8 i}D.K("I").E=j(t){t.J();2 e=F(7.k.H),r=7.k.L,i=9(7,".3").s(\'q.3 m[h="5"]\');5=p!=i?i.g:e;2 n=9(7,".3").s("q.G.3 m.4");4=p!=n?n.g:"v";2 a=9(7,".3").l(\'.Q [h^="P"]\'),o=[];f(6=0;6<a.u;++6)o.M(a[6].N("h")+"="+a[6].g);2 d=o.R("&");b(5==e&&"v"==4){2 c=r+"?x-w-3="+5;z.B.A=c}O{b(5!=e||0==4)8!1;c=r+"?x-w-3="+5+"&4="+4+"&"+d;z.B.A=c}};',54,54,'||var|cart|variation_id|product_id|index|this|return|findParentBySelector||if||||for|value|name||function|dataset|querySelectorAll|input|||null|form||querySelector||length|simple|to|add|collectionHas|window|href|location|parentNode|document|onclick|parseInt|variations_form|id|devvn_quickbuy_button_<?php echo $rand;?>|preventDefault|getElementById|base|push|getAttribute|else|attribute_|variations|join'.split('|'),0,{}))
    </script>
    <?php
}
