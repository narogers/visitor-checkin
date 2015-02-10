<?php include("header.php"); ?>
    <div role="main" class="ui-content" id="homeContent">
        <fieldset class="ui-grid-a" style="margin-top:5%">
            <div class="ui-block-a">
                <div class="homeButton" data-role="button" data-theme="a" data-corners="false">
                    <div class="homeImage fullWidth">
                        <a href="#typeCheckin" >
                            <img src="/checkin/images/1942.638.png" alt="New visitors"/>
                        </a>
                    </div>
                    <div class="homeCaption">
                        <div style="margin:0 5%">
						<form data-ajax="false" action="/checkin/" method="POST">
                        <input type="text" name="nameOrID" class="subtitle" style="color:#1e2228" placeholder="Type your name or Member ID to check in."/>
						<center>
                        <div data-role="controlgroup" id="patronTypeA" data-type="horizontal" data-role="fieldcontain">
                        <!--<fieldset data-role="controlgroup" id="patronTypeA" data-type="horizontal" data-role="fieldcontain">-->
                            <?php foreach(array("patronType1"=>"Member","patronType2"=>"Academic","patronType3"=>"Docent","patronType4"=>"Volunteer") as $k => $v) {?>
                                <input type="radio" name="patronType" id="<?php echo $k ?>" value="<?php echo $v ?>" />
                                <label for="<?php echo $k ?>"  style="padding:6px; font-size:.7em"><?php echo $v ?></label>
                            <?php } ?>
                        <!--</fieldset>-->
                        </div>
                        <div data-role="controlgroup" id="patronTypeB" data-type="horizontal" data-role="fieldcontain">
                        <!--<fieldset data-role="controlgroup" id="patronTypeB" data-type="horizontal" data-role="fieldcontain">-->
                            <?php foreach(array("patronType5"=>"Intern","patronType6"=>"Public","patronType7"=>"Fellow","patronType8"=>"Staff") as $k => $v) {?>
                                <input type="radio" name="patronType" id="<?php echo $k ?>" value="<?php echo $v ?>" />
                                <label for="<?php echo $k ?>"  style="padding:6px; font-size:.7em"><?php echo $v ?></label>
                            <?php } ?>
                        <!--</fieldset>-->
                        </div>
						</center>
                        <input type="submit" name="submit" value="Checkin" data-theme="b" />
						</form>
						</div>
                    </div>
                </div>
            </div>
            <div class="ui-block-b">
                <div class="homeButton" data-role="button" data-theme="b" data-corners="false">
                    <div class="homeImage fullWidth">
                        <a href="p2spro://scan?formats=CODABAR&callback=javascript:alertBarcode('CODE','FORMAT')">
                            <img src="/checkin/images/1991.134.2.png" alt="Scan your CMA Badge"/>
                        </a>
                    </div>
                    <div class="homeCaption">
                        CMA BADGE HOLDERS<span class="subtitle">Scan the barcode on the back.</span>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
<?php include("footer.html"); ?>