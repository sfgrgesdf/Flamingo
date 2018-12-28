<?php
declare(strict_types=1);

/**
 * This computes 10,000 iterations of the number of teams that can be fit in a 1800x1800 map at least 250 blocks apart
 * Each team is allotted 250 tries to find a suitable spot
 * These numbers have been chosen as they are the default values in the plugin's configuration
 */

class Vector2
{

	private $x;

	private $y;

	public function __construct(float $x, float $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	public function getX(): float
	{
		return $this->x;
	}

	public function getY(): float
	{
		return $this->y;
	}

	public function distance(Vector2 $other): float {
		return sqrt(($this->getX() - $other->getX())**2 + ($this->getY() - $other->getY())**2);
	}

}

$side = 1800;
$range = [-$side/2, $side/2];
$minDistance = 250; 
$spawns = [];

$respectsMinDistance = function (Vector2 $vector) use ($minDistance, &$spawns): bool {
	foreach ($spawns as $spawn) {
                if ($vector->distance($spawn) < $minDistance) {
                	return false;
                }
        }
        return true;
};

$spawnsFitted = [];
for ($i = 0; $i < 10000; ++$i) {
	$cnt = 0;
	while (true) {
		for ($j = 0; $j < 250; ++$j) {
			$vector = new Vector2(rand(...$range), rand(...$range));
			if ($respectsMinDistance($vector)) {
				$spawns[] = $vector;
				++$cnt;
				continue 2;
			}
		}
		break;
	}

	if (isset($spawnsFitted[$cnt])) {
		++$spawnsFitted[$cnt];
	} else {
		$spawnsFitted[$cnt] = 1;
	}

	$spawns = [];
}

ksort($spawnsFitted);
var_dump($spawnsFitted);
