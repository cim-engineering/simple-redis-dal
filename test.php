<?php
include 'src/redis.php';

$db = new hooli("207.244.77.81", "regen28hur");
echo $db->ping();

?>