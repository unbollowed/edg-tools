<?php

define('WORD_MIN_LENGTH', 6);
define('WORD_MAX_LENGTH', 12);

function edg_crypto_array_rand($array) {
	$keys = array_keys($array);
	$randIndex = random_int(0, count($keys) - 1);

	return $keys[$randIndex];
}

function edg_crypto_array_shuffle(&$array) {
	$count = count($array);

	for ($i = $count - 1; $i > 0; $i--) {
		$j = random_int(0, $i);
		[$array[$i], $array[$j]] = [$array[$j], $array[$i]];
	}
}

function edg_smooth_word($existing = [], $min_length = WORD_MIN_LENGTH, $max_length = WORD_MAX_LENGTH) {
	$open_syllables = ['mo', 'la', 'ri', 'su', 'ne', 'ka', 'ti', 'pa', 'lo', 'me'];
	$closed_syllables = ['ram', 'bil', 'tag', 'kor', 'len', 'mir', 'das', 'pul', 'gar', 'fin'];
	$clusters = ['tr', 'gr', 'cr', 'pr', 'kl', 'bl', 'fl', 'dr', 'st', 'sk', 'qu'];
	$german_starters = ['sch', 'ch'];
	$german_enders = ['ck', 'ch', 'ss', 'll', 'mm'];
	$vowel_pairs = ['ie', 'ei', 'au', 'oo', 'uu', 'ai', 'ou'];
	$vowels = str_split('aeiou');
	$all_consonant_groups = [...$clusters, ...$german_starters];
	$forbidden_starts = ['ie', 'oo', 'uu'];
	$forbidden_parts = ['blau', 'blei', 'trau', 'klau', 'stau', 'frei', 'grin', 'trip', 'trap', 'skip', 'skin', 'fire', 'fail', 'dead', 'love', 'cool', 'luck', 'hell', 'weiss', 'blue', 'free', 'true', 'life', 'dark', 'lie', 'low'];
	$forbidden_transitions = ['dt', 'gk'];
	$max_tries = 1000;

	for ($try = 0; $try < $max_tries; $try++) {
		$word = '';
		$used_syllables = [];
		$syllable_count = random_int(3, 4);
		$word_is_valid = true;

		for ($i = 0; $i < $syllable_count; $i++) {
			$syll_is_valid = false;
			$syll_try = 0;

			while (!$syll_is_valid && $syll_try < 50) {
				$syll_try++;
				$type_rand = random_int(1, 100);

				$syll = match (true) {
					$type_rand <= 35 => $syll = $open_syllables[edg_crypto_array_rand($open_syllables)],
					$type_rand <= 65 => $syll = $closed_syllables[edg_crypto_array_rand($closed_syllables)],
					$type_rand <= 75 => $syll = $clusters[edg_crypto_array_rand($clusters)] . $vowel_pairs[edg_crypto_array_rand($vowel_pairs)],
					$type_rand <= 85 => $syll = $vowels[edg_crypto_array_rand($vowels)] . $german_enders[edg_crypto_array_rand($german_enders)],
					$type_rand <= 95 => $syll = $german_starters[edg_crypto_array_rand($german_starters)] . $vowels[edg_crypto_array_rand($vowels)],
					default => $syll = $vowel_pairs[edg_crypto_array_rand($vowel_pairs)]
				};

				$potential_word = mb_strtolower($word . $syll);
				$syll_is_valid = true;

				if (in_array($syll, $used_syllables)) {
					$syll_is_valid = false;
					continue;
				}
				
				if ($i === 0) {
					foreach ($forbidden_starts as $start) {
						if (str_starts_with($potential_word, $start)) {
							$syll_is_valid = false;
							continue 2;
						}
					}
				}

				preg_match('/[aeiou]{1,2}$/i', $word, $end_vowels);
				preg_match('/^[aeiou]+/i', $syll, $start_vowels);

				if (mb_strlen($end_vowels[0] ?? '') + mb_strlen($start_vowels[0] ?? '') >= 3) {
					$syll_is_valid = false;
					continue;
				}

				$last_part = mb_substr($word, -2);
				$syll_starts_with_cluster = false;

				foreach ($all_consonant_groups as $c) {
					if (str_starts_with($syll, $c)) {
						$syll_starts_with_cluster = true;
						break;
					}
				}

				if (in_array($last_part, $all_consonant_groups) && $syll_starts_with_cluster) {
					$syll_is_valid = false;
					continue;
				}

				if (!empty($word)) {
					$transition = mb_strtolower(mb_substr($word, -1) . mb_substr($syll, 0, 1));

					if (in_array($transition, $forbidden_transitions)) {
						$syll_is_valid = false;
						continue;
					}
				}

				foreach ($forbidden_parts as $bad) {
					if (str_contains($potential_word, $bad)) {
						$syll_is_valid = false;
						continue 2;
					}
				}
			}

			if (!$syll_is_valid) {
				$word_is_valid = false;
				break;
			}

			$word .= $syll;
			$used_syllables[] = $syll;

			if (mb_strlen($word) > $max_length) {
				break;
			}
		}

		if (!$word_is_valid) {
			continue;
		}

		$word = mb_strtolower($word);

		if (mb_strlen($word) < $min_length || mb_strlen($word) > $max_length) {
			continue;
		}

		if (preg_match('/[aeiou]{3,}/i', $word)) {
			continue;
		}

		$too_similar = false;

		foreach ($existing as $old) {
			similar_text($old, $word, $percentage);

			if ($percentage >= 30) {
				$too_similar = true;
				break;
			}
		}

		if ($too_similar) {
			continue;
		}

		return ucfirst($word);
	}

	return '[<<#ERR!>>]';
}

