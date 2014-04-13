<select id="department_id" class="select-dropdown">
    <option value="-1" selected>Spécialité</option>
    <option value="0" option-title="Spécialité">Toutes</option>
                <?php foreach ($departments as $key => $department):?>
    <option value="<?= $key; ?>"><?= $department; ?></option>
                <?php endforeach;?>
</select>
<select id="motive_id" class="select-dropdown">
    <option value="-1" selected>Motif</option>
    <option value="0" option-title="Motif">Tous</option>
                <?php foreach ($motives as $key => $motive):?>
    <option value="<?= $key; ?>"><?= $motive; ?></option>
                <?php endforeach;?>
</select>
<select id="school_id" class="select-dropdown">
    <option value="-1" selected>École</option>
    <option value="0" option-title="École">Toutes</option>
                <?php foreach ($schools as $key => $school):?>
    <option value="<?= $key; ?>"><?= $school; ?></option>
                <?php endforeach;?>
</select>
<select id="period_id" class="select-dropdown last">
    <option value="-1" selected>Période</option>
    <option value="0" date-min="" date-max="" option-title="Période">Toutes</option>
    <option value="1" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('3 years'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" >Depuis <?= date('Y')-3;?></option>
    <option value="2" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('1 year'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" >Depuis <?= date('Y')-1;?></option>
    <option value="3" date-min="<?= date('Y-m-d');?>" date-max="<?= date('Y-m-d');?>">Maintenant</option>
    <option value="4" date-min="<?= date('Y-m-d');?>" date-max="" >A venir</option>
</select>