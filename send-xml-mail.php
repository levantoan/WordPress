<?php
add_action( 'wpcf7_before_send_mail', 'onego_contact7' );
function onego_contact7( $cf7 )
{
	$data = $_POST;
	og_send_mail($data);

}
function og_send_mail($data){
	if(isset($data['xmldata']) && $data['xmldata'] == 'sendxml'){
		$ctf7_id = $data['_wpcf7'];
		$meta = get_post_meta($ctf7_id,'_mail',true);
		$meta = maybe_unserialize($meta);
		$to = isset($meta['recipient'])?$meta['recipient']:get_bloginfo('admin_email');
		/* Create XML Document */
		$xmlDoc = new DOMDocument('1.0');
		
		/* Build Maximizer XML file */
		$xmlRoot = $xmlDoc->createElement('data');
		$xmlDoc->appendChild($xmlRoot);
		//firstname
		$xmlfirstname = $xmlDoc->createElement('FirstName',$data['first-name']);
		$xmlRoot->appendChild($xmlfirstname);
		//firstname
		$xmllastname = $xmlDoc->createElement('LastName',$data['last-name']);
		$xmlRoot->appendChild($xmllastname);
		//firstname
		$xmlcom = $xmlDoc->createElement('Company',$data['company']);
		$xmlRoot->appendChild($xmlcom);
		//firstname
		$xmlcountry = $xmlDoc->createElement('Country',$data['country']);
		$xmlRoot->appendChild($xmlcountry);
		//firstname
		$xmlphone = $xmlDoc->createElement('Phone',$data['your-tel']);
		$xmlRoot->appendChild($xmlphone);
		//firstname
		$xmlemail = $xmlDoc->createElement('Email',$data['your-email']);
		$xmlRoot->appendChild($xmlemail);
		//firstname
		$xmlmes = $xmlDoc->createElement('YourMessage',$data['your-message']);
		$xmlRoot->appendChild($xmlmes);
		//firstname
		$xmlnewsletter = $xmlDoc->createElement('Newsletter',isset($data['checkbox'])?'Yes':'No');
		$xmlRoot->appendChild($xmlnewsletter);
		//define the receiver of the email
		//$to = 'rockman.tth@gmail.com';
		$content = chunk_split(base64_encode($xmlDoc->saveXML()));
		$filename = 'myfile.xml';
		$subject = 'XML file for contact form 7';
		$message = 'My message';
		// a random hash will be necessary to send mixed content
		$separator = md5(time());
		
		// carriage return type (RFC)
		$eol = "\r\n";
		$fname = $data['first-name']." ".$data['last-name'];
		$femail = $data['your-email'];
		// main header (multipart mandatory)
		$headers = "From: {$fname} <{$femail}>" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;		
		$headers .= "Content-Type: multipart/mixed; charset=UTF-8" . $eol;
		$headers .= "boundary=\"" . $separator . "\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol;
		
		// message
		$body = "--" . $separator . $eol;
		$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
		$body .= "Content-Transfer-Encoding: 8bit" . $eol;
		$body .= $message . $eol;
		
		// attachment
		$body .= "--" . $separator . $eol;
		$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
		$body .= "Content-Transfer-Encoding: base64" . $eol;
		$body .= "Content-Disposition: attachment" . $eol;
		$body .= $content . $eol;
		$body .= "--" . $separator . "--";
		wp_mail($to, $subject, $body,$headers);
		
	}
}
