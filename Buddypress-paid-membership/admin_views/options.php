<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>Paid Membership Settings</h2>
	<h3>Manage account types</h3>
	<form action="options-general.php?page=paid_membership_options" method="post">
		<input type="hidden" name="save" value="account_types" />
		<span class="description">Set the different account options for your Buddypress site here, and how much they cost.</span>
		<ul id="account-types">
			<? if(empty($this->prices)){ ?>
				<li>
					<label>
						Account type label
						<input type="text" name="account_type[0][label]" class="label" />
					</label>
					<label>
						Account type price
						<input type="text" name="account_type[0][price]" class="price" /> <span class="description">0 for free</span>
					</label>
					<a href="#" title="Remove" class="remove-replicator">remove</a>
				</li>
			<? }else{ ?>
				<? 
					$i = -1;
					
					foreach($this->prices as $type => $price){
						$i++; 
				?>
					<li>
						<label>
							Account type label
							<input type="text" name="account_type[<?=$i?>][label]" value="<?=$type?>" class="label" />
						</label>
						<label>
							Account type price
							<input type="text" name="account_type[<?=$i?>][price]" value="<?=$price?>" class="price" />
						</label>
						<a href="#" title="Remove" class="remove-replicator">remove</a>
					</li>
				<? } ?>
			<? } ?>
		</ul>
		<p><a href="#" class="button" id="replicator">+ Add another</a> <button type="submit" class="button-primary">Save</button></p>
	</form>
	<h3>Your Paypal Settings</h3>
	<form action="options-general.php?page=paid_membership_options" method="post">
		<input type="hidden" name="save" value="paypal" />
		<table class="form-table">
			<tbody>
				<tr valign="top" class="alt">
					<th scope="row">
						<label for="sig">API Signature</label>
					</th>
					<td>
						<input type="text" name="paypal[paypal_sig]" id="sig" value="<?=get_option('paypal_sig')?>" />
						<span class="description">You can get this from Paypal.com</span>
					</td>
				</tr>
				<tr valign="top" class="alt">
					<th scope="row">
						<label for="user">Username</label>
					</th>
					<td>
						<input type="text" name="paypal[paypal_user]" id="user" value="<?=get_option('paypal_user')?>" />
					</td>
				</tr>
				<tr valign="top" class="alt">
					<th scope="row">
						<label for="pass">Password</label>
					</th>
					<td>
						<input type="text" name="paypal[paypal_pass]" id="pass" value="<?=get_option('paypal_pass')?>" />
					</td>
				</tr>
				<tr valign="top" class="alt">
					<th scope="row">
						<label for="currency">Currency</label>
					</th>
					<td>
						<select name="paypal[paypal_currency]" id="currency">
							<? foreach($this->currencies as $cur_key => $cur_val){ ?>
								<option <? if($cur_key == get_option('paypal_currency')){ echo 'selected="selected"'; } ?> value="<?=$cur_key?>"><?=$cur_val?> <?=$cur_key?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="alt">
					<th scope="row">
						<label for="recurring">Recurring</label>
					</th>
					<td>
						<select name="paypal[paypal_recurring]" id="recurring">
							<option value="Month">Monthly</option>
							<option value="Year" <? if(get_option('paypal_recurring') == 'Year'){ echo 'selected="selected"'; }?>>Annually</option>
						</select>
					</td>
				</tr>
				<tr valign="top" class="alt">
					<th scope="row">
						Using Sandbox?
					</th>
					<td>
						<label for="sandbox_yes">
							Yes
							<input type="radio" name="paypal[paypal_sandbox]" id="sandbox_yes" value="yes" <? if(get_option('paypal_sandbox') == 'yes'){ echo 'checked="checked"'; }?> />
						</label>
						<label for="sandbox_no">
							No
							<input type="radio" name="paypal[paypal_sandbox]" id="sandbox_no" value="no" <? if(get_option('paypal_sandbox') == 'no'){ echo 'checked="checked"'; }?> />
						</label>
						<span class="description">Use the sandbox while testing your website</span>
					</td>
				</tr>
			</tbody>
		</table>
		<p><button type="submit" class="button-primary">Save</button></p>
	</form>
</div>