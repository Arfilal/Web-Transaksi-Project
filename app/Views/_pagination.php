<?php if (isset($pager) && $pager instanceof \CodeIgniter\Pager\PagerRenderer) : ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Tombol First -->
            <?php if ($pager->hasPrevious()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getFirst() ?>">⏮ First</a>
                </li>
            <?php endif ?>

            <!-- Tombol Previous -->
            <?php if ($pager->hasPrevious()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPreviousPage() ?>">⬅ Prev</a>
                </li>
            <?php endif ?>

            <!-- Nomor Halaman -->
            <?php foreach ($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach ?>

            <!-- Tombol Next -->
            <?php if ($pager->hasNext()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNextPage() ?>">Next ➡</a>
                </li>
            <?php endif ?>

            <!-- Tombol Last -->
            <?php if ($pager->hasNext()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getLast() ?>">Last ⏭</a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
<?php endif ?>
