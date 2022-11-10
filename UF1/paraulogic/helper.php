<?php

const MIN_ANS = 7;
const MAX_LOOP = 50000;
const OLD_FUNCT= false;

const CARACTERS = "abcdefghijklmnopqrstuvwxyz_0123456789";
const DATE_FORMAT = "Y.m.d";

/**
 * Retorna la data d'avui
 *
 * @param string $format
 * @return string
 */
function today(string $format = DATE_FORMAT): string
{
    return date($format);
}

/**
 * Comprova si la data és vàlida
 *
 * @param string $date
 * @param string $format
 * @return bool
 */
function date_is_valid(string $date, string $format = DATE_FORMAT): bool
{
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}


/**
 * Comprova si una paraula forma part de la solució
 *
 * @param string $word
 * @return bool
 */
function word_is_valid(string $word): bool
{
    if (!isset($_SESSION["solucio"])) {
        return false;
    }

    return in_array($word, $_SESSION["solucio"]);
}

/**
 * Comprova si una paraula conté el caràcter del centre
 *
 * @param array $lletres
 * @param string $word
 * @return bool
 */
function has_center(array $lletres, string $word): bool
{
    $center = $lletres[3];
    return str_contains($word, $center);
}

/**
 * Retorna aquelles paraules que formen part de la solució
 *
 * @param array $lletres
 * @return array
 */
function filter_words(array $lletres): array
{
    $ret = array_filter(all_words(), function ($word) use ($lletres) {
        return strlen(str_replace($lletres, array_fill(0, count($lletres), ""), $word)) == 0;
    });;

    return array_filter($ret, function ($word) use ($lletres) {
        return has_center($lletres, $word);
    });
}

/**
 * retorna totes les paraules de PHP
 *
 * @return array
 */
function all_words(): array
{
    return get_defined_functions()["internal"];
}

/**
 * Crea una llavor de random a partir de la data en format Y.m.d
 * @param $date
 * @return void
 */
function set_seed_date($date): void
{
    list($y, $m, $d) = explode(".", $date);
    srand((int)$y * 365 * 12 + (int)$m * 12 + (int)$d);
}

/**
 * Crea una llista aleatòria de caràcters
 *
 * @param int $quantity
 * @param $caracters
 * @return array
 */
function crea_llista_caracters(int $quantity, $caracters = CARACTERS): array
{
    $llista = str_split($caracters, 1);
    shuffle($llista);
    return array_slice($llista, 0, $quantity);
}


/**
 * Retorna aquelles paraules que són possibles al phplogic
 *
 * @return array
 */
function all_unique_words() : array{
    $allwords = all_words();
    $allplayablewords = [];

    // Filtrem les paraules que poden jugar
    foreach ($allwords as $word) {
        $newchars = array_unique(str_split($word));
        if (count($newchars) <= 7) {
            $allplayablewords[] = $newchars;
            //echo implode("", $newchars). "\n";
        }
    }

    //echo count($allplayablewords). " ";
    return $allplayablewords;
}

/**
 * Escull una paraula aleatòria d'una llista de paraules
 *
 * @param array $words
 * @return array
 */
function pick_random_word(array $words): array
{
    $pick = rand(0, count($words) - 1);
    return $words[$pick];
}

/**
 * Crea un histograma dels caràcters d'una llista de paraules
 * L'histograma retorna un diccionari amb les repeticions de cada lletra.
 *
 * @param array $list_words
 * @return array
 */
function create_histogram(array $list_words): array
{
    $histogram = array_fill_keys(str_split(CARACTERS), 0);

    foreach ($list_words as $word){
        foreach ($word as $letter){
            ++$histogram[$letter];
        }
    }

    arsort($histogram);
    return $histogram;
}

/**
 * Funció d'utilitat per obtenir valors aleatoris amb ponderació.
 * Passeu en una matriu associativa, com ara array('A'=>5, 'B'=>45, 'C'=>50)
 * Una matriu com aquesta significa que "A" té un 5% de probabilitats de ser seleccionada, "B" 45% i "C" 50%.
 * El valor de retorn és la clau de matriu, A, B o C en aquest cas. Tingueu en compte que els valors assignats
 * no han de ser percentatges. Els valors són simplement relatius entre si. Si un valor
 * el pes era 2, i l'altre pes d'1, el valor amb el pes de 2 té aproximadament un 66%
 * possibilitat de ser seleccionat. Tingueu en compte també que els pesos han de ser enters.
 *
 * Font: https://stackoverflow.com/a/34346029
 *
 * @param array $weightedValues
 * @return string
 */
function escull_random_weighted(array $weightedValues) : string {
    $rand = mt_rand(1, (int) array_sum($weightedValues));

    foreach ($weightedValues as $key => $value) {
        $rand -= $value;
        if ($rand <= 0) {
            return $key;
        }
    }

    return "";
}

/**
 * DEPRECATED. Crea un nou hexàgon (7 lletres) que contingui MIN_ANS solucions.
 * Aquesta funció pot tenir un elevat cost computacional.
 * @param $date
 * @return array
 */
function crea_hexagon($date): array
{
    set_seed_date($date);
    $count = 0;

    do {
        $hexagon = crea_llista_caracters(7);
        $solucio = filter_words($hexagon);

        if($count++ > MAX_LOOP){
            die("Solució no trobada. MAX_ITERATION LOOP");
        }

    } while (count($solucio) < MIN_ANS);

    //echo "IT:" . $count . "\n";
    return [$hexagon, $solucio];
}

/**
 * Crea un nou hexàgon (7 lletres) que contingui MIN_ANS solucions de manera optimitzada.
 *
 * @param $date
 * @return array
 */
function crea_hexagon_op($date): array
{
    if(OLD_FUNCT) return crea_hexagon($date);

    set_seed_date($date);
    $allwords = all_unique_words();

    $histogram = create_histogram($allwords);
    $count = 0;

    do {
        $hexagon = pick_random_word($allwords);
        //echo "SELECTED:" . implode("", $hexagon). "\n";

        $missing = 7 - count($hexagon);
        //echo "MISSING:" . $missing . "\n";

        $extra = crea_llista_caracters_weigthed($histogram, $missing, $hexagon);

        //echo "EXTRA:" . implode("", $extra) . "\n";

        $hexagon = array_merge($hexagon, $extra);

        //echo "FINAL:" . implode("", $hexagon). "\n";

        $solucio = filter_words($hexagon);

        if($count++ > MAX_LOOP){
            die("Solució no trobada. MAX_ITERATION LOOP");
        }
    } while (count($solucio) < MIN_ANS);

    return [$hexagon, $solucio];
}

/**
 * Retorna una llista de caràcters aleatòria però amb probabilitats més altes per aquells caràcters amb més pes.
 *
 * @param array $histogram
 * @param int $times
 * @param array $skip
 * @return array
 */
function crea_llista_caracters_weigthed(array $histogram, int $times, array $skip): array
{
    if($times < 1) return [];

    $newchars = [];
    do{
        $val = escull_random_weighted($histogram);
        if (!in_array($val, $newchars) && !in_array($val, $skip))
            $newchars[] = $val;
    }
    while (count($newchars) < $times);

    return $newchars;
}