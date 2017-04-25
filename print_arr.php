<?php

function print_arr($var, $line = '', $file = '', $method = '', $return = false)
{
  $type = gettype($var);
  $out = print_r($var, true);
  $out = htmlspecialchars($out);
  $out = str_replace(' ', '&nbsp; ', $out);
  if ($type == 'boolean')
  $content = $var ? 'true' : 'false';
  else
  $content = nl2br($out);
  if ($file || $line) {
  $dopinfo = '<a style="color:#ff9; text-decoration:none;" href="javascript://" onclick="this.innerHTML = \'' . addslashes($file) . ' : <font color=#88ffff>' . addslashes($method) . '</font> : \'">+</a> <span style="color:ff6;">' . $line . ' :</span> ';
  } else {
  $dopinfo = '';
  }
  $out = '<div style="
  border:2px inset #666;
  background:black;
  font-family:Verdana;
  font-size:14px;
  color:#6F6;
  text-align:left;
  margin:20px;
  padding:16px; padding-left:4px;">
  <span style="color: #F66">' . $dopinfo . '(' . $type . ')</span> ' . $content . '</div><br /><br />';

  if (!$return)
  echo $out;
  else
  return $out;
}

function print_die($var, $line = '', $file = '', $method = '', $return = false)
{
  print_arr($var, $line, $file, $method, $return); die;
}