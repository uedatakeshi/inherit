<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $estate->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $estate->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Estates'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="estates form large-9 medium-8 columns content">
    <?= $this->Form->create($estate) ?>
    <fieldset>
        <legend><?= __('Edit Estate') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('subject');
            echo $this->Form->input('summary');
            echo $this->Form->input('address');
            echo $this->Form->input('access');
            echo $this->Form->input('property_form');
            echo $this->Form->input('structure');
            echo $this->Form->input('build');
            echo $this->Form->input('sale_term');
            echo $this->Form->input('rent_term');
            echo $this->Form->input('patients');
            echo $this->Form->input('pharmacy');
            echo $this->Form->input('equipment');
            echo $this->Form->input('transaction');
            echo $this->Form->input('terms');
            echo $this->Form->input('contact');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
