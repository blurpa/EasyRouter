<?php

namespace NickStuer\EasyRouter;

interface RouteInterface
{

    public function getHttpMethod();

    public function getPath();

    public function getAction();

}
