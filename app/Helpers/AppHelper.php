<?php

function append_data_from_serialize_array($model_obj, $data)
{
  for ($i = 0; $i < count($data); $i++) {
    $model_obj[$data[$i]["name"]] = $data[$i]["value"];
  }
}
