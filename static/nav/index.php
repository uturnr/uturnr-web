<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="UTF-8">
  <title>March Airport Practice</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
    <script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">
  <style type="text/css">
  body,td,th {
	font-family: Raleway, HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;
	color: #FFF;
}
body {
	background-color: #000;
}
h2 {
    text-align: center;
}
h2 {
    text-align: center;
}
img.centered {
    display: block;
    margin-left: auto;
    margin-right: auto 
	}
	a.centered {
    display: table;
    margin: auto;
	}
.one-half:hover .button-primary {
  background-color: #fafafa;
  border-color: #fafafa; }

      #feedbackarea {
          display: none;
      }
      
      .form_field {
          color: white;
      }
      
      input, textarea {
          color: black;
      }
      .phpfmg_form {
          list-style: none;
      }

  </style>
</head>
<body>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container">
      
    <div class="row">
      <div style="margin-top: 20%">
        <section class="header">
      <h2 class="title">March Airport Practice</h2></section>
      </div>
    </div>
        <div class="row">
      <div class="one-half column" style="margin-top: 5%">
        <a href="bearings/"><img class="centered" src="images/bearings.png" width="320" height="240" alt="Bearings"><a href="bearings/" class="centered button button-primary" style="margin-top: 10px">Bearings</a></a>
        </div>
      <div class="one-half column" style="margin-top: 5%">
        <a href="passingtraffic/"><img class="centered" src="images/passingtraffic.png" width="320" height="240" alt="Passing Traffic"><a href="passingtraffic/" class="centered button button-primary" style="margin-top: 10px">Passing Traffic</a></a>
      </div>
    </div>
    <div class="row">
      <div style="margin-top: 50%; margin-bottom: 5%;">
        <a class="centered button" onclick="toggle_visibility('feedbackarea');" href="#feedbackarea">Feedback</a>
        </div>
    </div>
      <div id="feedbackarea" class="row">
          
          
          
          
          
          
          
          
          
          
          
          
          

          
          
          
          
          
          
          <?php

// if the from is loaded from WordPress form loader plugin,
// the phpfmg_display_form() will be called by the loader
if( !defined('FormmailMakerFormLoader') ){
    # This block must be placed at the very top of page.
    # --------------------------------------------------
	require_once( dirname(__FILE__).'/form.lib.php' );
    phpfmg_display_form();
    # --------------------------------------------------
};


function phpfmg_form( $sErr = false ){
		$style=" class='form_text' ";

?>




<div id='frmFormMailContainer'>

<form name="frmFormMail" id="frmFormMail" target="submitToFrame" action='<?php echo PHPFMG_ADMIN_URL . '' ; ?>' method='post' enctype='multipart/form-data' onsubmit='return fmgHandler.onSubmit(this);'>

<input type='hidden' name='formmail_submit' value='Y'>
<input type='hidden' name='mod' value='ajax'>
<input type='hidden' name='func' value='submit'>

            
<ol class='phpfmg_form' >

<li class='field_block' id='field_0_div'><div class='col_label'>
	<label class='form_field'>Your email address</label>  </div>
	<div class='col_field'>
	<input type="text" name="field_0"  id="field_0" value="<?php  phpfmg_hsc("field_0", ""); ?>" class="u-full-width" placeholder="example@mailbox.com">
	<div id='field_0_tip' class='instruction'></div>
	</div>
</li>

<li class='field_block' id='field_1_div'><div class='col_label'>
	<label class='form_field'>Message</label> </label> </div>
	<div class='col_field'>
	<textarea name="field_1" id="field_1" rows=4 cols=25 class='u-full-width' placeholder="Type your message..."><?php  phpfmg_hsc("field_1"); ?></textarea>

	<div id='field_1_tip' class='instruction'></div>
	</div>
</li>


<li class='field_block' id='phpfmg_captcha_div'>
	<div class='col_label'></div><div class='col_field'>
	<?php phpfmg_show_captcha(); ?>
	</div>
</li>


            <li>
            <div class='col_label'>&nbsp;</div>
            <div class='form_submit_block col_field'>
	

                <input type='submit' value='Submit' class="button-primary">

				<div id='err_required' class="form_error" style='display:none;'>
				    <label class='form_error_title'>Please check the required fields</label>
				</div>
				

<div>
                <span id='phpfmg_processing' style='display:none;'>
                    <img id='phpfmg_processing_gif' src='<?php echo PHPFMG_ADMIN_URL . '?mod=image&amp;func=processing' ;?>' border=0 alt='Processing...'> <label id='phpfmg_processing_dots'></label>
                </span>
    </div>
            </div>
            </li>

</ol>
</form>

<iframe name="submitToFrame" id="submitToFrame" src="javascript:false" style="position:absolute;top:-10000px;left:-10000px;" /></iframe>

</div>
<!-- end of form container -->


<!-- [Your confirmation message goes here] -->
<div id='thank_you_msg' style='display:none;'>
Your message has been sent. Thank you!
</div>


            






<?php

    phpfmg_javascript($sErr);

}
# end of form




function phpfmg_form_css(){
    $formOnly = isset($GLOBALS['formOnly']) && true === $GLOBALS['formOnly'];
?>



<?php
}
# end of css
 
# By: formmail-maker.com
?>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
      </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>



