<?php
// Routes

// User routing
$app->get(   '/user',      "user.controller:indexJsonAction" );
$app->get(   '/user/{id}', "user.controller:indexJsonAction" );
$app->put(   '/user/{id}', "user.controller:updateJsonAction");
$app->post(  '/user',      "user.controller:createJsonAction");
$app->delete('/user/{id}', "user.controller:deleteJsonAction");

$app->get(  '/rpcserver',      "rpc.controller:serverJsonAction");


return $app;
