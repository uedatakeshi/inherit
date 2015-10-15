<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Estate'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="estates index large-9 medium-8 columns content">
    <h3><?= __('Estates') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('address') ?></th>
                <th><?= $this->Paginator->sort('access') ?></th>
                <th><?= $this->Paginator->sort('property_form') ?></th>
                <th><?= $this->Paginator->sort('build') ?></th>
                <th><?= $this->Paginator->sort('patients') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estates as $estate): ?>
            <tr>
                <td><?= $this->Number->format($estate->id) ?></td>
                <td><?= h($estate->name) ?></td>
                <td><?= h($estate->address) ?></td>
                <td><?= h($estate->access) ?></td>
                <td><?= h($estate->property_form) ?></td>
                <td><?= h($estate->build) ?></td>
                <td><?= h($estate->patients) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $estate->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $estate->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $estate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estate->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
