<?php
require_once 'Turn.php';

// Houdt alles bij over alle spellen (statistieken + eerdere games)
class Game {
    private $turn = null;
    private $archived = true;
    private $history = [];
    private $gamesPlayed = 0;
    private $totalAttempts = 0;
    private $totalCorrect = 0;

    public function newTurn($count, $sides) {
        if ($count < 3 || $count > 8) return false;
        if ($sides != 6 && $sides != 12) return false;
        $this->archive();   // een onafgemaakte game gaat ook naar de lijst
        $this->turn = new Turn($count, $sides);
        $this->archived = false;
        $this->gamesPlayed++;
        return true;
    }

    public function guess($w, $b, $p) {
        if ($this->turn === null || $this->turn->isLocked()) return false;
        $ok = $this->turn->guess($w, $b, $p);
        $this->totalAttempts++;
        if ($ok) {
            $this->totalCorrect++;
            $this->archive();
        }
        return $ok;
    }

    public function reveal() {
        if ($this->turn === null) return;
        $this->turn->reveal();
        $this->archive();
    }

    private function archive() {
        if ($this->turn !== null && !$this->archived) {
            $this->history[] = [
                'beurten' => $this->turn->getAttempts(),
                'fout' => $this->turn->getWrong(),
                'goed' => $this->turn->isSolved(),
            ];
            $this->archived = true;
        }
    }

    public function getTurn() { return $this->turn; }
    public function getHistory() { return $this->history; }
    public function getGamesPlayed() { return $this->gamesPlayed; }
    public function getTotalAttempts() { return $this->totalAttempts; }
    public function getTotalCorrect() { return $this->totalCorrect; }

    public function getScore() {
        $s = 0;
        foreach ($this->history as $h) { if ($h['goed']) $s++; }
        return $s;
    }
}