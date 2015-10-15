<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Estate'), ['action' => 'edit', $estate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Estate'), ['action' => 'delete', $estate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $estate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Estates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Estate'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="estates view large-9 medium-8 columns content">
    <h3><?= h($estate->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($estate->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Address') ?></th>
            <td><?= h($estate->address) ?></td>
        </tr>
        <tr>
            <th><?= __('Access') ?></th>
            <td><?= h($estate->access) ?></td>
        </tr>
        <tr>
            <th><?= __('Property Form') ?></th>
            <td><?= h($estate->property_form) ?></td>
        </tr>
        <tr>
            <th><?= __('Build') ?></th>
            <td><?= h($estate->build) ?></td>
        </tr>
        <tr>
            <th><?= __('Patients') ?></th>
            <td><?= h($estate->patients) ?></td>
        </tr>
        <tr>
            <th><?= __('Pharmacy') ?></th>
            <td><?= h($estate->pharmacy) ?></td>
        </tr>
        <tr>
            <th><?= __('Equipment') ?></th>
            <td><?= h($estate->equipment) ?></td>
        </tr>
        <tr>
            <th><?= __('Transaction') ?></th>
            <td><?= h($estate->transaction) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($estate->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($estate->created) ?></tr>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($estate->modified) ?></tr>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Subject') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->subject)); ?>
    </div>
    <div class="row">
        <h4><?= __('Summary') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->summary)); ?>
    </div>
    <div class="row">
        <h4><?= __('Structure') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->structure)); ?>
    </div>
    <div class="row">
        <h4><?= __('Sale Term') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->sale_term)); ?>
    </div>
    <div class="row">
        <h4><?= __('Rent Term') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->rent_term)); ?>
    </div>
    <div class="row">
        <h4><?= __('Terms') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->terms)); ?>
    </div>
    <div class="row">
        <h4><?= __('Contact') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->contact)); ?>
    </div>
</div>
