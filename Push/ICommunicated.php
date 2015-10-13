<?php namespace Push;

interface ICommunicated {
    public function communicate($projectId,$data);
}