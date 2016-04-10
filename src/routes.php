<?php
// Routes

$app->get('/', function($request, $response, $args) {
    $this->logger->info('gettin all counts');
    $counter_mapper = new CounterMapper($this->db);
    $counters = $counter_mapper->getCounters();

    return $response->withJson($counters);
});

$app->get('/{name}', function($request, $response, $args) {
    $this->logger->info('getting counter with name: '.$args['name']);
    $counter_mapper = new CounterMapper($this->db);
    $counter = $counter_mapper->getCounterByName($args['name']);
    $this->logger->info(json_encode($counter));
    if($counter){
        $this->logger->info('found');
        return $response->withJson($counter);
    } else {
        $this->logger->info('not found');
        //$response->write('resource not found');
        return $response->withStatus(404);
    }
});

$app->put('/{name}/{password}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info('inserting new counter with name '. $args['name']);

    $counter_mapper = new CounterMapper($this->db);

    $counter = new CounterEntity(
        ['name' => $args['name'], 'password' => $args['password']]
    );

    if($counter_mapper->getCounterByName($counter->getName())){
        return $response->withJson(
            ['message' => 'counter with name '.$counter->getName().' already exists'],
            409
        );
    }else{
        $counter_mapper->insert($counter);
        return $response->withJson($counter, 201);
    }
});


$app->post('/{name}/{password}', function ($request, $response, $args) {
    $this->logger->info('post');

    //we assume everything is going to fail
    $return = ['message' => 'an error has occurred'];
    $code = 400;
    $counter_mapper = new CounterMapper($this->db);
    //var_dump($request);
    $data = $request->getParsedBody();
    // validate the array 
    if($data && isset($data['value'])){
        $counter = $counter_mapper->getCounterByCredentials($args['name'], $args['password']);
        if($counter){
            if($data['value'] === '+1'){
                $counter->value++;
                $counter_mapper->update($counter);
                return $response->withJson($counter);
            } else if(is_int($data['value'])){
                $counter->value = $data['value'];
                $counter_mapper->update($counter);
                return $response->withJson($counter);
            } else {
                $return['message'] = 'Not a valid value, it should be either an integer or a "+1" string';
            }
        }else{
            $return['message'] = 'The counter was not found, possibly due to bad credentials';
            $code = 404;
        }
    }
    return $response->withJson($return, $code);

});
