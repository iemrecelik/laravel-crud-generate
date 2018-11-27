<?php
return function(){

return '
    public function getLangs()
    {
        return Languages::all([\'lang_name\', \'lang_short_name\']);
    }
';
};