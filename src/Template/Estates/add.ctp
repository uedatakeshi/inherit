<style type="text/css">
.bar {
    height: 18px;
    background: green;
}
</style>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Estates'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="estates form large-9 medium-8 columns content">
        <input id="fileupload" type="file" name="files[]" data-url="/uploads/upload" multiple>
    <?= $this->Form->create($estate) ?>
    <fieldset>
        <legend><?= __('物件情報 新規登録') ?></legend>

        <input id="fileupload" type="file" name="files[]" data-url="/uploads/index" multiple>
        <div id="progress"> <div class="bar" style="width: 0%;"></div> </div>
        <div id="files"> </div>

        <?php
            echo $this->Form->input('name', ['label' => ['text' => '名称']]);
            echo $this->Form->input('subject', ['label' => ['text' => '募集科目']]);
            echo $this->Form->input('summary', ['label' => ['text' => '概要']]);
            echo $this->Form->input('address', ['label' => ['text' => '住所']]);
            echo $this->Form->input('access', ['label' => ['text' => '交通']]);
            echo $this->Form->input('property_form', ['label' => ['text' => '物件形態']]);
            echo $this->Form->input('structure', ['label' => ['text' => '建物構造']]);
            echo $this->Form->input('build', ['label' => ['text' => '築年月']]);
            echo $this->Form->input('sale_term', ['label' => ['text' => '売買条件']]);
            echo $this->Form->input('rent_term', ['label' => ['text' => '賃貸条件']]);
            echo $this->Form->input('patients', ['label' => ['text' => '患者数']]);
            echo $this->Form->input('pharmacy', ['label' => ['text' => '薬局枠']]);
            echo $this->Form->input('equipment', ['label' => ['text' => '付帯設備']]);
            echo $this->Form->input('transaction', ['label' => ['text' => '取引形態']]);
            echo $this->Form->input('terms', ['label' => ['text' => '諸条件']]);
            echo $this->Form->input('contact', ['label' => ['text' => 'お問合わせ方法']]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
