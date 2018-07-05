<?php
// list command
// use: getaddresses true
// get pubkey in server 1 and server 2 (trao doi voi nhau thong qua mang rieng, khong chia se duoc tren he thong?)
// use: addmultisigaddress 2 '[$pubkey1, $pubkey2]' ->return new address (address for multisign) (add in 2 server)
// use: importaddress $newaddrss '' false -> import 2 server
// create a multisign-only stream
// use: create stream $streamname false
// use: listpermissions $streamname.write --> see address 1..... -> return it $_address
// use: revoke $_address $streamname.imap_rfc822_write_address
// grant $newaddress $streamname.write
// use: createrawsendfrom $newaddress '{}' '[{"for":$streamname,"key":$key,"data":$data}]' sign

//in second server(server 2), paste form clipboard
// use: signrawtransaction [paste-hex-blob]

// The response should contain a complete field containing true, along with an even larger blob in the hex field. Copy the new blob, and run:
// use: sendrawtransaction [paste-bigger-hex-blob
// use: subscribe $streamname
// use: liststreamitems $streamname
// var_dump
no_displayed_error_result($getaddresses, multichain('getaddresses',true));
echo "Public Key: ";
// foreach($getaddresses as $address){
    echo $getaddresses[0]['pubkey'];
// }
$pubkey1 = "02ae81c9052d272a259757ed5d019c3c86622a8cef7ffc0b1aa4a6e9f2b48bdf79"; // server 1 
$pubkey2 = "033a82edf5ae59cf5bf5af588c6455bbf59c722b217520c1bf6ec6ccee47c01c2a"; //server 2

echo "<br>";
echo "Multisign address: ";
no_displayed_error_result($multisig_address, multichain('addmultisigaddress',2,[$pubkey1,$pubkey2]));
echo $multisig_address;

no_displayed_error_result($importaddress, multichain('importaddress', $multisig_address, '' ,false));
// var_dump($multisig_address);