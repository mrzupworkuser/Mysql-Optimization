<?php

namespace App;

class ShardResolver
{
    public static function getConnectionByUserId($userId)
    {
        switch ($userId % 3) {
            case 0:
                return 'mysql_shard_1';
            case 1:
                return 'mysql_shard_2';
            case 2:
                return 'mysql_shard_3';
            default:
                return 'mysql_shard_1';
        }
    }
}
