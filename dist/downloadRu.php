<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Download;
use App\Response;

$v = new Valitron\Validator($_POST);

$v->rule('required', ['name', 'position', 'company', 'email']);
$v->rule('email', 'email');
$v->rule('accepted', 'agreement');

$data = [
  'name' => $_POST['name'],
  'position' => $_POST['position'],
  'company' => $_POST['company'],
  'email' => $_POST['email'],
  'phone' => $_POST['phone'],
];

if ($v->validate()) {
  $download = new Download($data, 'ru');
  if ($download->send() === false) {
    (new Response('Failed to send', 500))->send();
  }

  (new Response('Sent successfully', 201))->send();
}

(new Response('Invalid data', 422))->send();
