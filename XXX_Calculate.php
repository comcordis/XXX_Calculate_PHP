<?php

abstract class XXX_Calculate
{
	////////////////////
	// BMI
	////////////////////
	
	/*
	Body Mass Index / Quetelindex
	
	< 16.5 = Severely underweight/Anorexic
	16.5 - 18.5 = Underweight
	18.5 - 25 = Normal
	25 - 30 = Overweight
	30 - 35 = Obese Class 1
	35 - 40 = Obese Class 2
	40 - 45 = Severely Obese
	45 - 50 = Morbidly Obese
	50 - 60 = Super Obese
	60 > = Hyper Obese
	
	*/
	
	// kg, cm
	public static function getBMI ($weight = 0, $length = 0)
	{
		return $weight / (($length / 100) * ($length / 100));
	}
		
	////////////////////
	// Brassiere
	////////////////////
	
	/*
	Brassiere size
	
	Band size (under the breast)
	Bust size (around the fullest part of the breast)
	
	*/
		
	// cm, cm, ?
	public static function getBrassiereSize ($bandSize = 0, $bustSize = 0, $system = 'eu')
	{
		$result = false;
		
		$bandSize = XXX_Type::makeNumber($bandSize);
		$bustSize = XXX_Type::makeNumber($bustSize);
		
		$bandSize = XXX_UnitConverter::centimeterToInch($bandSize);
		$bustSize = XXX_UnitConverter::centimeterToInch($bustSize);
		
		switch ($system)
		{
			case 'usa':
				$cupVolumes = array('AA', 'A', 'B', 'C', 'DD/E', 'DDD/F', 'G', 'H', 'I', 'J', 'K');
				
				$bandSize += 5;
				
				if (XXX_Type::isUnevenNumber($bandSize))
				{
					$bandSize -= 1;	
				}
				
				$cup = XXX_Number::highest(XXX_Number::round($bustSize - $bandSize), 0);
				
				$result = array
				(
					'band' => XXX_Number::round($bandSize),
					'cupVolume' => $cupVolumes[$cup]
				);
				break;
			case 'uk':
				$cupVolumes = array('AA', 'A', 'B', 'C', 'D', 'DD', 'E', 'F', 'G', 'GG', 'H', 'HH', 'J');
				
				$bandSize += 5;
				
				if (XXX_Type::isUnevenNumber($bandSize))
				{
					$bandSize -= 1;	
				}
				
				$cup = XXX_Number::highest(XXX_Number::round($bustSize - $bandSize), 0);
				
				$result = array
				(
					'band' => XXX_Number::round($bandSize),
					'cupVolume' => $cupVolumes[$cup]
				);
				break;
			case 'aus':
				$cupVolumes = array('AA', 'A', 'B', 'C', 'D', 'DD', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
				
				$bandSize += 5;
				
				if (XXX_Type::isUnevenNumber($bandSize))
				{
					$bandSize -= 1;	
				}
				
				$cup = XXX_Number::highest(XXX_Number::round($bustSize - $bandSize), 0);
				
				$bandSize -= 22;
				
				$result = array
				(
					'band' => XXX_Number::round($bandSize),
					'cupVolume' => $cupVolumes[$cup]
				);
				break;
			case 'fr':
				$cupVolumes = array('AA', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
				
				$bandSize += 5;
				
				if (XXX_Type::isUnevenNumber($bandSize))
				{
					$bandSize -= 1;	
				}
				
				$cup = XXX_Number::highest(XXX_Number::round($bustSize - $bandSize), 0);
				
				$bandSize -= 30;				
				$bandSize = XXX_UnitConverter::inchToCentimeter($bandSize);
				$bandSize += 80;
				
				$result = array
				(
					'band' => XXX_Number::round($bandSize),
					'cupVolume' => $cupVolumes[$cup]
				);
				break;
			case 'eu':
				$cupVolumes = array('AA', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
				
				$bandSize += 5;
				
				if (XXX_Type::isUnevenNumber($bandSize))
				{
					$bandSize -= 1;
				}
				
				$cup = XXX_Number::highest(XXX_Number::round($bustSize - $bandSize), 0);
				
				$bandSize -= 30;
				$bandSize = XXX_UnitConverter::inchToCentimeter($bandSize);
				$bandSize += 65;
				
				$result = array
				(
					'band' => XXX_Number::round($bandSize),
					'cupVolume' => $cupVolumes[$cup]
				);
				break;
		}
		
		return $result;
	}	
		
	////////////////////
	// Distance
	////////////////////
	
	/*
	
	Based on Haversine formula
	
	http://www.movable-type.co.uk/scripts/gis-faq-5.1.html
	http://www.postcode.nl/index/151/1/0/berekening-latitude-longitude.html
	
	**Not taking into account elevation differences.** (So only usable as an indication)
	
	*/
	
	public static function getDistanceBetweenCoordinates ($coordinate1, $coordinate2)
	{
		// Kilometers
		$earthCirmcumference = 40075.16;
		
		// Convert degrees to radians
		$coordinate1['latitude'] = ($coordinate1['latitude'] * XXX_Number::pi()) / 180;
		$coordinate1['longitude'] = ($coordinate1['longitude'] * XXX_Number::pi()) / 180;
		$coordinate2['latitude'] = ($coordinate2['latitude'] * XXX_Number::pi()) / 180;
		$coordinate2['longitude'] = ($coordinate2['longitude'] * XXX_Number::pi()) / 180;
		
		$a = XXX_Number::power(XXX_Number::sine($coordinate2['latitude'] - $coordinate1['latitude'] / 2), 2);
		$b = XXX_Number::cosine($coordinate1['latitude']) * XXX_Number::cosine($coordinate2['latitude']);
		$b *= XXX_Number::power(XXX_Number::sine($coordinate2['longitude'] - $coordinate1['longitude'] / 2), 2);
		$a += $b;
		$c = 2 * XXX_Number::arcSine(XXX_Number::lowest(1, XXX_Number::squareRoot($a)));
		
		$earthRadius = ($earthCirmcumference / XXX_Number::pi()) / 2;
		
		$distance = $earthRadius * $c;
		
		return $distance;
	}
	
		
	////////////////////
	// Rectangle
	////////////////////
	
	/*
	
	The type parameter specifies whether the desired dimensions are the minimum or maximum area for the image to be scaled to. In the case of cropping the entire area needs to be filled to make it look things look uniform and pretty ;-).
	
	*/
	
	public static function getScaledRectangleSize ($originalWidth, $originalHeight, $desiredWidth = 0, $desiredHeight = 0, $type = 'minimum', $enlargeSmallerOriginal = false)
	{	
		$originalRatio = ($originalWidth / $originalHeight);
		$desiredRatio = ($desiredWidth / $desiredHeight);
		
		// What type of ratio is the original compared to desired, either "landscape" or "portrait"
		$moreLandscape = ($originalRatio > $desiredRatio) ? true : false;
		
		$wider = ($originalWidth > $desiredWidth);
		$higher = ($originalHeight > $desiredHeight);
		
		$scaleBy = 'none';
			
		// Bigger
		if ($wider && $higher)
		{
			if ($moreLandscape)
			{
				// Scale down by height
				if ($type == 'minimum')
				{
					$scaleBy = 'height';
				}
				// Scale down by width
				else
				{
					$scaleBy = 'width';
				}
			}
			else
			{
				// Scale down by width
				if ($type == 'minimum')
				{
					$scaleBy = 'width';
				}
				// Scale down by height
				else
				{
					$scaleBy = 'height';
				}
			}
		}
		// Overlapping by width
		elseif ($wider)
		{
			// Scale up by height
			if ($type == 'minimum')
			{
				$scaleBy = 'height';
			}
			// Scale down by width
			else
			{
				$scaleBy = 'width';
			}
		}
		// Overlapping by height
		elseif ($higher)
		{
			// Scale up by width
			if ($type == 'minimum')
			{
				$scaleBy = 'width';
			}
			// Scale down by height
			else
			{
				$scaleBy = 'height';
			}
		}
		// Smaller
		else
		{
			if ($type == 'minimum')
			{
				// Scale up by height
				if ($moreLandscape)
				{
					$scaleBy = 'height';
				}
				// Scale up by width
				else
				{
					$scaleBy = 'width';
				}
			}
			elseif ($enlargeSmallerOriginal)
			{
				// Scale up by width
				if ($moreLandscape)
				{
					$scaleBy = 'width';
				}
				// Scale up by height
				else
				{
					$scaleBy = 'height';
				}
			}
		}
		
		switch ($scaleBy)
		{
			case 'width':
				$newWidth = $desiredWidth;
				$newHeight = ($originalHeight * ($desiredWidth / $originalWidth));
				break;
			case 'height':
				$newWidth = ($originalWidth * ($desiredHeight / $originalHeight));
				$newHeight = $desiredHeight;
				break;
			default:
				$newWidth = $originalWidth;
				$newHeight = $originalHeight;
				break;
		}
		
		$result = array
		(
			'width' => XXX_Number::floor($newWidth),
			'height' => XXX_Number::floor($newHeight)
		);
		
		return $result;
	}
		
	////////////////////
	// Pagination
	////////////////////
		
	// First record = 0, First page = 1
	// Logic always start with 0, Humans always start with 1
	public static function getPaginationInformation ($recordTotal = 0, $page = 1, $recordsPerPage = 10, $centerSpan = 5, $edgeSpan = 3, $skipSpan = 10)
	{
		$recordTotal = XXX_Default::toPositiveInteger($recordTotal, 0);
		$page = XXX_Number::highest(XXX_Default::toPositiveInteger($page, 1), 1);
		$recordsPerPage = XXX_Number::highest(XXX_Default::toPositiveInteger($recordsPerPage, 10), 1);
		$centerSpan = XXX_Number::highest(XXX_Default::toPositiveInteger($centerSpan, 5), 1);
		$edgeSpan = XXX_Number::highest(XXX_Default::toPositiveInteger($edgeSpan, 3), 1);
		$skipSpan = XXX_Number::highest(XXX_Default::toPositiveInteger($skipSpan, 10), 1);
				
		$result = false;
		
		if ($recordTotal > 0)
		{
			$result = array
			(
				'records' => array
				(
					'offset' => 0,
					'start' => 0,
					'end' => 0,
					'total' => $recordTotal,
					'displayStart' => 0,
					'displayEnd' => 0,
					'displayTotal' => 0,
					'perPage' => $recordsPerPage
				),
				'pages' => array
				(
					'current' => $page,
					'total' => 1
				),
				'navigation' => array
				(
					'backward' => array
					(
						'first' => false,
						'skip' => false,
						'previous' => false,
						'edge' => false,
						'dots' => false,
						'center' => false
					),
					'current' => false,
					'forward' => array
					(
						'center' => false,
						'dots' => false,
						'edge' => false,
						'next' => false,
						'skip' => false,
						'last' => false
					)
				),
				'sqlLimitSuffix' => ''
			);
			
			// Pages
			$result['pages']['total'] = XXX_Number::ceil($result['records']['total'] / $result['records']['perPage']);
			
			if ($result['pages']['current'] > $result['pages']['total'])
			{
				$result['pages']['current'] = $result['pages']['total'];
			}
						
			// Records
			$result['records']['offset'] = ($result['pages']['current'] - 1) * $result['records']['perPage'];
			
			$result['records']['start'] = $result['records']['offset'];
			
			if ($result['pages']['current'] < $result['pages']['total'])
			{
				$result['records']['end'] = ($result['pages']['current'] * $result['records']['perPage']) - 1;
			}
			else
			{
				$result['records']['end'] = $result['records']['total'] - 1;
			}
			
			$result['records']['displayStart'] = $result['records']['start'] + 1;
			$result['records']['displayEnd'] = $result['records']['end'] + 1;
			$result['records']['displayTotal'] = ($result['records']['displayEnd'] - $result['records']['displayStart']) + 1;
			
			// Navigation
				
				// Backward
					
					// First
					if ($result['pages']['current'] > 1)
					{
						$result['navigation']['backward']['first'] = 1;
					}
					
					// Skip
					if ($result['pages']['current'] > $skipSpan)
					{
						$result['navigation']['backward']['skip'] = $result['pages']['current'] - $skipSpan;
					}
					
					// Previous
					if ($result['pages']['current'] > 1)
					{
						$result['navigation']['backward']['previous'] = $result['pages']['current'] - 1;
					}
					
					// Edge
					if ($result['pages']['current'] > $centerSpan + 1)
					{
						$available = ($result['pages']['current'] - 1)  - $centerSpan;						
						$available = XXX_Number::lowest($available, $edgeSpan);
						
						if ($available > 0)
						{
							$edgePages = array();
							
							for ($i = 1, $iEnd = $available; $i <= $iEnd; ++$i)
							{
								$edgePages[] = $i;
							}
							
							$result['navigation']['backward']['edge'] = $edgePages;
						}
					}
					
					// Dots
					if ($result['pages']['current'] > $edgeSpan + $centerSpan + 1)
					{
						$result['navigation']['backward']['dots'] = true;
					}
					
					// Center
					if ($result['pages']['current'] > 1)
					{
						$available = $result['pages']['current'] - 1;
						$available = XXX_Number::lowest($available, $centerSpan);
						
						if ($available > 0)
						{
							$centerPages = array();
							
							for ($i = $result['pages']['current'] - $available, $iEnd = $result['pages']['current'] - 1; $i <= $iEnd; ++$i)
							{
								$centerPages[] = $i;
							}
							
							$result['navigation']['backward']['center'] = $centerPages;
						}
					}
				
				// Current
				$result['navigation']['current'] = $result['pages']['current'];
				
				// Forward
					
					// Center
					if ($result['pages']['current'] < $result['pages']['total'])
					{
						$available = $result['pages']['total'] - $result['pages']['current'];
						$available = XXX_Number::lowest($available, $centerSpan);
						
						if ($available > 0)
						{
							$centerPages = array();
							
							for ($i = $result['pages']['current'] + 1, $iEnd = $result['pages']['current'] + $available; $i <= $iEnd; ++$i)
							{
								$centerPages[] = $i;
							}
							
							$result['navigation']['forward']['center'] = $centerPages;
						}
					}
					
					// Dots
					if ($result['pages']['current'] < $result['pages']['total'] - $edgeSpan - $centerSpan)
					{
						$result['navigation']['forward']['dots'] = true;
					}
					
					// Edge
					if ($result['pages']['current'] < ($result['pages']['total'] + 1) - $centerSpan)
					{
						$available = $result['pages']['total'] - $result['pages']['current'] - $centerSpan;						
						$available = XXX_Number::lowest($available, $edgeSpan);
						
						if ($available > 0)
						{
							$edgePages = array();
							
							for ($i = ($result['pages']['total'] + 1) - $available, $iEnd = $result['pages']['total']; $i <= $iEnd; ++$i)
							{
								$edgePages[] = $i;
							}
							
							$result['navigation']['forward']['edge'] = $edgePages;
						}
					}
					
					// Next
					if ($result['pages']['current'] < $result['pages']['total'])
					{
						$result['navigation']['forward']['next'] = $result['pages']['current'] + 1;
					}
					
					// Skip
					if ($result['pages']['current'] < $result['pages']['total'] - $skipSpan)
					{
						$result['navigation']['forward']['skip'] = $result['pages']['current'] + $skipSpan;
					}
					
					// Last
					if ($result['pages']['current'] < $result['pages']['total'])
					{
						$result['navigation']['forward']['last'] = $result['pages']['total'];
					}
			
			// SQL Limit Suffix
			$result['sqlLimitSuffix'] = ' LIMIT ' . $result['records']['offset'] . ', ' . $result['records']['perPage'];
		}
		
		return $result;
		
	}
	
	////////////////////
	// Basic Grid
	////////////////////
	
	public static function getBasicGridProperties ($cellTotal = 1, $columnTotal = 1)
	{
		if (!(XXX_Type::isPositiveInteger($cellTotal) && $cellTotal > 0))
		{
			$cellTotal = 1;
		}
		
		if (!(XXX_Type::isPositiveInteger($columnTotal) && $columnTotal > 0))
		{
			$columnTotal = 1;
		}
		
		if ($columnTotal > $cellTotal)
		{
			$columnTotal = $cellTotal;
		}
	
		$rowsPerColumn = XXX_Number::ceil($cellTotal / $columnTotal);
		
		$result = array
		(
			'columnTotal' => $columnTotal,
			'rowsPerColumn' => $rowsPerColumn,
			'cellTotal' => $cellTotal
		);
		
		return $result;
	}
}

?>