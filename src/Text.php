<?php

namespace Webbtj\Clico;

/**
 * The Clico Text class allows for text decorating for the CLI stdOut.
 * 
 * Text and backgrounds can be coloured; text weight can be set as well as
 * Other effects such as blinking, highlighting, and underlining.
 */
class Text
{
    static $BACKGROUND_OPENED = 'background';
    static $BG_DARK = 40;
    static $BG_DEFAULT = 49;
    static $BG_LIGHT = 100;
    static $BLINK = 5;
    static $DARK_SHADE = 'dark';
    static $FG_DARK = 30;
    static $FG_DEFAULT = 39;
    static $FG_LIGHT = 90;
    static $FOREGROUND_OPENED = 'foreground';
    static $HIDDEN = 8;
    static $HIGHLIGHT = 7;
    static $LIGHT_SHADE = 'light';
    static $UNDERLINE = 4;
    static $WEIGHT_HEAVY = 1;
    static $WEIGHT_LIGHT = 2;
    static $WEIGHT_NORMAL = 0;

    private $background;
    private $blink;
    private $foreground;
    private $hidden;
    private $highlight;
    private $opened;
    private $repeat = 1;
    private $shade;
    private $string;
    private $underline;
    private $weight;

    /**
     * Constructor
     * 
     * Can optionally set the text on instantiation.
     *
     * @param String $string
     */
    public function __construct(String $string = null)
    {
        $this->opened = static::$FOREGROUND_OPENED;
        $this->shade = static::$DARK_SHADE;

        if($string){
            $this->text($string);
        }
    }

    /**
     * Renders decorated text
     *
     * @return string
     */
    public function __toString()
    {
        $codes = [];
        if(isset($this->weight)){
            $codes[] = $this->weight;
        }
        if(isset($this->foreground)){
            $codes[] = $this->foreground;
        }
        if(isset($this->background)){
            $codes[] = $this->background;
        }
        if($this->underline){
            $codes[] = static::$UNDERLINE;
        }
        if($this->blink){
            $codes[] = static::$BLINK;
        }
        if($this->highlight){
            $codes[] = static::$HIGHLIGHT;
        }
        if($this->hidden){
            $codes[] = static::$HIDDEN;
        }

        return sprintf(
            "\e[%sm%s\e[0m",
            implode(';', $codes),
            str_repeat($this->string, $this->repeat)
        );
        
    }

    /**
     * Alias of heavyWeight()
     *
     * @return Text
     */
    public function b()
    {
        return $this->heavyWeight();
    }

    /**
     * Affect the background
     * 
     * Direct future colour methods to affect the background colour.
     *
     * @return Text
     */
    public function background()
    {
        $this->opened = static::$BACKGROUND_OPENED;
        return $this;
    }

    /**
     * Set the colour to black
     * 
     * Set the colour (background or foreground) to black
     *
     * @return Text
     */
    public function black()
    {
        return $this->assignColour($this->colourMap()['black']);
    }

    /**
     * Make the text blink/flash
     *
     * @return Text
     */
    public function blink(){
        $this->blink = true;
        return $this;
    }

    /**
     * Set the colour to blue
     * 
     * Set the colour (background or foreground) to blue
     *
     * @return Text
     */
    public function blue()
    {
        return $this->assignColour($this->colourMap()['blue']);
    }

    /**
     * Alias of heavyWeight()
     *
     * @return Text
     */
    public function bold()
    {
        return $this->heavyWeight();
    }

    /**
     * Set the colour to cyan
     * 
     * Set the colour (background or foreground) to cyan
     *
     * @return Text
     */
    public function cyan()
    {
        return $this->assignColour($this->colourMap()['cyan']);
    }

    /**
     * Use dark colour variants
     * 
     * Make future colour assignments their dark variant
     *
     * @return Text
     */
    public function dark()
    {
        $this->shade = static::$DARK_SHADE;
        return $this;
    }

