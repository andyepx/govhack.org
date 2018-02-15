<?php if (gh_has_mentors_list(get_the_ID())): ?>
    <h3>Mentors</h3>
    <div>
        <?php gh_render_mentors_list(get_the_ID()); ?>
    </div>
<?php endif; ?>

