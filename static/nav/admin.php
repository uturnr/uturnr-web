<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "codyrobertson@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "788a74" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'6B64' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGRoCkMREpoi0Mjo6NCKLBbSINLo2OLSiiDWItLI2MEwJQHJfZNTUsKVTV0VFIbkvBGgeq6OjA4reVpB5gaEhGGIB2NyCIobNzQMVflSEWNwHAA3ezpOaDLkeAAAAAElFTkSuQmCC',
			'B05E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYAlhDHUMDkMQCpjCGsDYwOiCrC2hlbcUQmyLS6DoVLgZ2UmjUtJWpmZmhWUjuA6lzaAhEMw+bGMgONDGgWxgdHVHEQG5mCGVEcfNAhR8VIRb3AQC2OssCknmQ9QAAAABJRU5ErkJggg==',
			'9CF2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDA6Y6IImJTGFtdG1gCAhAEgtoFWlwbWB0EEETYwWpR3LftKnTVi0NXbUqCsl9rK5gdY3IdjBA9LYiu0UAbAfDFAYsbsFwcwNjaMggCD8qQizuAwA5QcwGMknL/AAAAABJRU5ErkJggg==',
			'8E5E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHUMDkMREpog0sDYwOiCrC2jFFAOrmwoXAztpadTUsKWZmaFZSO4DqWNoCMQwD5sYK5oYSC+joyOKGMjNDKGMKG4eqPCjIsTiPgAM+cmxigaHRQAAAABJRU5ErkJggg==',
			'23C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANYQxhCHVqRxUSmiLQyOgRMdUASC2hlaHRtEAgIQNbdytDK2sDoIILsvmmrwpauWpk1Ddl9ASjqwJDRAWQeqhhrA6YdIg2YbgkNxXTzQIUfFSEW9wEAHNbLVwdtnWQAAAAASUVORK5CYII=',
			'1423' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGUIdkMRYHRimMjo6OgQgiYk6MISyNgQ0iKDoZXRlAIoFILlvZdbSpSAiC8l9jA4irUBbGgJQ9IqGOkxhQDMPqCoAUwxoD6pbQhhaWUMDUNw8UOFHRYjFfQAlycj8sry7ewAAAABJRU5ErkJggg==',
			'E9B3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDGUIdkMQCGlhbWRsdHQJQxEQaXUEkulijQ0MAkvtCo5YuTQ1dtTQLyX0BDYyBSOqgYgxYzGPBIobpFmxuHqjwoyLE4j4A5+DPLGJp9aIAAAAASUVORK5CYII=',
			'E49D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMYWhlCGUMdkMQCGhimMjo6OgSgioWyNgQ6iKCIMboiiYGdFBq1dOnKzMisaUjuC2gQaWUIQdcrCrQTXYyhlRGbGJpbsLl5oMKPihCL+wChn8vMh5Q36wAAAABJRU5ErkJggg==',
			'DC4C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQxkaHaYGIIkFTGFtdGh1CBBBFmsVaXCY6ujAgibGEOjogOy+qKXTVq3MzMxCdh9IHWsjXB1CLDQQQ8yhEc0OkFsaUd2Czc0DFX5UhFjcBwACxM5um7ccEAAAAABJRU5ErkJggg==',
			'047F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YWllDA0NDkMRYAximMjQEOiCrE5nCEIouFtDK6MrQ6AgTAzspaunSpauWrgzNQnJfQKtIK8MURjS9oqEOAYzodrQyOqCKAd3SytqAKgZ2M5rYQIUfFSEW9wEAPr7IuTV5sGoAAAAASUVORK5CYII=',
			'7675' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsQ2AMAwEncIbmH1MQW8kQpFpnMIbABtQkClJ6QhKkPLfnWzp9FAeUeipv/jFGBaMcxRPDQ105ubSKD/YRgp5nNj7pWMt55WS8ws8GGyg5H5RKbO0jCobObBnomioINKw6qywcwf7fdgXvxvPBssukdlqDgAAAABJRU5ErkJggg==',
			'2233' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGUIdkMREprC2sjY6OgQgiQW0ijQ6NAQ0iCDrbmVodACLIrlv2qqlq6auWpqF7L4AhikMCHVgyOgAFEUzjxUkiiYmAhRFd0toqGioI5qbByr8qAixuA8AXtbNGNDfUH0AAAAASUVORK5CYII=',
			'5A09' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMYAhimMEx1QBILaGAMYQhlCAhAEWNtZXR0dBBBEgsMEGl0bQiEiYGdFDZt2srUVVFRYcjuawWpC5iKrJehVTQUKNaALBYAVAe0AsUOkSkijQ5obmEF2uuA5uaBCj8qQizuAwC0O8zR2AvTmwAAAABJRU5ErkJggg==',
			'511F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIaGIIkFNDAGMIQwOjCgiLEGMKKJBQaA9cLEwE4Km7YqatW0laFZyO5rRVGHUywAi5jIFEwx1gDWUMZQR1TzBij8qAixuA8A2h/G8kU//M0AAAAASUVORK5CYII=',
			'B0D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgMYAlhDGRoCkMQCpjCGsDY6NKKItbK2sgJJVHUija5AMgDJfaFR01amroqKikJyH0RdoAOqeWCx0BBMO7C5BUUMm5sHKvyoCLG4DwDgRc/6K5FrDgAAAABJRU5ErkJggg==',
			'0B6F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGUNDkMRYA0RaGR0dHZDViUwRaXRtQBULaBVpZQWagOy+qKVTw5ZOXRmaheQ+sDpHDL1A8wKx2IEqhs0tUDejiA1U+FERYnEfAL/KyWGjgEToAAAAAElFTkSuQmCC',
			'0679' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxBrC2MjQEBAQgiYlMEWlkaAh0EEESC2gF8hodYWJgJ0UtnRa2aumqqDAk9wW0irYyTGGYiqa30SEAaC6aHY4ODCh2gNzC2sCA4hawmxsYUNw8UOFHRYjFfQBUxstkyDYS9QAAAABJRU5ErkJggg==',
			'7653' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHUIdkEVbWVtZGxgdAlDERBpZgbQIstgUkQbWqQwNAcjui5oWtjQza2kWkvsYHURbQaqQzWNtEGl0AIogmycCFHNFEwtoYG1ldHREcUtAA2MIQygDqpsHKPyoCLG4DwAznsxh8cs1tAAAAABJRU5ErkJggg==',
			'B46C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYWhlCGaYGIIkFTGGYyujoECCCLAZUxdrg6MCCoo7RlbWB0QHZfaFRS5cunboyC9l9AVNEWlkdHR0YUMwTDXVtCEQTY2hlBYqh2sHQiu4WbG4eqPCjIsTiPgAJiswij5TdQwAAAABJRU5ErkJggg==',
			'BB2C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGaYGIIkFTBFpZXR0CBBBFmsVaXRtCHRgQVPHABRDdl9o1NSwVSszs5DdB1bXyujAgGaewxQsYgGMGHYAVaG4BeRm1tAAFDcPVPhREWJxHwCVXsxyWuBmcQAAAABJRU5ErkJggg==',
			'9CAD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMIY6IImJTGFtdAhldAhAEgtoFWlwdHR0EEETY20IhImBnTRt6rRVS1dFZk1Dch+rK4o6CATpDUUVEwCKuaKpA7kFJIbsFpCbgeahuHmgwo+KEIv7AAtyzDd/wiZUAAAAAElFTkSuQmCC',
			'77A2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMEx1QBZtZWh0CGUICEATc3R0dBBBFpvC0MraENAgguy+qFXTlgKJKCT3MTowBADVNSLbwQoUZQ0NaEV2iwhQFKhuCrJYAFg0IABTLDA0ZBCEHxUhFvcBAN5HzN1/QV16AAAAAElFTkSuQmCC',
			'FB26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGaY6IIkFNIi0Mjo6BASgijW6NgQ6CKCpYwCKIbsvNGpq2KqVmalZSO4Dq2tlxDDPYQqjgwi6WACGWCujAwOaXtEQ1tAAFDcPVPhREWJxHwALcszmoMCvYwAAAABJRU5ErkJggg==',
			'B097' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGUNDkMQCpjCGMDo6NIggi7WytrI2BKCKTRFpdAWKBSC5LzRq2srMzKiVWUjuA6lzCAloZUAxDygGlGFAs4OxISCAAcMtjg5Y3IwiNlDhR0WIxX0AS73M2APSxrkAAAAASUVORK5CYII=',
			'BB8A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGVqRxQKmiLQyOjpMdUAWaxVpdG0ICAjAUOfoIILkvtCoqWGrQldmTUNyH5o6JPMCQ0MwxVDVYdELcTMjithAhR8VIRb3AQB7LM0ZifAE9gAAAABJRU5ErkJggg==',
			'78F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDA6Y6IIu2srayNjAEBKCIiTS6NjA6CCCLTQGpY3RAcV/UyrCloStTs5Dcx+gAVodiHmsDxDwRJDERLGIBDZhuCWgAurmBAdXNAxR+VIRY3AcAdsbK7OCLEO8AAAAASUVORK5CYII=',
			'2CDC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDGaYGIImJTGFtdG10CBBBEgtoFWlwbQh0YEHWDRRjBYqhuG/atFVLV0VmobgvAEUdGDI6YIqxNmDaAVSF4ZbQUEw3D1T4URFicR8AEnTMFskfkk4AAAAASUVORK5CYII=',
			'23F3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANYQ1hDA0IdkMREpoi0sjYwOgQgiQW0MjS6guSQdbcyANUB5ZDdN21V2NLQVUuzkN0XgKIODBkdMM0DqsEQE2nAdEtoKNDNDQwobh6o8KMixOI+AHRny5RLXMJWAAAAAElFTkSuQmCC',
			'F090' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGVqRxQIaGEMYHR2mOqCIsbayNgQEBKCIiTS6NgQ6iCC5LzRq2srMzMisaUjuA6lzCIGrQ4g1oIuxtjJi2IHNLZhuHqjwoyLE4j4AGA7M+4TK2j4AAAAASUVORK5CYII=',
			'8B6B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUMdkMREpoi0Mjo6OgQgiQW0ijS6Njg6iKCpY21ghKkDO2lp1NSwpVNXhmYhuQ+sDqt5gSjmYRPD5hZsbh6o8KMixOI+AMUty+xO5n8eAAAAAElFTkSuQmCC',
			'86D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUIdkMREprC2sjY6OgQgiQW0ijSyNgQ0iKCoE2kAiQUguW9p1LSwpauilmYhuU9kimgrkjq4ea5o5mETw+YWbG4eqPCjIsTiPgBCj83zYiv4lAAAAABJRU5ErkJggg==',
			'99C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WAMYQxhCHaY6IImJTGFtZXQICAhAEgtoFWl0bRB0EMEQY4CpAztp2tSlS1NXrZqaheQ+VlfGQCR1ENjKANTLiGKeQCsLhh3Y3ILNzQMVflSEWNwHAFHYzC61GRpyAAAAAElFTkSuQmCC',
			'376F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RANEQx1CGUNDkMQCpjA0Ojo6OqCobGVodG1AE5vC0MrawAgTAztpZdSqaUunrgzNQnbfFIYAVgzzGB1YGwLRxFgb0MUCpog0MKLpFQ0QaWAIZUTVO0DhR0WIxX0A0ojJQhDOqakAAAAASUVORK5CYII=',
			'F25D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHUMdkMQCGlhbWRsYHQJQxEQaXYFiIihiDI2uU+FiYCeFRq1aujQzM2sakvuA6qYwNASi6w3AFGN0YMUQA7rE0RHNLaKhDqGMKG4eqPCjIsTiPgA2fMw0AKiF1QAAAABJRU5ErkJggg==',
			'5330' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNYQxhDGVqRxQIaRFpZGx2mOqCIMTQ6NAQEBCCJBQYA9TU6OogguS9s2qqwVVNXZk1Ddl8rijqYGNC8QBSxgFZMO0SmYLqFNQDTzQMVflSEWNwHAJKBzTOAXxFpAAAAAElFTkSuQmCC',
			'88AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQximMLQii4lMYW1lCGWY6oAkFtAq0ujo6BAQgKaOtSHQQQTJfUujVoYtXRWZNQ3JfWjq4Oa5hgaGhqCLoanDphfkZnSxgQo/KkIs7gMAkFnMloJ4uYIAAAAASUVORK5CYII=',
			'B1FC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgMYAlhDA6YGIIkFTGEMYG1gCBBBFmtlBYoxOrCgqGMAiyG7LzRqVdTS0JVZyO5DUwc1D7cYph2obgkFuhgohuLmgQo/KkIs7gMAC2zJn7FTEKAAAAAASUVORK5CYII=',
			'BB5F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHUNDkMQCpoi0sjYwOiCrC2gVaXRFFwOpmwoXAzspNGpq2NLMzNAsJPeB1DE0BGKY54BFzBVdDKiX0dERRQzkZoZQVLcMVPhREWJxHwBT28tlGu84PQAAAABJRU5ErkJggg==',
			'B726' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGaY6IIkFTGFodHR0CAhAFmtlaHRtCHQQQFXXygAUQ3ZfaNSqaatWZqZmIbkPqC6AoZURzTxGB4YpjA4iKGKsDQwBaGJTRBqAKlH0hgaINLCGBqC4eaDCj4oQi/sA++rMpOm4wuUAAAAASUVORK5CYII=',
			'4F74' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37poiGuoYGNAQgi4WIAMmARmQxRohYK7IY6xSgWKPDlAAk902bNjVs1dJVUVFI7gsAqZvC6ICsNzQUKBbAGBqC4haRBkYHBlS3AMVYG4gQG6jwox7E4j4ARg3NuKL3IwMAAAAASUVORK5CYII=',
			'66C7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCHUNDkMREprC2MjoENIggiQW0iDSyNgigigF5rGAa4b7IqGlhS1etWpmF5L6QKaKtQHWtyPYGtIo0ujYwTMEUEwhgwHBLoAMWN6OIDVT4URFicR8ABirL46lsh4kAAAAASUVORK5CYII=',
			'1DFA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDA1qRxVgdRFpZGximOiCJiTqINLo2MAQEoOgFiQFJJPetzJq2MjUUSCK5D00dslhoCG7zYGJAt6CKiYYA3YwmNlDhR0WIxX0ANArI1TK26EEAAAAASUVORK5CYII=',
			'0AC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHRoCkMRYAxhDGB0CGpHFRKawtrI2CLQiiwW0ijS6NjBMCUByX9TSaStTgVQUkvsg6oAmougVDQWKhYag2AFSJ4DmFpFGR6BOZDFGB5FGBzQ3D1T4URFicR8ACwDN/RzTEboAAAAASUVORK5CYII=',
			'4089' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEMIQyTHVAFgthDGF0dAgIQBJjDGFtZW0IdBBBEmOdItLo6OgIEwM7adq0aSuzQldFhSG5LwCszmEqst7QUJFG14aABhEUt4DsCHBAFcN0C1Y3D1T4UQ9icR8An0DLIqhg6vwAAAAASUVORK5CYII=',
			'8573' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA0IdkMREpogAyUCHACSxgFaQWECDCKq6EIZGh4YAJPctjZq6dBUQZiG5T2QKUNUUhgZU84BiAQwo5gHtaHR0YECzg7WVtYERxS2sAYwhrA0MKG4eqPCjIsTiPgDt381nBFNmpAAAAABJRU5ErkJggg==',
			'6B27' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUNDkMREpoi0Mjo6NIggiQW0iDS6NgSgijWItILIACT3RUZNDVu1MmtlFpL7QoDmMYAgst5WkUaHKQxTMMQCGAIY0N3iwOiA7mbW0EAUsYEKPypCLO4DAEUSy/senV37AAAAAElFTkSuQmCC',
			'9566' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGaY6IImJTBFpYHR0CAhAEgtoFWlgbXB0EEAVC2FtYHRAdt+0qVOXLp26MjULyX2srgyNro6OKOYxtALFGgIdRJDEBFpFMMREprC2oruFNYAxBN3NAxV+VIRY3AcAV8HLie/B8mUAAAAASUVORK5CYII=',
			'C97B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDA0MdkMREWllbGRoCHQKQxAIaRRodgGIiyGINQLFGR5g6sJOiVi1dmrV0ZWgWkvsCGhgDHaYwoprXwNDoEMCIal4jC9A0VDGQW1gbUPWC3dzAiOLmgQo/KkIs7gMAgpnML8dntKkAAAAASUVORK5CYII=',
			'3471' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RAMYWllDA1qRxQKmMEwFklNRVLYyhALFQlHEpjC6MjQ6wPSCnbQyaunSVSCI7L4pIq0MUxhaUc0TDXUIQBdjaGV0YEB3SytrA6oY2M0NDKEBgyD8qAixuA8An7rLl7APHmUAAAAASUVORK5CYII=',
			'42F3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37pjCGsIYGhDogi4WwtrI2MDoEIIkxhog0ugJpESQx1ikMYLEAJPdNm7Zq6dLQVUuzkNwXMIVhCitCHRiGhjIEsKKZB3SLA6YYawO6WximiIYC7UV180CFH/UgFvcBAMDTy9yhKwIuAAAAAElFTkSuQmCC',
			'ED1C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkNEQximMEwNQBILaBBpZQhhCBBBFWt0DGF0YEETc5jC6IDsvtCoaSuzgAjZfWjqCIqh2dEKdB+KW0BuZgx1QHHzQIUfFSEW9wEAwrrMuw0WxbUAAAAASUVORK5CYII=',
			'212B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjAGMDo6OgQgiQW0sgawNgQ6iCDrbgXqBYoFILtv2qqoVSszQ7OQ3Qeyo5URxTxGB6DYFEYU81jBKlHFgGygCKre0FBWIAxEcfNAhR8VIRb3AQDVYcfUH5fMHAAAAABJRU5ErkJggg==',
			'4174' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nM2QsQ2AMAwE7cJ7ZYQv4ibTOAUbRGxA4ylJOpNQgsDfnf6lk8mXM/pT3vFrBFEYIssMMtTIOMtgW2TSt1RTQ/Dbdy9+eCnBD6PXOMWtamdgzZMLJ7q6DD+bmejCvvrfc7nxOwFXnMtnNKbJZwAAAABJRU5ErkJggg==',
			'C78F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WENEQx1CGUNDkMREWhkaHR0dHZDVBTQyNLo2BKKKNTC0MiLUgZ0UtWrVtFWhK0OzkNwHVBfAiG5eA6MDK7p5jawN6GIirSIN6HpZQ0QaGEIZUcQGKvyoCLG4DwBsucm+IPqnigAAAABJRU5ErkJggg==',
			'50EC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHaYGIIkFNDCGsDYwBIigiLG2sjYwOrAgiQUGiDS6AsWQ3Rc2bdrK1NCVWSjua0VRh1MsoBXTDpEpmG5hDcB080CFHxUhFvcBAPdmykbIm3U8AAAAAElFTkSuQmCC',
			'BDE2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHaY6IIkFTBFpZW1gCAhAFmsVaXRtYHQQQVUHFGNoEEFyX2jUtJWpoatWRSG5D6qu0QHDPIZWBkyxKQxY3ILpZsfQkEEQflSEWNwHAMRhzjBcB3PlAAAAAElFTkSuQmCC',
			'3494' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RAMYWhlCGRoCkMQCpjBMZXR0aEQWA6libQhoRRGbwugKFJsSgOS+lVFLl67MjIqKQnbfFJFWhpBAB1TzREMdGgJDQ1DtaGUEugTNLa1At6CIYXPzQIUfFSEW9wEAUhfNKkJvZl4AAAAASUVORK5CYII=',
			'4820' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nGNYhQEaGAYTpI37pjCGMIQytKKIhbC2Mjo6THVAEmMMEWl0bQgICEASY53CCtQX6CCC5L5p01aGrVqZmTUNyX0BIHWtjDB1YBgaKtLoMAVVjGEKUCyAAcUOBqBeRgcGFLeA3MwaGoDq5oEKP+pBLO4DABxzy2alQwyEAAAAAElFTkSuQmCC',
			'50C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYAhhCHaY6IIkFNDCGMDoEBASgiLG2sjYIOoggiQUGiDS6NjDA1IGdFDZt2srUVaumZiG7rxVFHZIYI4p5Aa2YdohMwXQLawCmmwcq/KgIsbgPACCNy//EAxWRAAAAAElFTkSuQmCC',
			'391E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RAMYQximMIYGIIkFTGFtZQhhdEBR2SrS6IguNkWk0WEKXAzspJVRS5dmTVsZmoXsvimMgUjqoOYxNGKKsWCIgd2CJgZyM2OoI4qbByr8qAixuA8A2e7Jk9WMUZIAAAAASUVORK5CYII=',
			'B98C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGaYGIIkFTGFtZXR0CBBBFmsVaXRtCHRgQVEn0ujo6OiA7L7QqKVLs0JXZiG7L2AKYyCSOqh5DGDzUMVYsNiB6RZsbh6o8KMixOI+AMbHzKm35N17AAAAAElFTkSuQmCC',
			'AD45' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQxgaHUMDkMRYA0RaGVodHZDViUwRaXSYiioW0AoUC3R0dUByX9TSaSszMzOjopDcB1Ln2ujQIIKkNzQUKAa0VQTdvEZHBzSxVoZGh4AAFDGQmx2mOgyC8KMixOI+AM9ezfUDeaWvAAAAAElFTkSuQmCC',
			'35EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7RANEQ1lDHUMDkMQCpog0sDYwOqCobMUiNkUkBEkM7KSVUVOXLg1dGZqF7L4pDI2uGOZhExPBEAuYwtqKbq9oAGMIupsHKvyoCLG4DwCMqMlNOuIyYgAAAABJRU5ErkJggg==',
			'982E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMDkMREprC2Mjo6OiCrC2gVaXRtCEQTY21lQIiBnTRt6sqwVSszQ7OQ3MfqClTXyoiilwFonsMUVDEBkFgAqhjYLQ6oYiA3s4YGorh5oMKPihCL+wCU7Mksq/BK8gAAAABJRU5ErkJggg==',
			'B7C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QgNEQx1CHUMDkMQCpjA0OjoEOiCrC2hlaHRtEEQVm8LQytrA6OqA5L7QqFXTlq5aGRWF5D6gugBWIC2CYh6jA6YYawMr0A4UsSkiDYwOAQHI7gsNAKoIdZjqMAjCj4oQi/sA9w/M0wosBH4AAAAASUVORK5CYII=',
			'553E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkNEQxmBMABJLKBBpIG10dGBAU2MoSEQRSwwQCSEAaEO7KSwaVOXrpq6MjQL2X2tDI0OaOaBxdDMC2gVwRATmcLaiu4W1gDGEHQ3D1T4URFicR8AMEHLSyGliNIAAAAASUVORK5CYII=',
			'C2D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDGVqRxURaWVtZGx2mOiCJBTSKNLo2BAQEIIs1MADFAh1EkNwXtWrV0qWrIrOmIbkPqG4KK0IdTCwAQ6yR0YEVzQ6gWxrQ3cIaIhrqiubmgQo/KkIs7gMAtgfNYVZBHmYAAAAASUVORK5CYII=',
			'804B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHUMdkMREpjCGMLQ6OgQgiQW0srYyTHV0EEFRJ9LoEAhXB3bS0qhpKzMzM0OzkNwHUufaiG4eUCw0EMU8sB2N6HYA3YKmF5ubByr8qAixuA8ALfnMN9dg/CIAAAAASUVORK5CYII=',
			'BF33' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgNEQx1DGUIdkMQCpog0sDY6OgQgi7WKgMgGETR1DI0ODQFI7guNmhq2auqqpVlI7kNTh9s8HHaguyU0QKSBEc3NAxV+VIRY3AcAHePPUhjBF9YAAAAASUVORK5CYII=',
			'D96B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMdkMQCprC2Mjo6OgQgi7WKNLo2ODqIYIgxwtSBnRS1dOnS1KkrQ7OQ3BfQyhjoimEeA1BvIJp5LJhiWNyCzc0DFX5UhFjcBwCOo81Ig2V5pQAAAABJRU5ErkJggg==',
			'E699' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGaY6IIkFNLC2Mjo6BASgiIk0sjYEOoigijUgiYGdFBo1LWxlZlRUGJL7AhpEWxlCAqai6W10AJuAKubYEIBmB6ZbsLl5oMKPihCL+wD5H8zxIqFrYAAAAABJRU5ErkJggg==',
			'D2A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYQximMIaGIIkFTGFtZQhlaBBBFmsVaXR0dEATY2h0bQgAQoT7opauWrp0VdTKLCT3AdVNYQWRqHoDWEMDpqCKMToA1QUwoLqlgbUh0AHVzaKhrmhiAxV+VIRY3AcAudjOJLyZKE8AAAAASUVORK5CYII=',
			'22E4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nM3QIQ7AIAyF4YfoDdh9MPMV1HCaIrgB2Q0wnHLgGja5ZWvdlxD+FP0yij/tK33ELpIEZWO+UiFFtsbF511RrKFgWmXbd/TWpKdk+xiV1AX71gXwMIm2ZSjNv2zL0NVENtmX5q/u9+De9J2j8cyCHx+xiQAAAABJRU5ErkJggg==',
			'854F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxkaHUNDkMREpog0MLQ6OiCrC2gFik1FFQOqC2EIhIuBnbQ0aurSlZmZoVlI7hOZwtDo2ohuHlAsNBDdjkaHRnQ7WIEqUcVYAxhD0MUGKvyoCLG4DwDRgMsI4QcZtgAAAABJRU5ErkJggg==',
			'B80D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgMYQximMIY6IIkFTGFtZQhldAhAFmsVaXR0dHQQQVPH2hAIEwM7KTRqZdjSVZFZ05Dch6YObp4rFjFsdqC7BZubByr8qAixuA8ApvPMl2dGT1AAAAAASUVORK5CYII=',
			'DF6D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGUMdkMQCpog0MDo6OgQgi7WKNLA2ODqIYIgxwsTATopaOjVs6dSVWdOQ3AdW54hNbyBhMSxuCQ0AqkBz80CFHxUhFvcBAOAwzLy+ch03AAAAAElFTkSuQmCC',
			'5C22' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMYQxlCGaY6IIkFNLA2Ojo6BASgiIk0uDYEOoggiQUGiEBlEO4LmzZt1aqVWauikN3XClTRytCIbAdYbApQFNkOoJhDAFAUSUxkCtAtDgwByGKsAYyhrKGBoSGDIPyoCLG4DwAPAsycbkQc2gAAAABJRU5ErkJggg==',
			'28A7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQximMIaGIImJTGFtZQgF0khiAa0ijY6ODihiDK2srawNAUCI5L5pK8OWropamYXsvgCwulZkexkdRBpdQwOmoLilASjWEBCALCbSANIb6IAsFhrKGIIuNlDhR0WIxX0AAnHMDRpiGdYAAAAASUVORK5CYII=',
			'8D0B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WANEQximMIY6IImJTBFpZQhldAhAEgtoFWl0dHR0EEFV1+jaEAhTB3bS0qhpK1NXRYZmIbkPTR3cPJCYCGE7MNyCzc0DFX5UhFjcBwBwIcxqenS6lQAAAABJRU5ErkJggg==',
			'07BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQ11DGUMdkMRYAxgaXRsdHQKQxESmAMUaAh1EkMQCWhlaWRHqwE6KWrpq2tLQlaFZSO4DqgtgRTMvoJXRgRXNPJEprA3oYqwBIg3oehmBKljR3DxQ4UdFiMV9AIuSy5DyvC/wAAAAAElFTkSuQmCC',
			'412E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjAEMIQyhgYgi4UwBjA6Ojogq2MMYQ1gbQhEEWMF6UWIgZ00bdqqqFUrM0OzkNwXAFLXyoiiNzQUKDYFVQzslgBMMUYHdDHWUNbQQFQ3D1T4UQ9icR8AeHLG2W50PzAAAAAASUVORK5CYII=',
			'FB8B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAT0lEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGUMdkMQCGkRaGR0dHQJQxRpdGwIdRHCrAzspNGpq2KrQlaFZSO4jwTxCdkDFMN08UOFHRYjFfQDUI8zLP9IoAQAAAABJRU5ErkJggg==',
			'2641' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHVqRxUSmsLYytDpMRRYLaBVpZJjqEIqiu1WkgSEQrhfipmnTwlZmZi1FcV+AaCsrmh2MDiKNrqEBKGKsDSKNDuhuaQC6BU0sNBTs5tCAQRB+VIRY3AcASUrMZEuhEwYAAAAASUVORK5CYII=',
			'A9F5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0MDkMRYA1hbWYEyyOpEpog0uqKJBbSCxVwdkNwXtXTp0tTQlVFRSO4LaGUMdAWZgaQ3NJShEV0soJUFbAeqGMgtDAEBKGJANzcwTHUYBOFHRYjFfQAQgsuuw/peQwAAAABJRU5ErkJggg==',
			'FA27' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGUNDkMQCGhhDGB0dGkRQxFhbWYEkqphIowOQDEByX2jUtJVZIIjkPrC6VoZWBhS9oqEOUximMKCbFwB0D5qYowOjA7qYa2ggithAhR8VIRb3AQCTx81SbNAaewAAAABJRU5ErkJggg==',
			'5E46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNEQxkaHaY6IIkFNIg0MLQ6BASgi011dBBAEgsMAIoFOjoguy9s2tSwlZmZqVnI7msVaWBtdEQxDywWGugggmwHUIyh0RFFTGQKSAzVLawBmG4eqPCjIsTiPgCnR8x4Iqe8tgAAAABJRU5ErkJggg==',
			'2D17' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WANEQximMIaGIImJTBFpZQgB0khiAa0ijY5oYgxAMYcpQDlk902btjJr2qqVWcjuCwCra0W2l9EBLDYFxS0NYLEAZDGRBqBbpjA6IIuFhoqGMIY6oogNVPhREWJxHwCzAcujAuzw/wAAAABJRU5ErkJggg==',
			'7190' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGVpRRFsZAxgdHaY6oIixBrA2BAQEIItNYQCKBTqIILsvalXUyszIrGlI7mN0ANoRAlcHhqwNQLEGVDEgO4ARzY4AkBiaWwIaWEMx3DxA4UdFiMV9AJiSyXDuvh0GAAAAAElFTkSuQmCC',
			'44FC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjC0soYGTA1AFgthmMrawBAggiTGGMIQytrA6MCCJMY6hdEVJIbsvmnTli5dGroyC9l9AVNEWpHUgWFoqGioK5oY2C1odkDEUN0CFUN180CFH/UgFvcBABgTydB8p/3OAAAAAElFTkSuQmCC',
			'4866' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjCGMIQyTHVAFgthbWV0dAgIQBJjDBFpdG1wdBBAEmOdwtrK2sDogOy+adNWhi2dujI1C8l9ASB1jo4o5oWGgswLdBBBcQs2MUy3YHXzQIUf9SAW9wEAHtvLiPdwfCkAAAAASUVORK5CYII=',
			'97B7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM3QsRGAMAhAUVLQW8R9YpGeQpZwClKwgbqBhZlSSqKWehq6d9zxL1AvT+BP80ofUs+ZA4/O4gwllyTRGamZ0NkUbY9c37rUdeO6T64PM5DtaXNZQ0Kh2VunKGYETUsULENqm804NPbV/z04N30HzNHMPG4prHoAAAAASUVORK5CYII=',
			'0023' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUIdkMRYAxhDGB0dHQKQxESmsLayNgQ0iCCJBbSKNDoAxQKQ3Be1dNrKrJVZS7OQ3AdW18rQEICudwoDinkgO4CuQREDu8WBEcUtIDezhgaguHmgwo+KEIv7AD68y2VorcxmAAAAAElFTkSuQmCC',
			'254D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WANEQxkaHUMdkMREpog0MLQ6OgQgiQW0AsWmOjqIIOtuFQlhCISLQdw0berSlZmZWdOQ3RfA0OjaiKqX0QEoFhqIIsbaINLogKZOpIG1Feg+FLeEhjKGoLt5oMKPihCL+wCDI8uxWFiNNQAAAABJRU5ErkJggg==',
			'9163' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUIdkMREpjAGMDo6OgQgiQW0sgawNjg0iKCIMQDFgDSS+6ZNXRW1dOqqpVlI7mN1BapzdGhANo8BrDcAxTwBLGIiUxgw3AJ0SSi6mwcq/KgIsbgPAKy+yiVsZzuiAAAAAElFTkSuQmCC',
			'AC40' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YQxkaHVqRxVgDWIEiDlMdkMREpog0AEUCApDEAlpFGhgCHR1EkNwXtXTaqpWZmVnTkNwHUsfaCFcHhqGhQLHQQBQxkDqHRnQ7gG5pRHVLQCummwcq/KgIsbgPAGoKzkRBNqUGAAAAAElFTkSuQmCC',
			'5D39' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQxhDGaY6IIkFNIi0sjY6BASgijU6NAQ6iCCJBQYAxRodYWJgJ4VNm7Yya+qqqDBk97WC1DlMRdYLFgOZimwHRAzFDpEpmG5hDcB080CFHxUhFvcBAJ/6zhEpte5LAAAAAElFTkSuQmCC',
			'45A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsQ3AIAwETcEGZB9S0BsJN0zjFGzgsAENU4bSiJSJEn93elsnQ1+G4U95x082AgFGzZJjIDg0M4OZ3RfNrLhkGQWVX61naz3nrPxQ4Agcvd6lcT9QpDS5uNHD2UVssQszaWFf/e+53PhdvqTOoVGeC5QAAAAASUVORK5CYII=',
			'4A96' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjAEMIQyTHVAFgthDGF0dAgIQBJjDGFtZW0IdBBAEmOdItLoChRDdt+0adNWZmZGpmYhuS8AqM4hJBDFvNBQ0VAHoF4RFLeINDpiE0NzC0jMAd3NAxV+1INY3AcAqv/MNhEh9U8AAAAASUVORK5CYII=',
			'FFAA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMLQiiwU0iDQwhDJMdUATY3R0CAhAE2NtCHQQQXJfaNTUsKWrIrOmIbkPTR1CLDQwNAS3eSSJDVT4URFicR8Ay57NjLca6rYAAAAASUVORK5CYII=',
			'A110' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YAhimMLQii7EGMAYwhDBMdUASE5kCFA1hCAhAEgtoBelldBBBcl/U0lVRq6atzJqG5D40dWAYGoopBlGHzQ5UtwS0soYyhjqguHmgwo+KEIv7AL/iydKR7TMUAAAAAElFTkSuQmCC'        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "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";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>