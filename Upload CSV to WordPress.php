<?php
<form action="" method="post" enctype="multipart/form-data">
  <?php wp_nonce_field('coupons_code_nonce','coupons_nonce');?>	
  <input type="file" name="filecsv_link" id="filecsv_link" accept=".csv"/>
  <input name="export" type="submit" class="button button-primary button-large export_coupons" id="import_csv" value="Import CSV">			
</form>
<div class="mess_process"></div>
<?php
$datas = array();	
if(isset($_FILES['filecsv_link'])){
  $uploadedfile = $_FILES['filecsv_link'];
  if(is_array($uploadedfile)){ 
    $ext = pathinfo($uploadedfile['name'], PATHINFO_EXTENSION);
    if($ext == 'csv' && $uploadedfile['type'] == 'application/vnd.ms-excel'){					

      if ( ! function_exists( 'wp_handle_upload' ) ) {
          require_once( ABSPATH . 'wp-admin/includes/file.php' );
      }

      $upload_overrides = array( 'test_form' => false );

      $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

      if ( $movefile && ! isset( $movefile['error'] ) ) {
        if (($handle = @fopen($movefile['file'], "r")) !== FALSE) {
          $row = 0;						
            while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
              if($row > 0)
                $datas[] = $data;						    	
              $row++;		
            }
            fclose($handle);
        }
        wp_delete_file($movefile['file']);
      } else {
          echo $movefile['error'];
      }
    }
  }
}
echo '<pre>';
print_r($datas);


//Change folder Upload
function edd_set_upload_dir( $upload ) {
    $upload['subdir'] = '/csv-linux-product';
    $upload['path'] = $upload['basedir'] . $upload['subdir'];
    $upload['url']  = $upload['baseurl'] . $upload['subdir'];
    return $upload;
}
function edd_change_downloads_upload_dir() {
    global $pagenow;

    if ( ! empty( $_GET['page'] ) && 'edit.php' == $pagenow ) {
        if ( 'coupons-code-export' == $_GET['page'] ) {
            add_filter( 'upload_dir', 'edd_set_upload_dir' );
        }
    }
}
add_action( 'admin_init', 'edd_change_downloads_upload_dir', 999 );
