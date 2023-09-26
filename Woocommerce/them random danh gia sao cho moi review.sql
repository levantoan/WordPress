-- Thêm giá trị ngẫu nhiên 4 hoặc 5 vào table wpu1_commentmeta với nhiều giá trị 5 sao hơn

INSERT INTO wpu1_commentmeta (comment_id, meta_key, meta_value)
SELECT
    comment_ID AS comment_id,
    'rating' AS meta_key,
    CASE WHEN RAND() < 0.8 THEN '5' ELSE '4' END AS meta_value
FROM wpu1_comments
WHERE comment_type = 'review';
