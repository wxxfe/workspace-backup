<?php $this->load->view('common/header', $header_footer_data) ?>

<table class="table ranking-default">
    <thead>
        <tr>
            <th>排名</th>
            <th class="text-left">球队</th>
            <th>场次</th>
            <th>胜/平/负</th>
            <th>净胜球</th>
            <th>积分</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rankingData as $key => $team): ?>
            <tr>
                <?php if ($key < 3): ?>
                    <td width="10%" class="num text-red"><?= $key + 1 ?></td>
                <?php else: ?>
                    <td width="10%" class="num"><?= $key + 1 ?></td>
                <?php endif; ?>
                <td class="text-left">
                    <img class="barge" src="<?= getImageUrl($team['badge']) ?>" alt="<?= $team['name'] ?>"/>
                    <?= $team['name'] ?>
                </td>
                <td><?= $team['wins'] + $team['draws'] + $team['loses'] ?></td>
                <td><?= $team['wins'] ?>/<?= $team['draws'] ?>/<?= $team['loses'] ?></td>
                <td><?= $team['goals_differential'] ?></td>
                <td><?= $team['points'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->load->view('common/footer', $header_footer_data) ?>