    /**
     * Make the last assigned colour dark
     * 
     * Make the most recent colour assignment (background/foreground) its dark variant.
     *
     * @return Text
     */
    public function darken()
    {
        if($this->opened == static::$FOREGROUND_OPENED){
            if($this->foreground >= static::$FG_LIGHT){
                $diff = static::$FG_LIGHT - static::$FG_DARK;
                $this->foreground -= $diff;
            }
        }elseif($this->opened == static::$BACKGROUND_OPENED){
            if($this->background >= static::$BG_LIGHT){
                $diff = static::$BG_LIGHT - static::$BG_DARK;
                $this->background -= $diff;
            }
        }
        return $this;
    }

    /**
     * Alias of lightWeight()
     *
     * @return Text
     */
    public function dim()
    {
        return $this->lightWeight();
    }

    /**
     * Alias of blink()
     *
     * @return Text
     */
    public function flash()
    {
        return $this->blink();
    }

    /**
     * Affect the foreground.
     * 
     * Direct future colour methods to affect the foreground colour.
     *
     * @return Text
     */
    public function foreground()
    {
        $this->opened = static::$FOREGROUND_OPENED;
        return $this;
    }
    
    /**
     * Get the undecorated text value
     *
     * @return string
     */
    public function getText()
    {
        return $this->string;
    }

    /**
     * Set the colour to green
     * 
     * Set the colour (background or foreground) to green
     *
     * @return Text
     */
    public function green()
    {
        return $this->assignColour($this->colourMap()['green']);
    }

    /**
     * Make the text bold
     *
     * @return Text
     */
    public function heavyWeight()
    {
        $this->weight = static::$WEIGHT_HEAVY;
        return $this;
    }

    /**
     * Make the text invisible
     *
     * @return Text
     */
    public function hidden()
    {
        $this->hidden = true;
        return $this;
    }

    /**
     * Alias of hidden()
     *
     * @return Text
     */
    public function hide()
    {
        return $this->hidden();
    }
    
    /**
     * Make the text highlighted
     * 
     * Make the text highlighted (invert background and foregroune colours)
     *
     * @return Text
     */
    public function highlight()
    {
        $this->highlight = true;
        return $this;
    }

    /**
     * Get the length of the undecorated string
     *
     * @return integer
     */
    public function length()
    {
        return strlen($this->string);
    }

    /**
     * Use light colour variants
     * 
     * Make future colour assignments their light variant
     *
     * @return Text
     */
    public function light()
    {
        $this->shade = static::$LIGHT_SHADE;
        return $this;
    }

    /**
     * Make the last assigned colour light
     * 
     * Make the most recent colour assignment (background/foreground) its light
     * variant.
     *
     * @return Text
     */
    public function lighten()
    {
        if($this->opened == static::$FOREGROUND_OPENED){
            if($this->foreground < static::$FG_LIGHT){
                $diff = static::$FG_LIGHT - static::$FG_DARK;
                $this->foreground += $diff;
            }
        }elseif($this->opened == static::$BACKGROUND_OPENED){
            if($this->background < static::$BG_LIGHT){
                $diff = static::$BG_LIGHT - static::$BG_DARK;
                $this->background += $diff;
            }
        }
        return $this;
    }

    /**
     * Make the text light weight/thin/dim
     *
     * @return Text
     */
    public function lightWeight()
    {
        $this->weight = static::$WEIGHT_LIGHT;
        return $this;
    }

    /**
     * Set the colour to magenta
     * 
     * Set the colour (background or foreground) to magenta
     *
     * @return Text
     */
    public function magenta()
    {
        return $this->assignColour($this->colourMap()['magenta']);
    }

    /**
     * Alias of normalWeight()
     *
     * @return Text
     */
    public function normal()
    {
        return $this->normalWeight();
    }

    /**
     * Make the text normal/default weight
     *
     * @return Text
     */
    public function normalWeight()
    {
        $this->weight = static::$WEIGHT_NORMAL;
        return $this;
    }

