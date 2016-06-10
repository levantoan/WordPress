/*Send Mail to customer*/
$to = $book_email;
$subject = 'Your book - '. get_bloginfo('name');
$body = 'Thank you for your booking! We will contact with you soon.';
$headers = array('Content-Type: text/html; charset=UTF-8');		 
$sendmail = wp_mail( $to, $subject, $body, $headers );
