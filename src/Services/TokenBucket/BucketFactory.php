<?php

namespace App\Services\TokenBucket;

use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

class BucketFactory
{
    public static function createBucket(
        string $bucketName,
        int $maxTokens
    ): BlockingConsumer {
        $storage = new FileStorage(__DIR__ . '/' . $bucketName . '.bucket');
        $rate = new Rate($maxTokens, Rate::SECOND);
        $bucket = new TokenBucket($maxTokens, $rate, $storage);
        $consumer = new BlockingConsumer($bucket);
        $bucket->bootstrap($maxTokens);
        return $consumer;
    }
}
