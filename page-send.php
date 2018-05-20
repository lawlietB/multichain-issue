<?php
	if (@$_POST['unlockoutputs'])
		if (no_displayed_error_result($result, multichain('lockunspent', true)))
			output_success_text('All outputs successfully unlocked');
	
	if (@$_POST['sendasset']) {
		if (strlen($_POST['metadata']))
			$success=no_displayed_error_result($sendtxid, multichain('sendwithmetadatafrom',
				$_POST['from'], $_POST['to'], array($_POST['asset'] => floatval($_POST['qty'])), bin2hex($_POST['metadata'])));
		else
			$success=no_displayed_error_result($sendtxid, multichain('sendassetfrom',
				$_POST['from'], $_POST['to'], $_POST['asset'], floatval($_POST['qty'])));
				
		if ($success)
			output_success_text('Asset successfully sent in transaction '.$sendtxid);
	}
?>

			<div class="row">		
				<div class="col-sm-12">
					<h3>Send Asset</h3>
					
					<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
						<div class="form-group">
							<label for="from" class="col-sm-3 control-label">From address:</label>
							<div class="col-sm-9">
							<select class="form-control" name="from" id="from">
<?php
	foreach ($usableaddresses as $address) {
?>
								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, true, $labels)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="asset" class="col-sm-3 control-label">Asset name:</label>
							<div class="col-sm-9">
							<select class="form-control" name="asset" id="asset">
<?php
	foreach ($keyusableassets as $asset => $dummy) {
?>
								<option value="<?php echo html($asset)?>"><?php echo html($asset)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="to" class="col-sm-3 control-label">To address:</label>
							<div class="col-sm-9">
							<select class="form-control" name="to" id="to">
<?php
	foreach ($receiveaddresses as $address) {
		if ($address==$getinfo['burnaddress'])
			continue;
?>
								<option value="<?php echo html($address)?>"><?php echo format_address_html($address, @$keymyaddresses[$address], $labels)?></option>
<?php
	}
?>						
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="qty" class="col-sm-3 control-label">Quantity:</label>
							<div class="col-sm-9">
								<input class="form-control" name="qty" id="qty" placeholder="0.0">
							</div>
						</div>
						<!--<div class="form-group">
							<label for="metadata" class="col-sm-3 control-label">Metadata:</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="3" name="metadata" id="metadata"></textarea>
							</div>
						</div>-->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<input class="btn btn-default" type="submit" name="sendasset" value="Send Asset">
							</div>
						</div>
					</form>

				</div>
			</div>