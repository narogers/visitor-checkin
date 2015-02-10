<?php include("header.php"); ?>
<div role="main" class="ui-content formContent">
    <div id="localAddress" style="display:none">
       <div style="margin-left:10%"><h2>Please enter your local address.</h2></div>
        <fieldset style="text-align:center; margin:0 10%">
            <input type="text" name="localStreetAddress" id="localStreetAddress" placeholder="Street Address" autocomplete="off" required="required" />
        </fieldset>
        <fieldset class="ui-grid-d" style="text-align:center">
            <div class="ui-block-a" style="width:10%">&nbsp;</div>
            <div class="ui-block-b" style="width:50%">
                <input type="text" name="localCity" id="localCity" placeholder="City" autocomplete="off" required="required" />
            </div>
            <div class="ui-block-c" style="width:15%">
                <select name="localState" id="localState">
                <?php include("states.php"); ?>
                </select>
            </div>
            <div class="ui-block-d" style="width:15%">
                <input type="text" pattern="[0-9]*" name="localZip" id="localZip" placeholder="Zipcode" required="required" />
            </div>
            <div class="ui-block-e" style="width:10%">&nbsp;</div>
        </fieldset>
        <fieldset style="text-align:center; margin:0 10%">
            <input type="number" name="phone" id="phone" placeholder="Telephone" autocomplete="off"/>
        </fieldset>
        <div style="text-align:center; margin:0 35%"><input type="checkbox" name="homeDifferent" id="homeDifferent" data-mini="true" data-inline="true" data-theme="b" /><label for="homeDifferent">Home Address (if different from local address)</label></div>
    </div>
    <div>&nbsp;</div>
    <div id="homeAddress">
    <fieldset style="margin:0 10%">
        <legend><strong>Home address<br /><br /></strong></legend>
        <input type="text" name="homeStreetAddress" id="homeStreetAddress" placeholder="Street Address" />
    </fieldset>
    <fieldset class="ui-grid-d" style="text-align:center">
        <div class="ui-block-a" style="width:10%">&nbsp;</div>
        <div class="ui-block-b" style="width:50%">
            <input type="text" name="homeCity" id="homeCity" placeholder="City" />
        </div>
        <div class="ui-block-c" style="width:15%">
            <select  name="homeState" id="homeState" required="required">
                <?php include("states.php"); ?>
            </select>
        </div>
        <div class="ui-block-d" style="width:15%">
            <input type="text" pattern="[0-9]*" name="homeZip" id="homeZip" placeholder="Zipcode" />
        </div>
        <div class="ui-block-e" style="width:10%">&nbsp;</div>
    </fieldset>
    </div>
    <div id="staffFellow">
        <fieldset class="ui-grid-d" style="text-align:center">
            <div class="ui-block-a" style="width:10%">&nbsp;</div>
            <div class="ui-block-b" style="width:50%">
                <input type="text" name="department" id="department" placeholder="Department" required="required" />
            </div>
            <div class="ui-block-c" style="width:15%">
                <input type="text" name="title" id="title" placeholder="Title" required="required" />
            </div>
            <div class="ui-block-d" style="width:15%">
                <input type="text" name="extension" id="extension" placeholder="Extension" />
            </div>
            <div class="ui-block-e" style="width:10%">&nbsp;</div>
        </fieldset>
        <fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain" style="text-align:center">
            <input type="radio" name="fullTimeOrPartTime" id="fullTime1" value="fullTime" />
            <label for="fullTime1">Full Time</label>
            <input type="radio" name="fullTimeOrPartTime" id="fullTime2" value="partTime" />
            <label for="fullTime2">Part Time</label>
            <input type="checkbox" name="isTemporary" id="isTemporary" />
            <label for="isTemporary">Temporary</label>
        </fieldset>
        <div>&nbsp;</div>
    </div>
    <div id="intern">
        <fieldset class="ui-grid-d" style="text-align:center">
            <div class="ui-block-a" style="width:10%">&nbsp;</div>
            <div class="ui-block-b" style="width:40%">
                <input type="text" name="department" id="department" placeholder="Department" required="required" />
            </div>
            <div class="ui-block-c" style="width:40%">
                <input type="text" name="supervisor" id="supervisor" placeholder="Supervisor" required="required" />
            </div>
            <div class="ui-block-e" style="width:10%">&nbsp;</div>
        </fieldset>
    </div>
    <div id="idNumber">
        <fieldset class="ui-grid-d" style="text-align:center">
            <div class="ui-block-a" style="width:10%">&nbsp;</div>
            <div class="ui-block-b" style="width:40%">
                <input type="text" name="licenseNumber" id="licenseNumber" placeholder="Driver's License/State ID Number" required="required" />
                <input type="text" pattern="[0-9]*" name="cmaMemberID" id="cmaMemberID" placeholder="CMA Membership Number" required="required" />
            </div>
            <div class="ui-block-e" style="width:10%">&nbsp;</div>
        </fieldset>
    </div>
    <div id="temporaryDate">
        <fieldset style="margin:0 40%;">
            <label for="date"><strong>End Date:</strong></label>
            <input type="date" name="endDate" id="endDate" data-inline="true" value="<?php echo date("Y-m-d") ?>"  />
        </fieldset>
    </div>
    <div class="ui-grid-a">
        <div class="ui-block-a"><a data-role="button" data-inline="true" data-theme="b" data-rel="back" data-transition="slideright" data-icon="arrow-l">Back</a></div>
        <div class="ui-block-b" style="text-align:right"><button data-inline="true" data-theme="b" onclick="checkRequired('#reg3');" data-icon="arrow-r" data-iconpos="right">Continue</button></div>
    </div>
</div>
<?php include("footer.html"); ?>