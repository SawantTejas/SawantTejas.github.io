<?php

$conn=mysqli_connect("localhost","root","");
mysqli_select_db($conn,"classroom");
if(!$conn)
{
  echo 'Not connected to server';
}
if(!mysqli_select_db($conn,'classroom'))
{
  echo 'Database not selected';
}
