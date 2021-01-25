<?php

namespace Devlob\Utils;

trait Display
{
    /**
     * Store colors.
     *
     * @var array
     */
    private $colors
        = [
            'green'  => "\e[32m",
            'yellow' => "\e[33m",
            'red'    => "\e[91m"
        ];

    /**
     * Apply green color to text.
     *
     * @param string $text
     */
    public function green(string $text): void
    {
        $this->output($this->colors['green'], $text);
    }

    /**
     * Apply yellow color to text.
     *
     * @param string $text
     */
    public function yellow(string $text): void
    {
        $this->output($this->colors['yellow'], $text);
    }

    /**
     * Apply red color to text.
     *
     * @param string $text
     */
    public function red(string $text): void
    {
        $this->output($this->colors['red'], $text);
    }

    /**
     * Format output.
     *
     * @param string $color
     * @param string $text
     */
    private function output(string $color, string $text): void
    {
        echo "$color$text\e[0m";
    }
}