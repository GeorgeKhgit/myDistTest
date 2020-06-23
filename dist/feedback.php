<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Feedback;
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
  'comment' => $_POST['comment'],
];

if ($v->validate()) {
  $feedback = new Feedback($data);
  if ($feedback->send() === false) {
    (new Response('Failed to send', 500))->send();
  }

  $feedback->save();

  (new Response('Sent successfully', 201))->send();
}

(new Response('Invalid data', 422))->send();
