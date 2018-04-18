<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [
    'Serie' => [
        ['list', '/list/{page}', 'GET'],
        ['selectSerie', '/pageSerie/{id:\d+}', 'GET'],
        ['search', '/searchResult', 'GET'],
        ['addView', '/admin', 'GET'],
        ['viewAfterAdd', '/serie/admin', ['GET','POST']],
    ],
    'Home' => [
        ['homePage', '/', 'GET'],
        ['aboutus', '/aboutus', 'GET']

    ],

];
