<?php

    // on database success or whatever
      $return_arr["status"] = 'saved';
      $return_arr["message"] = utf8_encode("Success");

      echo json_encode($return_arr);
      exit();

?>