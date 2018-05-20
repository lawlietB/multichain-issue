			<div class="row">
					<h3>List block</h3>
<?php
	$getinfo=multichain_getinfo();
	$numblocks=0;
	if (is_array($getinfo)) {
		$numblocks=$getinfo['blocks'];
	}

	for($height = 0; $height <= $numblocks; $height++){
		if (no_displayed_error_result($block, multichain('getblock',(string)$height))) {
?>
						<div class="col-sm-6">
						<table class="table table-bordered table-condensed table-striped table-break-words"> 
							<tr style="background:lightblue">
								<th colspan="3">Block number</th>
								<td colspan="3"><?php echo html($block['height'])?></td>
							</tr>
							<tr>
								<th colspan="2">Merkle root</th>
								<td colspan="4"class="td-break-words small"><?php echo html($block['merkleroot'])?></td>
							</tr>
							<tr>
								<th colspan="2" >Miner</th>
								<td colspan="4"class="td-break-words small"><?php echo html($block['miner'])?></td>
							</tr>
							<tr>
								<th>Size</th>
								<td><?php echo html($block['size'])?></td>
								<th>Nonce</th>
								<td><?php echo html($block['nonce'])?></td>
								<th>Difficulty</th>
								<td><?php echo html($block['difficulty'])?></td>
							</tr>
							<tr>
								<th colspan="2">Block Hash</th>
								<td colspan="4" class="td-break-words small"><?php echo html($block['hash'])?></td>
							</tr>
							<tr>
								<th>Previous Block Hash</th>
								<td colspan="2" class="td-break-words small"><?php if($height > 0)echo html($block['previousblockhash'])?></td>
								<th>Next Block Hash</th>
								<td colspan="2"class="td-break-words small"><?php if($height < $numblocks)echo html($block['nextblockhash'])?></td>
							</tr>
							<tr>
								
							</tr>

					</table><br>
				</div>
<?php
		}		
	}	
?>
			
			</div>
