<?php include("header.php"); ?>
<div role="main" class="ui-content formContent">
   <div style="margin-left:10%"><h2>Please enter your name and select your patron type.</h2></div>
    <fieldset class="ui-grid-c" style="text-align:center">
        <div class="ui-block-a" style="width:5%">&nbsp;</div>
        <div class="ui-block-b" style="width:45%">
            <input type="text" name="lname" id="lname" placeholder="Last Name" autocomplete="off" required="required" />
        </div>

        <div class="ui-block-c" style="width:45%">
            <input type="text" name="fname" id="fname" placeholder="First Name" autocomplete="off" required="required" />
        </div>
        <div class="ui-block-d" style="width:5%">&nbsp;</div>
    </fieldset>
    <fieldset class="ui-grid-b">
        <div class="ui-block-a" style="width:5%">&nbsp;</div>
        <div class="ui-block-b" style="width:90%">
            <input type="email" name="email" id="email" placeholder="Email" autocomplete="off" required="required" />
        </div>
        <div class="ui-block-c" style="width:5%">&nbsp;</div>
    </fieldset>
    <fieldset data-role="controlgroup" id="patronType" data-type="horizontal" data-role="fieldcontain" style="margin-left:5%">
        <legend><strong>Patron Type:</strong></legend>
        <center>
		<?php 
		foreach (array("patronType1"=>"Member", "patronType2"=>"Academic", "patronType3"=>"Docent", "patronType4"=>"Volunteer", "patronType5"=>"Intern", "patronType6"=>"Public", "patronType7"=>"Fellow", "patronType8"=>"Staff") as $k => $v) {
		?>
        <input type="radio" name="patronType" id="<?php echo $k ?>" value="<?php echo $v ?>" />
        <label for="<?php echo $k ?>" style="padding:3px; font-size:.5em"><?php echo $v ?></label>
        <?php } ?>
		</center>
    </fieldset>
    <div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>
    <div class="ui-grid-a">
        <div class="ui-block-a"><a data-role="button" data-inline="true" data-theme="b" data-rel="back" data-transition="slideright" onclick="fullReset();" data-icon="arrow-l">Cancel</a></div>
        <div class="ui-block-b" style="text-align:right"><button data-inline="true" data-theme="b" onclick="checkRequired();" data-icon="arrow-r" data-iconpos="right">Continue</button></div>
    </div>
</div>
<?php include("footer.html"); ?>