function edg_syllable_word($existing = [], $min_length = WORD_MIN_LENGTH, $max_length = WORD_MAX_LENGTH) {
	$syllables = ['la', 'ra', 'fu', 'mo', 'ri', 'fa', 'si', 'at', 'tri', 'ku', 'las', 'ren', 'ka', 'di', 'to', 'ba', 'co', 'le', 'na', 'mi', 'po', 'vo', 'ni', 'ze', 'zu', 'te', 'so', 'ju', 'lu', 'pa', 'li', 'do', 'gi', 'no', 'me', 'ki', 'sa', 'go', 'da', 're', 'yo', 'xa', 've', 'hu'];

	do {
		$used = [];
		$word = '';

		while (mb_strlen($word) < $min_length) {
			$syl = $syllables[edg_crypto_array_rand($syllables)];

			if (in_array($syl, $used)) {
				continue;
			}

			$used[] = $syl;
			$word .= $syl;
		}

		if (mb_strlen($word) > $max_length) {
			$word = mb_substr($word, 0, $max_length);
		}

		$word = ucfirst($word);
		$too_similar = false;

		foreach ($existing as $old) {
			similar_text($old, $word, $percentage);

			if ($percentage >= 30) {
				$too_similar = true;
				break;
			}
		}
	} while ($too_similar);

	return $word;
}

function edg_password() {
	$words = [];
	$types = ['A', 'B', 'A'];
	edg_crypto_array_shuffle($types);

	foreach ($types as $type) {
		switch ($type) {
			case 'A':
				$words[] = edg_syllable_word($words);
				break;
			default:
				$words[] = edg_smooth_word($words);
				break;
		}
	}

	if (count($words) === 3 && mb_strlen($words[0]) === mb_strlen($words[1]) && mb_strlen($words[1]) === mb_strlen($words[2])) {
		do {
			$words[2] = edg_smooth_word(array_slice($words, 0, 2));
		} while (mb_strlen($words[2]) === mb_strlen($words[0]));
	}

	$specials = ['!', '@', '#', '$', '%', '^', '&', '*'];
	$special = $specials[edg_crypto_array_rand($specials)];

	do {
		$digits = [random_int(0, 9), random_int(0, 9), random_int(0, 9)];
	} while (count(array_unique($digits)) === 1);

	$numbers = implode('', $digits);
	$password = implode('', $words) . $special . $numbers;

	return $password;
}

header('Content-Type: text/plain; charset=utf-8');
echo edg_password();
