<?php

namespace PageHandlers;

class StartPageHandler extends PageHandler {

    public function handle() {
        $this->setPhpTemplate('start');
        return $this;
    }

    public function loginRequired() {
        return false;
    }

}
