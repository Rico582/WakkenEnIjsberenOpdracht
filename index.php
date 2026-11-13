<?php
require_once 'Play.php';   // klassen laden VOOR session_start
session_start();

if (isset($_POST['reset'])) {
    unset($_SESSION['game']);
}
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = new Game();
}
$game = $_SESSION['game'];
$melding = Play::handle($game, $_POST);
$turn = $game->getTurn();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wakken en IJsberen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1>Wakken, ijsberen en pinguins</h1>

    <?php if ($melding['text'] !== ''): ?>
        <div class="alert alert-<?= $melding['type'] ?>"><?= htmlspecialchars($melding['text']) ?></div>
    <?php endif; ?>

    <?php if ($turn !== null && $turn->getHint() !== null && !$turn->isLocked()): ?>
        <div class="alert alert-info">Hint: <?= htmlspecialchars($turn->getHint()) ?></div>
    <?php endif; ?>

    <?php if ($turn !== null): ?>
        <div><?php foreach ($turn->getCubes() as $c) { echo $c->getSvg(); } ?></div>
    <?php endif; ?>

    <?php if ($turn !== null && !$turn->isLocked()): ?>
        <!-- raadformulier: één keer raden per submit -->
        <form method="post" class="my-3">
            <label class="form-label">Raad Wakken</label>
            <input type="number" min="0" name="wakken" class="form-control mb-2" required>
            <label class="form-label">Raad Ijsberen</label>
            <input type="number" min="0" name="ijsberen" class="form-control mb-2" required>
            <label class="form-label">Raad Pinguins</label>
            <input type="number" min="0" name="pinguins" class="form-control mb-3" required>
            <button type="submit" name="raad" class="btn btn-primary">Raad</button>
            <button type="submit" name="oplossing" class="btn btn-primary" formnovalidate>Geef oplossing</button>
        </form>

        <h2>Huidige game</h2>
        <table class="table table-striped">
            <tr><th>Fout geraden</th></tr>
            <tr><td><?= $turn->getWrong() ?></td></tr>
        </table>
    <?php else: ?>
        <?php if ($turn !== null && $turn->isRevealed()): ?>
            <h2>Huidige game</h2>
            <table class="table table-striped">
                <tr><th>Aantal Wakken</th><th>Aantal Ijsberen</th><th>Aantal Pinguins</th></tr>
                <tr>
                    <td><?= $turn->countWakken() ?></td>
                    <td><?= $turn->countBears() ?></td>
                    <td><?= $turn->countPenguins() ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <!-- keuze aantal dobbelstenen + soort (senior) -->
        <form method="post" class="my-3">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Aantal dobbelstenen (3 t/m 8)</label>
                    <input type="number" name="aantal" min="3" max="8" class="form-control" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Kubus (6) of Pentagon (12) vorm</label>
                    <select name="soort" class="form-select">
                        <option value="6">Kubus (6)</option>
                        <option value="12">Pentagon (12)</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="gooi" class="btn btn-primary">Gooi Dobbelstenen</button>
        </form>
    <?php endif; ?>

    <h2>Statistieken</h2>
    <table class="table table-striped">
        <tr>
            <th>Spellen gespeeld</th>
            <th>Pogingen om te raden</th>
            <th>Keer goed geraden</th>
        </tr>
        <tr>
            <td><?= $game->getGamesPlayed() ?></td>
            <td><?= $game->getTotalAttempts() ?></td>
            <td><?= $game->getTotalCorrect() ?></td>
        </tr>
    </table>

    <?php if (count($game->getHistory()) > 0): ?>
        <h2>Vorige games</h2>
        <table class="table table-striped">
            <tr><th></th><th>Aantal beurten</th><th>Fout geraden</th></tr>
            <?php foreach ($game->getHistory() as $h): ?>
                <tr>
                    <td>Game</td>
                    <td><?= $h['beurten'] ?></td>
                    <td><?= $h['fout'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>Score: <?= $game->getScore() ?></p>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="reset" class="btn btn-outline-secondary btn-sm">Alles resetten</button>
    </form>
</div>
</body>
</html>