<?php

class Read {
    public $file = "";

    public function __construct( $url ) {
        $this->file = file_get_contents( $url );
    }
}

class Parser {

    private $haystack = "";
    
    public $result;
    
    private $isTag = false;
    
    private $stackLength = 0;
    
    private $textPositions = array();
    
    public function __construct( $data ) {
        $this->haystack = $data;
        $this->stackLength = strlen( $this->haystack );
    }
    
    public function doIt() {
        $offset = 0;
        $tagOpen = '<';
        $i = 0;
        $qCounter = 0;
        while ( true ) {
            $position = strpos( $this->haystack, $tagOpen, $offset );
            if ( $position !== false ) {
                $this->isTag = true;
                if ( $position > 0 ) {
                    $this->textPositions[ $i ]['e'] = $position;
                }
                $i++;
                $offset = $position;
                $tagClose = '>';
                $position = strpos( $this->haystack, $tagClose, $offset );
                if ( $position !== false ) {
                    $this->isTag = false;
                    $this->textPositions[ $i ]['s'] = $position;
                }
            }
            else {
                echo "...";
                break;
            }
            $offset = $position;
        }
        
        foreach ( $this->textPositions as $position ) {
            if ( isset( $position['e'] ) === false ) {
                $position['e'] = $this->stackLength;
            }
            $startPosition   = $position['s'] + 1;
            $endPosition     = $position['e'];
            $textBlockLength = $position['e'] - $position['s'] - 1;
            
            // $foundTextBlock = substr( $this->haystack, $startPosition, $endPosition );
            // var_dump( $foundTextBlock );
            
            $quotePosition = strpos( $this->haystack, '"', $startPosition );
            if ( $quotePosition !== false && $quotePosition < $endPosition ) {
                $foundTextBlock = substr( $this->haystack, $startPosition, $textBlockLength );
                var_dump( $foundTextBlock );
            }
        }
        
        $this->result = $this->haystack;
    }
}

if ( isset( $_GET['f'] ) && $_GET['f'] != "" ) {
    $url = $_GET['f'];
}
else {
    $url = "http://192.168.0.211/verstka/files/smartcomplex/smartcomplex_publications_1.html";
}

$read = new Read( $url );

$parser = new Parser( $read->file );
$parser->doIt();

$result = $parser->result;
die;

var_dump( $result );