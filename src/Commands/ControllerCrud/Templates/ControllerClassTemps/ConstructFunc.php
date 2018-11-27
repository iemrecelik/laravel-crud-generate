<?php
return function(){

return '
    protected $locale;

    public function __construct()
    {
        $this->locale = \App::getLocale();
    }
';
};