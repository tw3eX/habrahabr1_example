<?php
namespace Push;

class Push{

    protected $projectSecret;
    private   $projectKey;
    private $communicate;

    public function __construct($project, $secret, ICommunicated $communicate )
    {
        $this->setProject($project, $secret)->setCommunicate($communicate);
    }

    private function setProject($projectKey, $projectSecret)
    {
        $this->projectKey = $projectKey;
        $this->projectSecret = $projectSecret;
        return $this;
    }

    public function setCommunicate(ICommunicated $communicate)
    {
        $this->communicate = $communicate;
        return $this;
    }

    public function publish($channel, $data = [])
    {
        return $this->send("publish", ["channel" => $channel, "data" => $data]);
    }

    public function send($method, $params = [])
    {
        if (empty($params)) {
            $params = new \StdClass();
        }
        $data = json_encode(["method" => $method, "params" => $params]);
        return
            $this->communicate
                ->communicate(
                    $this->projectKey,
                    ["data" => $data]
                );
    }


}