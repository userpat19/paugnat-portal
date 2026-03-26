<?php

require_once '../app/controllers/EventsController.php';

$eventsController = new EventsController();
$eventsController->index();

?>