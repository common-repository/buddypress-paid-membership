<h4>Payment details</h4>

<? do_action('bp_general_errors') ?>

<label for="field_account_type">Account Type</label>
<select name="field_account_type" id="field_account_type">
	<? foreach($this->prices as $price_key => $price_val){ ?>
		<option value="<?=$price_key?>"><?=$price_key?> &mdash; <?=$this->currency_symbol?><?=number_format($price_val, 2)?></option>
	<? } ?>
</select>
<? do_action('bp_card_name_first_errors') ?>							

<label for="card_name_first">First name</label>
<input type="text" name="card_name_first" id="card_name_first" value="<?=htmlspecialchars(@$_POST['card_name_first'])?>" />
<? do_action('bp_card_name_first_errors') ?>							

<label for="card_name_second">Last name</label>
<input type="text" name="card_name_second" id="card_name_second" value="<?=htmlspecialchars(@$_POST['card_name_second'])?>" />
<? do_action('bp_card_name_second_errors') ?>							

<label for="card_number">Card Number</label>
<input type="text" name="card_number" id="card_number" value="<?=htmlspecialchars(@$_POST['card_number'])?>" />
<? do_action('bp_card_number_errors') ?>

<label for="security">Security Code<span>(3 digit code)</span></label>
<input type="text" name="security" id="security" value="<?=htmlspecialchars($_POST['security'])?>"  />
<? do_action('bp_security_errors') ?>

<label for="cc_type">Credit card type</label>
<select name="cc_type">
	<option value="Visa">Visa</option>
	<option value="MasterCard">MasterCard</option>
	<option value="Discover">Discover</option>
	<option value="Amex">Amex</option>
</select>

<label for="ccExpMonth">Expires (month)</label>
<select name="ccExpMonth">
	<option value="01"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 01){ ?> selected="selected"<? } ?>>01 - January</option>
	<option value="02"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 02){ ?> selected="selected"<? } ?>>02 - February</option>
	<option value="03"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 03){ ?> selected="selected"<? } ?>>03 - March</option>
	<option value="04"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 04){ ?> selected="selected"<? } ?>>04 - April</option>
	<option value="05"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 05){ ?> selected="selected"<? } ?>>05 - May</option>
	<option value="06"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 06){ ?> selected="selected"<? } ?>>06 - June</option>
	<option value="07"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 07){ ?> selected="selected"<? } ?>>07 - July</option>
	<option value="08"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 08){ ?> selected="selected"<? } ?>>08 - August</option>
	<option value="09"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 09){ ?> selected="selected"<? } ?>>09 - September</option>
	<option value="10"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 10){ ?> selected="selected"<? } ?>>10 - October</option>
	<option value="11"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 11){ ?> selected="selected"<? } ?>>11 - November</option>
	<option value="12"<? if(!empty($_POST['ccExpMonth']) && $_POST['ccExpMonth'] == 12){ ?> selected="selected"<? } ?>>12 - December</option>	
</select>

<label for="ccExpYear">(year)</label>
<select name="ccExpYear">
	<option value="2009"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2009){ ?> selected="selected"<? } ?>>2009</option>
	<option value="2010"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2010){ ?> selected="selected"<? } ?>>2010</option>
	<option value="2011"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2011){ ?> selected="selected"<? } ?>>2011</option>
	<option value="2012"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2012){ ?> selected="selected"<? } ?>>2012</option>
	<option value="2013"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2013){ ?> selected="selected"<? } ?>>2013</option>
	<option value="2014"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2014){ ?> selected="selected"<? } ?>>2014</option>
	<option value="2015"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2015){ ?> selected="selected"<? } ?>>2015</option>
	<option value="2016"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2016){ ?> selected="selected"<? } ?>>2016</option>
	<option value="2017"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2017){ ?> selected="selected"<? } ?>>2017</option>
	<option value="2018"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2018){ ?> selected="selected"<? } ?>>2018</option>
	<option value="2019"<? if(!empty($_POST['ccExpYear']) && $_POST['ccExpYear'] == 2019){ ?> selected="selected"<? } ?>>2019</option>	
</select>

<label for="address1">Address</label>
<input type="text" name="address1" id="address1" value="<?=htmlspecialchars(@$_POST['address1'])?>"  />
<? do_action('bp_address1_errors') ?>

<label for="address2">Address (Line 2)</label>
<input type="text" name="address2" id="address2" value="<?=htmlspecialchars(@$_POST['address2'])?>"  />
<? do_action('bp_address2_errors') ?>

<label for="city">City</label>
<input type="text" name="city" id="city" value="<?=htmlspecialchars(@$_POST['city'])?>"  />
<? do_action('bp_city_errors') ?>

<label for="county">County</label>
<input type="text" name="county" id="county" value="<?=htmlspecialchars(@$_POST['county'])?>"  />
<? do_action('bp_county_errors') ?>

<label for="postcode">Post code</label>
<input type="text" name="postcode" id="postcode" value="<?=htmlspecialchars(@$_POST['postcode'])?>"  />
<? do_action('bp_postcode_errors') ?>