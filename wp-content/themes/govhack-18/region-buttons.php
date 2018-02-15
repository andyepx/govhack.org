<?php
$regions = ['QLD', 'TAS', 'NSW', 'VIC', 'NT', 'SA', 'ACT', 'WA'];
sort($regions);
?>

<div class="wrapper region-quick-links">
    <?php foreach ($regions as $r): ?>
        <a class="button gh-blue" href="/regions/<?php echo strtolower($r) ?>"><?php echo $r ?></a>
    <?php endforeach; ?>
    <a class="button gh-blue black" href="/regions/nz">NZ</a>
</div>
