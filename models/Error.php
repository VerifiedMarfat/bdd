<?php

namespace Models;

class Error {

    public function get($query) {
        // TODO :: Log and handle all errors properly.
        $error = PHP_EOL;
        switch ( $query ) {
            case 'command':
                $error .= 'Incorrect command detected please try the following:' . PHP_EOL;
                $error .= PHP_EOL . 'php -f app.php offer:3for1 file:data.xml';
                break;
            default:
                $error .= 'Something went wrong!' . PHP_EOL;
                $error .= $query;
                break;
        }

        $error .= PHP_EOL;
        return $error;
    }

}

?>