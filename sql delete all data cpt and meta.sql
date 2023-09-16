DELETE p, pm
  FROM wp_posts p
 INNER
  JOIN wp_postmeta pm
    ON pm.post_id = p.ID
 WHERE p.post_type= 'your_custom_post_type';
