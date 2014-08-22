<?php

class Read {
    public $file = "";
    private $url = ""; 
    
    public function __construct( $url ) {
        $this->url = $url;
        $this->getHtmlFile();
    }
    
    public function getHtmlFile() {
        $this->file = file_get_contents( $this->url );
    }
}

if ( ! isset( $_GET['f'] ) || $_GET['f'] == "" ) {
    echo "WE NEED \"F!\"";
    die();
}

function isLatinChar( $char ) {
    $bool  = false;
    $latin = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
                    'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
                    's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A',
                    'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                    'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
                    'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2',
                    '3', '4', '5', '6', '7', '8', '9', '0', '#',
                    '.', '/', '-', '>', '<', ';', '%', '=', ']',
                    '[', ')', '(' );
    foreach ( $latin as $value ) {
        if ( $value == $char ) {
            $bool = true;
        }
    }
    return $bool;
}

function isPunct( $char ) {
    $bool  = false;
    $latin = array( ' ', ',', "\n", "\r", '.', '>', '<', '—', '(', ')' );
    foreach ( $latin as $value ) {
        if ( $value == $char ) {
            $bool = true;
        }
    }
    return $bool;
}

$url = $_GET['f'];

$read = new Read( $url );

$haystack = $read->file;
$needle   = '"';
$offset   = 1000;
$limit    = strlen( $haystack );
$counter  = 0;
while ( $offset < $limit ) {
    $pos = strpos( $haystack, $needle, $offset );
    
    if ( $pos !== false ) {
        $prevChar = $haystack[ $pos - 1 ];
        $nextChar = $haystack[ $pos + 1 ];
        if ( isPunct( $prevChar ) && ! isLatinChar( $nextChar ) ) {
//            var_dump( $prevChar );
//            print substr( $haystack, $pos - 10, 20 ) . "\n";
            // substr_replace( $haystack, "&laquo;", $pos, 1 );
            $left = substr( $haystack, 0, $pos );
            $right = substr( $haystack, $pos + 1);
            $haystack = $left . "&laquo;" . $right;
            $counter++;
        }
        elseif ( isPunct( $nextChar ) && !isLatinChar( $prevChar ) ) {
//            print substr( $haystack, $pos - 10, 20 ) . "\n";
            // var_dump( $nextChar );
            // substr_replace( $haystack, "&raquo;", $pos, 1 );
            $left = substr( $haystack, 0, $pos );
            // var_dump( $left );die;
            $right = substr( $haystack, $pos + 1);
            $haystack = $left . "&raquo;" . $right;
            $counter++;
        }
        $offset = $pos + 1;
    }
    else {
        if ( $limit != strlen( $haystack ) ) {
            print "ok";
        }
        else {
            var_dump( $haystack );
        }
        var_dump( $counter );
        die;
    }
}