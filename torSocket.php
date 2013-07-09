<?php

class TorSocket {

	private $ip;
	private $port;
	public $handler;

	public function __construct($ip, $port) {

		$this->ip = $ip;
		$this->port = $port;

		$this->socksbuffer($ip,$port);
	}

	public function close() {

		fclose($this->handler);

	}

	public function newHandler($ip, $port) {
		$this->close();
		$this->socksbuffer($ip, $port);
	}

	private function socksbuffer($ip,$port) {

		$this->handler = fsockopen("localhost", "9050",$errNo,$errStr,1);

		$ip = explode(".",$ip);

		$buffer = array(4,1,0,$port,intval($ip[0]),intval($ip[1]),intval($ip[2]),intval($ip[3]),"I","s","k","u","r","i",0x00);

		foreach($buffer as $byte) {

			if(is_int($byte)) $byte = chr($byte);

			fwrite($this->handler,$byte);
		}

		$point = 0;

		while ($point < 8) {

			$response = fread($this->handler,1);

			if($point == 1) {

				switch(ord($response)) {
					case 0x5b:
						throw new Exception("request rejected\n");
						break;
					case 0x5c:
						throw new Exception("request failed because client is not running identd\n");
						break;
					case 0x5d:
						throw new Exception("request failed because client's identd could not confirm the user ID string in the request\n");
						break;
				}

			}

			$point++;
		}

		return $this->handler;
	}


}
