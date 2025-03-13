/*
Css
.load-more-posts-wrap {
    text-align: center;
}
div#post-list .nav-pagination {
    display: none;
}
button#load-more-posts {
    padding: 10px 25px !important;
    color: #231f20;
    font-weight: 500;
    border: 1px solid #231f20;
    border-radius: 25px;
    min-height: auto;
    margin: 0;
    line-height: 1.5;
}
button#load-more-posts:hover{
    background: var(--primary-color, #ed1c24);
    border-color: var(--primary-color, #ed1c24);
    color: #fff !important;
}
*/

/*Load more post by ajax*/
function checkAndAddLoadMoreButton() {
    if ($('#post-list .nav-pagination a.next').length) {
        if (!$('.load-more-posts-wrap').length) {
            $('#post-list .nav-pagination').after('<div class="load-more-posts-wrap"><button id="load-more-posts">Xem thêm tin</button></div>');
        }
    } else {
        $('.load-more-posts-wrap').remove();
    }
}
checkAndAddLoadMoreButton();

$(document).on('click', '#load-more-posts', function(e) {
    e.preventDefault();

    var $button = $(this);
    var nextLink = $('#post-list .nav-pagination a.next').attr('href');

    $button.text('Đang tải...').prop('disabled', true);

    $.get(nextLink, function(data) {
        var $data = $(data);

        var $newPosts = $data.find('#post-list > .row').children();

        $('#post-list > .row').append($newPosts);

        var $newPagination = $data.find('#post-list .nav-pagination');
        $('#post-list .nav-pagination').replaceWith($newPagination);

        checkAndAddLoadMoreButton();

        history.pushState({}, document.title, nextLink);

        $button.text('Xem thêm tin').prop('disabled', false);
    }).fail(function() {
        $button.text('Xem thêm tin').prop('disabled', false);
        alert('Có lỗi xảy ra khi tải bài viết');
    });
});
