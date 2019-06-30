<?php
  const API_URL = 'http://195.83.128.55/~mmi16f13/raspberry/index.php';

  exec('gpio mode 0 out');
  exec('gpio mode 3 down');

  api_call();

  function api_call() {
    do {
      $response = file_get_contents(API_URL.'?action=read');
      $responseData = json_decode($response);
  
      foreach($responseData as $key => $state) {
        if ('led' === $key) {
          if ($state !== getLedState()) {
            setLedState($state);
          }
        }
        
        if ('switch' === $key) {
          if ($state !== getSwitchState()) {
            // Define new state on the API
            file_get_contents(API_URL.'?action=write&name=switch&value='.(int) getSwitchState());
          }
        }
      }

      sleep(1);
    } while(true);
  }

  function setLedOn() {
    exec('gpio write 0 1');
  }
	
  function setLedOff() {
    exec('gpio write 0 0');
  }
  
  function setLedState(bool $state) {
    exec('gpio write 0 '. (int) $state);
  }

  function getSwitchState() {
    return (bool) exec('gpio read 3');
  }
	
  function getLedState() {
     return (bool) exec('gpio read 0');
  }
