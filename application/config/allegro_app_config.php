<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// MAIL CONFIGURATION

$config['MailSecureType'] = 'tls';
$config['MailSMTPHost'] = 'smtp.gmail.com';
$config['MailPort'] = 587;
$config['MailSMTPLogin'] = '';
$config['MailSMTPPassword'] = '';
$config['MailFrom'] = '';
$config['MailFromName'] = 'Allegro Search Service';
$config['MailNoHTMLSupport'] = 'Brak możliwości odczytu wiadomości w formacie HTML';
$config['SaveToFile'] = false;

// ALLEGRO WEBAPI CONFIGURATION

$config['AllegroAPI'] = array(
	'url' => 'https://webapi.allegro.pl/service.php?wsdl',
	'component' => 1, // component AllegroWebAPI
	'country' => 228, // kod kraju, 1 - Polska, 228 - serwis testowy
	'webapikey' => '652d6c74', // klucz API
	'login' => 'testapipl',
	'passwd' => 'azns2013pl',
	'serviceurl' => 'http://testwebapi.pl/' // adres URL serwisu Allegro, który jest wykorzystywany
);

?>