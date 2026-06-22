<div class="container projects">
    <h1 style="margin-top: 0px">Aktive Projekte</h1>
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($customers as $customer): ?>
                <table class="table table-bordered table-hover table-condensed">
                    <colgroup>
                        <col width="60%">
                        <col width="12%">
                        <col width="12%">
                        <col width="4%">
                        <col width="12%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?php echo $customer; ?></th>
                        <th style="text-align: right;">Schätzung</th>
                        <th style="text-align: right;">Aktuell</th>
                        <th style="border-left: none;">&nbsp;</th>
                        <th style="text-align: right;">Rest / Überhang</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($projects as $project):
                        if ($project['customer'] !== $customer):
                            continue;
                        endif;

                        $style   = '';
                        $percent = 100 / $project['estimation'] * $project['duration'];

                        if ($project['duration'] > $project['estimation']):
                            $style = 'danger';
                        elseif (round($percent) >= 90):
                            $style = 'warning';
                        endif;

                        if ($project['ts_type'] == 'JIRA' && $project['ts_prefix']):
                            $linkStart = '<a href="'
                                . hs($project['ts_url'] . '/browse/' . $project['ts_prefix'])
                                . '">';
                            $linkEnd = '</a>';
                        else:
                            $linkStart = $linkEnd = '';
                        endif;
                        ?>

                        <tr class="<?php echo $style; ?>">
                            <td>
                                <?php echo $linkStart . hs($project['project']) . $linkEnd; ?>
                                <a class="single-project" href="?project=<?= $project['project_id'] ?>">#<?= $project['project_id'] ?></a>
                            </td>
                            <td class="r" title="<?php echo formatTimeDays($project['estimation']);?>"><?php echo formatTime($project['estimation']); ?></td>
                            <td class="r" title="<?php echo formatTimeDays($project['duration']);?>"><?php echo formatTime($project['duration']); ?></td>
                            <td class="r"><b><?php echo formatPercent($percent); ?></b></td>
                            <?php if (($project['estimation'] - $project['duration']) > 0): ?>
                                <td class="r" title="<?php echo formatTimeDays($project['estimation'] - $project['duration']);?>"><?php echo formatTime($project['estimation'] - $project['duration']); ?></td>
                            <?php else: ?>
                                <td class="r" title="<?php echo formatTimeDays($project['estimation'] - $project['duration']);?>"><?php echo formatTime($project['duration'] - $project['estimation']); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                    <tfoot style="border-top: 2px solid #AAA">
                    <?php
                    $total   = $totals[$customer];
                    $style   = '';
                    $percent = 100 / $total['estimation'] * $total['duration'];

                    if ($total['duration'] > $total['estimation']):
                        $style = 'danger';
                    elseif (round($percent) >= 90):
                        $style = 'warning';
                    endif;
                    ?>

                    <tr class="<?php echo $style; ?>">
                        <td style="border-top-color: #000;"><b>Summe</b></td>
                        <td style="border-top-color: #000;" class="r"><b><?php echo formatTime($total['estimation']); ?></b></td>
                        <td style="border-top-color: #000;" class="r"><b><?php echo formatTime($total['duration']); ?></b></td>
                        <td style="border-top-color: #000;" class="r"><b><?php echo formatPercent($percent); ?></b></td>
                        <?php if (($total['estimation'] - $total['duration']) > 0): ?>
                            <td style="border-top-color: #000;" class="r"><b><?php echo formatTime($total['estimation'] - $total['duration']); ?></b></td>
                        <?php else: ?>
                            <td style="border-top-color: #000;" class="r"><b><?php echo formatTime($total['duration'] - $total['estimation']); ?></b></td>
                        <?php endif; ?>
                    </tr>
                    </tfoot>
                </table>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($projectId): ?>
        <p>
            <a href="projects.php">Alle Projekte anzeigen</a>
        </p>
    <?php endif; ?>


    <h4>Parameter</h4>
    <ul>
        <li><tt>timespan</tt>
            <a href="<?php echo getBaseUrl('timespan', 7); ?>">7</a> -
            <a href="<?php echo getBaseUrl('timespan', 14); ?>">14</a> -
            <a href="<?php echo getBaseUrl('timespan', 30); ?>">30</a>
        </li>
        <li><tt>project</tt> - single project view</li>
    </ul>
</div>
