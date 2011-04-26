<?php

class CoffeeGrinder
{
	protected $compiler;

	public function __construct($compilerCmd = 'java -jar jcoffeescript-1.0.jar')
	{
		$this->compiler = $compilerCmd;
	}

	public function parse($coffee)
	{
		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w')
		);

		$process = proc_open($this->compiler, $descriptorspec, $pipes);

		if (is_resource($process))
		{
			fwrite($pipes[0], $coffee);
			fclose($pipes[0]);

			$retval = stream_get_contents($pipes[1]);
			$errorval = stream_get_contents($pipes[2]);

			fclose($pipes[1]);
			fclose($pipes[2]);

			$proc_value = proc_close($process);

			if ($errorval != '')
				throw new Exception($errorval);

			return $retval;
		}
	}
}

