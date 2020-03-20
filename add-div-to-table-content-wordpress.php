/*
* Author: levantoan.com
Add div to table in wordpress content
*/
function devvn_table_respon($content) {
    $content = preg_replace('/<table([\S\s]*?)<\/table>/', '<div class="devvn_table_respon"><table$1</table></div>', $content);
    return $content;
}
add_filter('the_content', 'devvn_table_respon');

/*CSS
.devvn_table_respon {
    overflow-y: hidden;
    overflow-x: auto;
}
*/