    /**
     * Experimental: Make the text multi-coloured
     *
     * @return string
     */
    public function rainbow()
    {
        $letters = str_split($this->string);
        $foregroundColours = array_keys($this->colourMap());
        $backgroundColours = array_reverse(array_keys($this->colourMap()));
        foreach($letters as &$letter){
            $letter = new Text($letter);
            call_user_func_array([$letter, current($foregroundColours)], []);
            $letter->background();
            call_user_func_array([$letter, current($backgroundColours)], []);
            next($foregroundColours) === false ? reset($foregroundColours) : null;
            next($backgroundColours) === false ? reset($backgroundColours) : null;
        }
        return implode('', $letters);
    }

    /**
     * Set the colour to red
     * 
     * Set the colour (background or foreground) to red
     *
     * @return Text
     */
    public function red()
    {
        return $this->assignColour($this->colourMap()['red']);
    }

    /**
     * Repeat the text property when rendering
     *
     * @param integer $repeat
     * @return Text
     */
    public function repeat(int $repeat)
    {
        $this->repeat = $repeat;
        return $this;
    }

    /**
     * Set the active colour property to its default.
     * 
     * Pass $setAll = true to set both the background and foreground colours to
     * their default.
     *
     * @param boolean $setAll
     * @return Text
     */
    public function setDefault($setAll = false)
    {
        if($this->opened == static::$FOREGROUND_OPENED || $setAll){
            $this->foreground = static::$FG_DEFAULT;
        }
        if($this->opened == static::$BACKGROUND_OPENED || $setAll){
            $this->background = static::$BG_DEFAULT;
        }
    }

    /**
     * Alias of heavyWeight()
     *
     * @return Text
     */
    public function strong()
    {
        return $this->heavyWeight();
    }

    /**
     * Experimental: Output a test pattern
     *
     * @param integer $width
     * @return string
     */
    public function testPattern($width = 10)
    {
        $output = [];
        for($i = 0; $i <= ($width * 3); $i++){
            foreach(array_keys($this->colourMap()) as $colour){
                $output[] = (new Text)->text(' ')->repeat($width)->background()->dark()->{$colour}();
                $output[] = (new Text)->text(' ')->repeat($width)->background()->light()->{$colour}();
            }
            $output[] = "\n";
        }
        return implode('', $output);
    }

    /**
     * Set the (undecorated) string property
     *
     * @param String $string
     * @return Text
     */
    public function text(String $string)
    {
        $this->string = $string;
        return $this;
    }

    /**
     * Alias of lightWeight()
     *
     * @return Text
     */
    public function thin()
    {
        return $this->lightWeight();
    }

    /**
     * Alias of underline()
     *
     * @return Text
     */
    public function u()
    {
        return $this->underline();
    }

    /**
     * Make the text underlined
     *
     * @return Text
     */
    public function underline()
    {
        $this->underline = true;
        return $this;
    }

    /**
     * Set the colour to white/grey
     * 
     * Set the colour (background or foreground) to white/grey
     *
     * @return Text
     */
    public function white()
    {
        return $this->assignColour($this->colourMap()['white']);
    }

    /**
     * Set the colour to yellow
     * 
     * Set the colour (background or foreground) to yellow
     *
     * @return Text
     */
    public function yellow()
    {
        return $this->assignColour($this->colourMap()['yellow']);
    }

    /**
     * Set the colour property
     *
     * @param integer $colour
     * @return Text
     */
    private function assignColour(int $colour)
    {
        $static = '';
        if($this->opened == static::$FOREGROUND_OPENED){
            $static .= 'FG_';
        }elseif($this->opened == static::$BACKGROUND_OPENED){
            $static .= 'BG_';
        }

        if($this->shade == static::$LIGHT_SHADE){
            $static .= 'LIGHT';
        }elseif($this->shade == static::$DARK_SHADE){
            $static .= 'DARK';
        }

        $this->{$this->opened} = static::${$static} + $colour;

        return $this;
    }

    /**
     * The index of available colours and their numeric values
     *
     * @return Array
     */
    private function colourMap()
    {
        return [
            'black' => 0,
            'red' => 1,
            'green' => 2,
            'yellow' => 3,
            'blue' => 4,
            'magenta' => 5,
            'cyan' => 6,
            'white' => 7,
        ];
    }

}