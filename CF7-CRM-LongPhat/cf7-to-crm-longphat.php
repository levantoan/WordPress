<?php
/*
Author: levantoan.com
Insert to functions.php
*/

class Devvn_Cf7_CRMLongPhat{

    protected $username = "data";
    protected $password = "crmlongphat12131415@";
    public $url = "http://crm.longphat.com.vn/service/v4_1/rest.php";
    protected $goi_crm = '';
    public $cf7ID = '';
    public $test_mode = false;

    function __construct()
    {
        $this->set_username(''); //Username
        $this->set_password(''); // Password
        $this->goi_crm = ''; // Gói CRM
        $this->cf7ID = 385; // ID của Contact Form 7
        $this->test_mode = true; // True để ghi log

        add_action( 'wpcf7_before_send_mail', array($this, 'process_contact_form_to_crmlongphat') );
    }
    function set_username($username){
        $this->username = $username;
    }
    function set_password($password){
        $this->password = $password;
    }
    function set_url($url){
        $this->url = $url;
    }
    function restRequest($method, $arguments){
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $post = array(
            "method" => $method,
            "input_type" => "JSON",
            "response_type" => "JSON",
            "rest_data" => json_encode($arguments),
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result,1);
    }
    function login()
    {
        $login_parameters = array(
            //user authentication
            "user_auth" => array(
                "user_name" => $this->username,// User name
                "password" => md5($this->password),// Password
            ),

            //application name
            "application_name" => "Long Phat CRM",

            //name value list for 'language' and 'notifyonsave'
            "name_value_list" => array(
            ),
        );

        $result = $this->restRequest('login',$login_parameters);
        return $result['id'];
    }
    function createNewLead($name, $phone, $email, $desc)
    {
        $session_id = $this->login();
        $target_parameters = array(
            //session id
            "session" => $session_id,

            //The name of the module from which to retrieve records.
            "module_name" => "Leads",

            //Record attributes
            "name_value_list" => array(

                array(
                    "name" => "last_name",
                    "value" => $name //Ten
                ),

                array(
                    "name" => "phone_mobile",
                    "value" => $phone //Sdt
                ),

                array(
                    "name" => "email1",
                    "value" => $email //Email
                ),

                array(
                    "name" => "description",
                    "value" => $desc //Ghi chu
                ),

                array(
                    "name" => "leadtype_c",
                    "value" => "FromWeb" // Goi CRM
                )
            ),
            array(
                "name" => "assigned_user_id",
                "value" => $this->goi_crm // Goi CRM
            )

        );
        $result = $this->restRequest('set_entry', $target_parameters); //result.id	String	The ID of the record that was created/updated.

        if($this->test_mode) {
            ob_start();
            print_r($result);
            $result = ob_get_clean();
            $log = "User: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
                "ID " . $result . PHP_EOL .
                "-------------------------" . PHP_EOL;
            file_put_contents(dirname(__FILE__) . '/log_crm_long_phat.txt', $log, FILE_APPEND);
        }

    }
    function process_contact_form_to_crmlongphat($cf7){
        if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $post_data = $submission->get_posted_data();
                $name = sanitize_text_field($post_data['your-name']);
                $phone = sanitize_text_field($post_data['your-phone']);
                $email = sanitize_text_field($post_data['your-email']);
                $mess = sanitize_text_field($post_data['your-message']);
                $_wpcf7ID = intval($post_data['_wpcf7']);
                if($_wpcf7ID == $this->cf7ID){
                    $this->createNewLead($name, $phone, $email, $mess);
                }
            }
        }
    }

}
$cf7_crm_longphat = new Devvn_Cf7_CRMLongPhat();
