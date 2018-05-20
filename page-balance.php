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
					<h3>Available Balances</h3>
			
<?php
	$sendaddresses=array();
	$usableaddresses=array();
	$keymyaddresses=array();
	$keyusableassets=array();
	$haslocked=false;
	$getinfo=multichain_getinfo();
	$labels=array();
	
	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'send', implode(',', array_get_column($getaddresses, 'address')))
		))
			$sendaddresses=array_get_column($listpermissions, 'address');
			
		foreach ($getaddresses as $address)
			if ($address['ismine'])
				$keymyaddresses[$address['address']]=true;

		$labels=multichain_labels();

		if (no_displayed_error_result($listpermissions, multichain('listpermissions', 'receive')))
			$receiveaddresses=array_get_column($listpermissions, 'address');
		
		foreach ($sendaddresses as $address) {
			if (no_displayed_error_result($allbalances, multichain('getaddressbalances', $address, 0, true))) {

				if (count($allbalances)) {
					$assetunlocked=array();
					
					if (no_displayed_error_result($unlockedbalances, multichain('getaddressbalances', $address, 0, false))) {
						if (count($unlockedbalances))
							$usableaddresses[]=$address;
							
						foreach ($unlockedbalances as $balance)
							$assetunlocked[$balance['name']]=$balance['qty'];
					}
					
					$label=@$labels[$address];

?>
						<table class="table table-bordered table-condensed table-break-words <?php echo ($address==@$getnewaddress) ? 'bg-success' : 'table-striped'?>">
<?php
			if (isset($label)) {
?>
							<tr>
								<th style="width:25%;">Label</th>
								<td><?php echo html($label)?></td>
							</tr>
<?php
			}
?>
							<tr>
								<th style="width:20%;">Address</th>
								<td class="td-break-words small"><?php echo html($address)?></td>
							</tr>
<?php
					foreach ($allbalances as $balance) {
						$unlockedqty=floatval($assetunlocked[$balance['name']]);
						$lockedqty=$balance['qty']-$unlockedqty;
						
						if ($lockedqty>0)
							$haslocked=true;
						if ($unlockedqty>0)
							$keyusableassets[$balance['name']]=true;
?>
							<tr>
								<th><?php echo html($balance['name'])?></th>
								<td><?php echo html($unlockedqty)?><?php echo ($lockedqty>0) ? (' ('.$lockedqty.' locked)') : ''?></td>
							</tr>
<?php
					}
?>
						</table>
<?php
				}
			}
		}
	}
	
	if ($haslocked) {
?>
				<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
					<input class="btn btn-default" type="submit" name="unlockoutputs" value="Unlock all outputs">
				</form>
<?php
	}
?>
				</div>
				
				
			</div>