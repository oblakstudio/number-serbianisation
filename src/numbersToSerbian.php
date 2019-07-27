<?php

namespace SeeBeen\SerbianPHP;

class NumbersToSerbianWords
{

	private static $digits = [
		0	 => 'nula',
		1	 => 'jedan',
		2	 => 'dva',
		3	 => 'tri',
		4	 => 'četiri',
		5	 => 'pet',
		6	 => 'šest',
		7	 => 'sedam',
		8	 => 'osam',
		9	 => 'devet',
		10	 => 'deset',
		11   => 'jedanaest',
		12   => 'dvanaest',
		13   => 'trinaest',
		14   => 'četrnaest',
		15   => 'petnaest',
		16   => 'šesnaest',
		17   => 'sedamnaest',
		18   => 'osamnaest',
		19   => 'devetnaest',
		40	 => 'četrdeset',
		50	 => 'pedeset',
		60	 => 'šezdeset',
		90	 => 'devedeset',
		100	 => 'sto',
		200	 => 'dvesta',
		300  => 'trista',
		1000 => 'hiljadu',
	];

	private $number;

	private $separated;

	private $string;


	public function __construct($number)
	{

		$this->number    = $number;
		$this->separated = [];
		$this->string    = '';

	}

	public function set_number($number)
	{

		$this->number    = $number;
		$this->separated = [];
		$this->string    = '';

	}

	public function to_letters()
	{

		$this->separate_digits($this->number);

		foreach ($this->separated as $index => $number) :
			$this->convert_sections($index,$number);
		endforeach;

		return str_replace('  ',' ',$this->string);
		

	}
	private function separate_digits($number)
	{

		$this->separated[] = ($number % 1000);
		
		$number = intdiv($number,1000);

		if ($number > 0)
			return $this->separate_digits($number);
		else
			return true;

	}

	private function convert_sections($index,$number)
	{

		$divisors = [
			100 => 'sto',
			10  => 'deset',
			1   => ''			
		];

		$last_digit = $number % 100;


		$section_string = '';

		foreach ($divisors as $divisor => $suffix) :

			if ($number == 0)
				break;

			if (array_key_exists($number,self::$digits)) :

				$section_string .= sprintf(
					'%s',
					self::$digits[$number]
				);
				break;

			endif;

			$remainder = $number % $divisor;
			$number    = $number - $remainder;

			if ($number > 0) :

				if (array_key_exists($number,self::$digits)) :

					$section_string .= sprintf(
						'%s ',
						self::$digits[$number]
					);

				else :

					$digit = intdiv($number,$divisor);

					$section_string .= sprintf(
						'%s%s ',
						self::$digits[$digit],
						$suffix
					);

				endif;

			endif;

			$number = $remainder;			

		endforeach;

		$section_string = $this->append_suffix($section_string, $index, $last_digit);

		if (in_array($index,[1,3])) :


			$section_string = str_replace('jedan ', 'jedna ', $section_string);
			$section_string = str_replace('dva ', 'dve ', $section_string);

		endif;

		$this->string = sprintf(
			'%s %s',
			$section_string,
			$this->string
		);

	}

	private function append_suffix($section_string, $section, $last_digit)
	{

		if($section == 3)
		var_dump($last_digit);

		if ($section == 0)
			return $section_string;

		$base = [
			1 => 'hiljad',
			2 => 'milion',
			3 => 'milijard',
			4 => 'bilion',
		];

		if (($last_digit % 10) == 1)
			$last_digit = 1;
		elseif  ( ($last_digit > 10) && (($last_digit % 10) <= 4) && (($last_digit % 10) > 1))
			$last_digit = $last_digit % 10;


		$letter_matrix = [
			1 => ['a','a','e','e','e','a'],
			2 => ['a','','a','a','a','a'],
			3 => ['i','a','e','e','e','i'],
			4 => ['a','','a','a','a','a'],
		];

		$last_digit = ($last_digit > 5) ? 5 : $last_digit;

		$suffix = $letter_matrix[$section][$last_digit];	

		return sprintf(
			'%s %s%s',
			$section_string,
			$base[$section],
			$suffix
		);

	}

}