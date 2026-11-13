<?php
require_once 'Game.php';

// Verwerkt de formulieren en geeft een melding terug voor het scherm
class Play {
    public static function handle(Game $game, array $post) {
        $result = ['type' => '', 'text' => ''];

        if (isset($post['gooi'])) {
            $count = (int)($post['aantal'] ?? 0);
            $sides = (int)($post['soort'] ?? 6);
            if (!$game->newTurn($count, $sides)) {
                $result = ['type' => 'danger', 'text' => 'Kies 3 tot en met 8 dobbelstenen.'];
            }
        } elseif (isset($post['oplossing'])) {
            $game->reveal();
        } elseif (isset($post['raad'])) {
            $turn = $game->getTurn();
            if ($turn === null || $turn->isLocked()) {
                $result = ['type' => 'warning', 'text' => 'Je kunt voor deze worp niet meer raden.'];
            } elseif (!is_numeric($post['wakken'] ?? '') || !is_numeric($post['ijsberen'] ?? '') || !is_numeric($post['pinguins'] ?? '')) {
                $result = ['type' => 'warning', 'text' => 'Vul alle drie de velden in met een getal.'];
            } elseif ($game->guess((int)$post['wakken'], (int)$post['ijsberen'], (int)$post['pinguins'])) {
                $result = ['type' => 'success', 'text' => 'correct geraden'];
            } else {
                $result = ['type' => 'danger', 'text' => 'helaas fout'];
            }
        }
        return $result;
    }
}