<?php
function generateToken()
{
    return md5(rand(1, 100) . microtime());
}
