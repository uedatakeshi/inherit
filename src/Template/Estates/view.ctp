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
    <div class="row">
        <h4><?= __('No') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->id)); ?>
    </div>
    <div class="row">
        <h4><?= __('名称') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->name)); ?>
    </div>
    <div class="row">
        <h4><?= __('募集科目') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->subject)); ?>
    </div>
    <div class="row">
        <h4><?= __('概要') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->summary)); ?>
    </div>
    <div class="row">
        <h4><?= __('住所') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->address)); ?>
    </div>
    <div class="row">
        <h4><?= __('交通') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->access)); ?>
    </div>
    <div class="row">
        <h4><?= __('物件形態') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->property_form)); ?>
    </div>
    <div class="row">
        <h4><?= __('建物構造') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->structure)); ?>
    </div>
    <div class="row">
        <h4><?= __('築年月') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->build)); ?>
    </div>
    <div class="row">
        <h4><?= __('売買条件') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->sale_term)); ?>
    </div>
    <div class="row">
        <h4><?= __('賃貸条件') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->rent_term)); ?>
    </div>
    <div class="row">
        <h4><?= __('患者数') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->patients)); ?>
    </div>
    <div class="row">
        <h4><?= __('薬局枠') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->pharmacy)); ?>
    </div>
    <div class="row">
        <h4><?= __('付帯設備') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->equipment)); ?>
    </div>
    <div class="row">
        <h4><?= __('取引形態') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->transaction)); ?>
    </div>
    <div class="row">
        <h4><?= __('諸条件') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->terms)); ?>
    </div>
    <div class="row">
        <h4><?= __('お問合わせ方法') ?></h4>
        <?= $this->Text->autoParagraph(h($estate->contact)); ?>
    </div>
</div>
