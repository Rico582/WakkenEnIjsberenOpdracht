<?php
require_once 'Cube.php';

// Eén worp met meerdere dobbelstenen + het raden daarvan
class Turn {
    private $cubes = [];
    private $wrong = 0;
    private $attempts = 0;
    private $solved = false;
    private $revealed = false;

    private $hints = [
        "Ijsberen zitten alleen om een wak, zodat ze voedsel kunnen krijgen.",
        "Pinguins zitten op de zuidpool als er op de noordpool een wak is.",
        "Een wak zit alleen op dobbelstenen met een oneven aantal ogen.",
    ];

    public function __construct($count, $sides) {
        for ($i = 0; $i < $count; $i++) {
            $cube = new Cube($sides);
            $cube->roll();
            $this->cubes[] = $cube;
        }
    }

    public function getCubes() { return $this->cubes; }

    public function countWakken() {
        $n = 0;
        foreach ($this->cubes as $c) { if ($c->hasWak()) $n++; }
        return $n;
    }

    public function countBears() {
        $n = 0;
        foreach ($this->cubes as $c) { $n += $c->getBears(); }
        return $n;
    }

    public function countPenguins() {
        $n = 0;
        foreach ($this->cubes as $c) { $n += $c->getPenguins(); }
        return $n;
    }

    public function isLocked() { return $this->solved || $this->revealed; }
    public function isSolved() { return $this->solved; }
    public function isRevealed() { return $this->revealed; }
    public function getWrong() { return $this->wrong; }
    public function getAttempts() { return $this->attempts; }

    public function guess($wakken, $bears, $penguins) {
        if ($this->isLocked()) return false;
        $this->attempts++;
        $ok = ($wakken == $this->countWakken()
            && $bears == $this->countBears()
            && $penguins == $this->countPenguins());
        if ($ok) { $this->solved = true; }
        else { $this->wrong++; }
        return $ok;
    }

    public function reveal() {
        if (!$this->solved) $this->revealed = true;
    }

    // na 3 keer fout krijg je een hint
    public function getHint() {
        if ($this->wrong < 3) return null;
        return $this->hints[($this->wrong - 3) % count($this->hints)];
    }
}