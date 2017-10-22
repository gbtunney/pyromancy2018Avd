<?php require_once dirname(realpath(__FILE__)) . '/walkthrough/head.php'; ?>

<div class="container">
    <ul class="stage-progress-list">
        <?php $num_of_steps = 4;
        $step = (empty($_POST['step']) ? 0 : $_POST['step']);
        foreach (range(0, $num_of_steps) as $st): ?>
            <li class="stage-progress-item stage-progress-step">
                <a href="#" class="stage-progress-step-link">
                    <svg class="step-icon <?php if ($st <= $step) {echo 'completed';} ?>" viewBox="0 0 22 22" preserveAspectRatio="xMinYMin meet"$>
                        <circle r="11" cx="11" cy="11"></circle>
                    </svg>
                </a>
            </li>
            <?php if ($st != $num_of_steps): ?>
            <li class="stage-progress-item stage-progress-item-divider <?php if ($st + 1 <= $step) {echo 'completed';} ?>"></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <div class="cont">
        <form class="form" method='POST'>
            <?php foreach (range(0, $num_of_steps) as $st) { ?>
                <?php $fname = dirname(realpath(__FILE__)) . "/walkthrough/step_" . $st . ".php"; ?>
                <?php if (!file_exists($fname) || $st != $step) { continue; }  ?>
                <?php require_once $fname; ?>
            <?php } ?> 
        </form>
    </div>
</div>
