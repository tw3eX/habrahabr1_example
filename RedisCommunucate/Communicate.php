<?php namespace RedisCommunucate;

class Communicate implements \Push\ICommunicated {

    private $redisClient;

    public function __construct($redisClient){
        $this->redisClient = $redisClient;
    }

    public function communicate($projectId, $data)
    {
        $toSend = array(
            "project" => $projectId
        );
        $data['data'] = json_decode($data['data']);
        $toSend = array_merge($toSend, $data);
        $toSend['data'] = array($toSend['data']);
        $this->redisClient->rpush("centrifugo.api", json_encode($toSend));
    }
}