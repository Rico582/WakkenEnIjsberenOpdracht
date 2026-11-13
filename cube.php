<?php
// Eén dobbelsteen: 6 kanten (kubus) of 12 kanten (pentagon)
class Cube {
    private $sides;
    private $value = 1;

    public function __construct($sides = 6) {
        $this->sides = $sides;
    }

    public function roll() {
        $this->value = rand(1, $this->sides);
    }

    public function getValue() { return $this->value; }

    // onderkant (zuidpool): 7 - waarde bij 6 kanten, 13 - waarde bij 12 kanten
    public function getBottom() { return ($this->sides + 1) - $this->value; }

    // een wak zit in het midden bij oneven waarden
    public function hasWak() { return $this->value % 2 === 1; }

    // ijsberen: alle ogen behalve het wak in het midden
    public function getBears() { return $this->hasWak() ? $this->value - 1 : 0; }

    // pinguins: onderkant, alleen als er een wak is
    public function getPenguins() { return $this->hasWak() ? $this->getBottom() : 0; }

    public function getSvg() {
        return $this->sides === 12 ? $this->pentagonSvg() : $this->cubeSvg();
    }

    private function cubeSvg() {
        $v = $this->value;
        $pos = [
            1 => [],
            2 => [[70, 30], [30, 70]],
            3 => [[70, 30], [30, 70]],
            4 => [[30, 30], [70, 30], [30, 70], [70, 70]],
            5 => [[30, 30], [70, 30], [30, 70], [70, 70]],
            6 => [[30, 30], [70, 30], [30, 50], [70, 50], [30, 70], [70, 70]],
        ];
        $svg = "<svg width='110' height='110' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' style='margin:5px'>";
        $svg .= "<rect x='4' y='4' width='92' height='92' rx='15' fill='lightblue' stroke='black' stroke-width='5'/>";
        if ($this->hasWak()) {
            $svg .= "<circle cx='50' cy='50' r='9' fill='blue'/>";
        }
        foreach ($pos[$v] as $p) {
            $svg .= "<circle cx='{$p[0]}' cy='{$p[1]}' r='9' fill='white'/>";
        }
        return $svg . "</svg>";
    }

    private function pentagonSvg() {
        $svg = "<svg width='120' height='120' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' style='margin:5px'>";
        $svg .= "<polygon points='50,4 96,38 78,94 22,94 4,38' fill='lightblue' stroke='black' stroke-width='3'/>";
        $n = $this->hasWak() ? $this->value - 1 : $this->value;
        if ($this->hasWak()) {
            $svg .= "<circle cx='50' cy='55' r='4' fill='blue'/>";
        }
        for ($i = 0; $i < $n; $i++) {
            $hoek = (-90 + $i * (360 / $n)) * M_PI / 180;
            $x = round(50 + 32 * cos($hoek), 1);
            $y = round(55 + 32 * sin($hoek), 1);
            $svg .= "<circle cx='$x' cy='$y' r='3.5' fill='white'/>";
        }
        return $svg . "</svg>";
    }
}